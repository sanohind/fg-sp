<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use App\Models\Item;
use App\Models\LogStorePull;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OperatorApiController extends Controller
{
    /**
     * Get operator dashboard data
     */
    public function dashboard()
    {
        try {
            $user = Auth::user();
            
            // Get assigned slots with items
            $assignedSlots = Slot::with(['rack', 'item'])
                ->whereNotNull('item_id')
                ->get()
                ->map(function ($slot) {
                    return [
                        'id' => $slot->id,
                        'slot_name' => $slot->slot_name,
                        'rack_name' => $slot->rack->rack_name,
                        'current_qty' => $slot->current_qty,
                        'capacity' => $slot->capacity,
                        'part_no' => $slot->item->part_no,
                        'erp_code' => $slot->item->erp_code,
                        'is_full' => $slot->current_qty >= $slot->capacity,
                        'is_empty' => $slot->current_qty <= 0
                    ];
                });

            // Get recent activities
            $recentActivities = LogStorePull::with(['user', 'slot'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'action' => $log->action,
                        'slot_name' => $log->slot_name,
                        'rack_name' => $log->rack_name,
                        'part_no' => $log->part_no,
                        'lot_no' => $log->lot_no,
                        'qty' => $log->qty,
                        'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                        'user_name' => $log->name
                    ];
                });

            // Get summary statistics
            $totalSlots = Slot::count();
            $filledSlots = Slot::whereNotNull('item_id')->count();
            $emptySlots = $totalSlots - $filledSlots;
            $totalItems = Slot::whereNotNull('item_id')->sum('current_qty');

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => [
                        'total_slots' => $totalSlots,
                        'filled_slots' => $filledSlots,
                        'empty_slots' => $emptySlots,
                        'total_items' => $totalItems
                    ],
                    'assigned_slots' => $assignedSlots,
                    'recent_activities' => $recentActivities
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Scan slot for store operation
     */
    public function scanSlotForStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'slot_name' => 'required|string|min:3|max:4'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 400);
            }

            $slot = Slot::with(['rack', 'item'])
                ->where('slot_name', $request->slot_name)
                ->first();

            if (!$slot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            if (!$slot->item_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot kosong, tidak ada item yang diatur',
                    'data' => [
                        'slot_name' => $slot->slot_name,
                        'rack_name' => $slot->rack->rack_name,
                        'available' => true
                    ]
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'slot' => [
                        'id' => $slot->id,
                        'slot_name' => $slot->slot_name,
                        'rack_name' => $slot->rack->rack_name,
                        'current_qty' => $slot->current_qty,
                        'capacity' => $slot->capacity
                    ],
                    'item' => [
                        'id' => $slot->item->id,
                        'part_no' => $slot->item->part_no,
                        'erp_code' => $slot->item->erp_code,
                        'packaging_image_url' => $slot->item->packaging_image_url,
                        'part_image_url' => $slot->item->part_image_url
                    ],
                    'is_full' => $slot->current_qty >= $slot->capacity
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store item by ERP code
     */
    public function storeByErp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'slot_name' => 'required|string|min:3|max:4',
                'erp_code' => 'required|string|min:60|max:63'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 400);
            }

            $user = Auth::user();
            $slotName = $request->slot_name;
            $erpCode = $request->erp_code;

            // Parse ERP code structure
            $erpParts = explode(';', $erpCode);
            if (count($erpParts) !== 8) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format ERP code tidak valid. Harus memiliki 8 kolom yang dipisahkan dengan semicolon (;)'
                ], 400);
            }

            // Extract components
            $actualErpCode = trim($erpParts[0]); // Column 1: ERP part number
            $quantity = trim($erpParts[1]);      // Column 2: Quantity
            $lotNo = trim($erpParts[2]);         // Column 3: Lot number
            $customerName = trim($erpParts[3]);  // Column 4: Customer name
            $poLine = trim($erpParts[4]);        // Column 5: PO line
            $sequence = trim($erpParts[5]);      // Column 6: Sequence
            $dnNo = trim($erpParts[6]);          // Column 7: DN number
            $seqDn = trim($erpParts[7]);         // Column 8: Sequence DN

            if (empty($lotNo)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lot number tidak boleh kosong dalam ERP code'
                ], 400);
            }

            // Get slot and validate
            $slot = Slot::with(['rack', 'item'])
                ->where('slot_name', $slotName)
                ->first();

            if (!$slot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            if (!$slot->item_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot kosong, tidak ada item yang diatur'
                ], 400);
            }

            $item = $slot->item;
            
            // ✅ NEW: Validate ERP code matches item ERP code
            if ($item->erp_code !== $actualErpCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'ERP code tidak sesuai dengan item pada slot ini',
                    'expected' => $item->erp_code,
                    'scanned' => $actualErpCode
                ], 400);
            }

            // ✅ NEW: Validate quantity from ERP code matches item quantity
            if ($item->qty != $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Qty item tidak sama',
                    'expected_qty' => $item->qty,
                    'scanned_qty' => $quantity
                ], 400);
            }

            // ✅ NEW: Check if lot number already exists in log_store_pull table
            $existingLot = LogStorePull::where('lot_no', $lotNo)
                ->where('slot_name', $slotName)
                ->first();
            
            if ($existingLot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lot number sudah ada dalam slot ini',
                    'lot_no' => $lotNo,
                    'existing_action' => $existingLot->action
                ], 400);
            }

            // Check capacity
            if ($slot->current_qty >= $slot->capacity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot sudah penuh',
                    'data' => [
                        'current_qty' => $slot->current_qty,
                        'capacity' => $slot->capacity
                    ]
                ], 400);
            }

            DB::beginTransaction();
            try {
                // Update slot quantity
                $newQty = $slot->current_qty + 1;
                $slot->update(['current_qty' => $newQty]);

                // Create log entry
                $logData = [
                    'erp_code' => $actualErpCode,
                    'part_no' => $item->part_no,
                    'slot_id' => $slot->id,
                    'slot_name' => $slot->slot_name,
                    'rack_name' => $slot->rack->rack_name,
                    'lot_no' => $lotNo,
                    'action' => 'store',
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'qty' => 1
                ];

                $logEntry = LogStorePull::create($logData);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menambahkan 1 box',
                    'data' => [
                        'slot_name' => $slot->slot_name,
                        'rack_name' => $slot->rack->rack_name,
                        'part_no' => $item->part_no,
                        'lot_no' => $lotNo,
                        'current_qty' => $newQty,
                        'capacity' => $slot->capacity,
                        'is_full' => $newQty >= $slot->capacity,
                        'log_id' => $logEntry->id
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Scan slot for pull operation
     */
    public function scanSlotForPull(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'slot_name' => 'required|string|min:3|max:4'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 400);
            }

            $slot = Slot::with(['rack', 'item'])
                ->where('slot_name', $request->slot_name)
                ->first();

            if (!$slot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            if (!$slot->item_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot kosong',
                    'data' => [
                        'slot_name' => $slot->slot_name,
                        'rack_name' => $slot->rack->rack_name,
                        'available' => false
                    ]
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'slot' => [
                        'id' => $slot->id,
                        'slot_name' => $slot->slot_name,
                        'rack_name' => $slot->rack->rack_name,
                        'current_qty' => $slot->current_qty,
                        'capacity' => $slot->capacity
                    ],
                    'item' => [
                        'id' => $slot->item->id,
                        'part_no' => $slot->item->part_no,
                        'erp_code' => $slot->item->erp_code,
                        'packaging_image_url' => $slot->item->packaging_image_url,
                        'part_image_url' => $slot->item->part_image_url
                    ],
                    'is_empty' => $slot->current_qty <= 0
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pull item by lot number
     */
    public function pullByLotNumber(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'slot_name' => 'required|string|min:3|max:4',
                'lot_no' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 400);
            }

            $user = Auth::user();
            $slotName = $request->slot_name;
            $lotNo = $request->lot_no;

            // Get slot and validate
            $slot = Slot::with(['rack', 'item'])
                ->where('slot_name', $slotName)
                ->first();

            if (!$slot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            if (!$slot->item_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot kosong'
                ], 400);
            }

            // Check if lot number exists in this slot
            $lotExists = LogStorePull::where('slot_name', $slotName)
                ->where('action', 'store')
                ->whereNotIn('lot_no', function($query) use ($slotName, $lotNo) {
                    $query->select('lot_no')
                        ->from('log_store_pull')
                        ->where('slot_name', $slotName)
                        ->where('lot_no', $lotNo)
                        ->where('action', 'pull');
                })
                ->exists();

            if (!$lotExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lot number tidak ditemukan atau sudah diambil dari slot ini'
                ], 400);
            }

            // Check if slot has items
            if ($slot->current_qty <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot sudah kosong'
                ], 400);
            }

            DB::beginTransaction();
            try {
                // Update slot quantity
                $newQty = $slot->current_qty - 1;
                $slot->update(['current_qty' => $newQty]);

                // Create log entry
                $logData = [
                    'erp_code' => $slot->item->erp_code,
                    'part_no' => $slot->item->part_no,
                    'slot_id' => $slot->id,
                    'slot_name' => $slot->slot_name,
                    'rack_name' => $slot->rack->rack_name,
                    'lot_no' => $lotNo,
                    'action' => 'pull',
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'qty' => 1
                ];

                $logEntry = LogStorePull::create($logData);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengambil 1 box dengan lot ' . $lotNo,
                    'data' => [
                        'slot_name' => $slot->slot_name,
                        'rack_name' => $slot->rack->rack_name,
                        'part_no' => $slot->item->part_no,
                        'lot_no' => $lotNo,
                        'current_qty' => $newQty,
                        'capacity' => $slot->capacity,
                        'is_empty' => $newQty <= 0,
                        'log_id' => $logEntry->id
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get slot information
     */
    public function getSlotInfo($slotName)
    {
        try {
            $slot = Slot::with(['rack', 'item'])
                ->where('slot_name', $slotName)
                ->first();

            if (!$slot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            // Get available lot numbers for this slot
            $availableLots = LogStorePull::where('slot_name', $slotName)
                ->where('action', 'store')
                ->whereNotIn('lot_no', function($query) use ($slotName) {
                    $query->select('lot_no')
                        ->from('log_store_pull')
                        ->where('slot_name', $slotName)
                        ->where('action', 'pull');
                })
                ->pluck('lot_no')
                ->unique()
                ->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'slot' => [
                        'id' => $slot->id,
                        'slot_name' => $slot->slot_name,
                        'rack_name' => $slot->rack->rack_name,
                        'current_qty' => $slot->current_qty,
                        'capacity' => $slot->capacity
                    ],
                    'item' => $slot->item ? [
                        'id' => $slot->item->id,
                        'part_no' => $slot->item->part_no,
                        'erp_code' => $slot->item->erp_code,
                        'packaging_image_url' => $slot->item->packaging_image_url,
                        'part_image_url' => $slot->item->part_image_url
                    ] : null,
                    'available_lots' => $availableLots,
                    'total_available_lots' => $availableLots->count(),
                    'is_full' => $slot->item ? ($slot->current_qty >= $slot->capacity) : false,
                    'is_empty' => !$slot->item || $slot->current_qty <= 0
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search items by part number or ERP code
     */
    public function searchItems(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 400);
            }

            $query = $request->get('query');
            
            $items = Item::where('part_no', 'LIKE', "%{$query}%")
                ->orWhere('erp_code', 'LIKE', "%{$query}%")
                ->with(['slot' => function($q) {
                    $q->with('rack');
                }])
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'part_no' => $item->part_no,
                        'erp_code' => $item->erp_code,
                        'packaging_image_url' => $item->packaging_image_url,
                        'part_image_url' => $item->part_image_url,
                        'slot' => $item->slot ? [
                            'slot_name' => $item->slot->slot_name,
                            'rack_name' => $item->slot->rack->rack_name,
                            'current_qty' => $item->slot->current_qty,
                            'capacity' => $item->slot->capacity
                        ] : null
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $items,
                    'total' => $items->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get operator activity history
     */
    public function getActivityHistory(Request $request)
    {
        try {
            $user = Auth::user();
            
            $query = LogStorePull::with(['slot.rack'])
                ->where('user_id', $user->id);

            // Filter by date range if provided
            if ($request->has('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            
            if ($request->has('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            // Filter by action if provided
            if ($request->has('action') && in_array($request->action, ['store', 'pull'])) {
                $query->where('action', $request->action);
            }

            // Filter by slot if provided
            if ($request->has('slot_name')) {
                $query->where('slot_name', $request->slot_name);
            }

            $activities = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 20));

            $activities->getCollection()->transform(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'slot_name' => $log->slot_name,
                    'rack_name' => $log->rack_name,
                    'part_no' => $log->part_no,
                    'erp_code' => $log->erp_code,
                    'lot_no' => $log->lot_no,
                    'qty' => $log->qty,
                    'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                    'user_name' => $log->name
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $activities
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
