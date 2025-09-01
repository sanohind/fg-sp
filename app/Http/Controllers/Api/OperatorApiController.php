<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Slot;
use App\Models\Rack;
use App\Models\LogStorePull;
use App\Models\SlotHistory;
use App\Models\ItemHistory;
use App\Models\RackHistory;
use Carbon\Carbon;

class OperatorApiController extends Controller
{
    /**
     * Get operator dashboard data
     */
    public function dashboard(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Get recent activities
            $recentActivities = LogStorePull::with(['slot.item', 'slot.rack'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Get summary statistics
            $today = Carbon::today();
            $stats = [
                'today_store' => LogStorePull::where('user_id', $user->id)
                    ->where('action', 'store')
                    ->whereDate('created_at', $today)
                    ->count(),
                'today_pull' => LogStorePull::where('user_id', $user->id)
                    ->where('action', 'pull')
                    ->whereDate('created_at', $today)
                    ->count(),
                'total_slots' => Slot::count(),
                'occupied_slots' => Slot::where('current_qty', '>', 0)->count(),
                'empty_slots' => Slot::where('current_qty', 0)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                    ],
                    'stats' => $stats,
                    'recent_activities' => $recentActivities->map(function ($activity) {
                        return [
                            'id' => $activity->id,
                            'action' => $activity->action,
                            'quantity' => $activity->qty,
                            'slot_name' => $activity->slot_name,
                            'item_description' => $activity->slot->item ? $activity->slot->item->description : null,
                            'rack_name' => $activity->rack_name,
                            'lot_no' => $activity->lot_no,
                            'created_at' => $activity->created_at->format('Y-m-d H:i:s'),
                        ];
                    }),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Scan slot by slot_name to determine target slot and item
     */
    public function scanSlotnameForPosting(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'slot_name' => 'required|string|min:3|max:4'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Additional validation for slot_name length
            $slotName = $request->slot_name;
            if (strlen($slotName) < 3 || strlen($slotName) > 4) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot name harus memiliki panjang 3-4 karakter'
                ], 400);
            }

            // Check if slot exists first
            $slot = Slot::with(['rack', 'item'])
                ->where('slot_name', $request->slot_name)
                ->first();

            if (!$slot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot "' . $request->slot_name . '" tidak ditemukan dalam sistem'
                ], 404);
            }

            // Check if slot has an item assigned
            if (!$slot->item_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot "' . $request->slot_name . '" tidak memiliki item yang diatur'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Slot valid untuk posting',
                'data' => [
                    'slot' => $slot,
                    'rack' => $slot->rack,
                    'item' => $slot->item,
                    'package_image' => $slot->item ? ($slot->item->packaging_image_url ?? null) : null,
                    'packaging_image_url' => $slot->item ? ($slot->item->packaging_image_url ?? null) : null,
                    'current_qty' => $slot->current_qty,
                    'capacity' => $slot->capacity,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in API scanSlotnameForPosting', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error scanning slot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store items by ERP code
     */
    public function storeByErp(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi. Silakan login ulang.'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'slot_name' => 'required|string|exists:slots,slot_name',
                'erp_code' => 'required|string|min:58|max:63', // Full scan string
                'lot_no' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Test database connection
            try {
                DB::connection()->getPdo();
                \Log::info('Database connection OK');
            } catch (\Exception $dbError) {
                \Log::error('Database connection failed', ['error' => $dbError->getMessage()]);
                throw $dbError;
            }
            
            // Parse ERP code structure
            $fullScanString = $request->erp_code;
            $erpParts = explode(';', $fullScanString);
            
            if (count($erpParts) !== 8) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Format ERP code tidak valid. Harus memiliki 8 kolom yang dipisahkan dengan semicolon (;)'
                ], 400);
            }
            
            // Extract components - KOLOM 1 adalah ERP CODE yang sebenarnya
            $actualErpCode = trim($erpParts[0]);
            $quantity = trim($erpParts[1]);
            $lotNo = trim($erpParts[2]);
            $customerName = trim($erpParts[3]);
            $poLine = trim($erpParts[4]);
            $sequence = trim($erpParts[5]);
            $dnNo = trim($erpParts[6]);
            $seqDn = trim($erpParts[7]);
            
            // Validate lot_no is not empty
            if (empty($lotNo)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Lot number tidak boleh kosong dalam ERP code'
                ], 400);
            }
            
            \Log::info('ERP code parsed successfully', [
                'actual_erp_code' => $actualErpCode,
                'quantity' => $quantity,
                'lot_no' => $lotNo,
                'full_scan_string' => $fullScanString
            ]);

            $slot = Slot::with(['rack', 'item'])
                ->where('slot_name', $request->slot_name)
                ->first();

            if (!$slot) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            $item = $slot->item;

            // Compare with actual ERP code (kolom 1) not full string
            if (!$item || $item->erp_code !== $actualErpCode) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'ERP code tidak sesuai dengan item pada slot ini',
                    'data' => [
                        'expected' => $item ? $item->erp_code : 'null',
                        'scanned' => $actualErpCode
                    ]
                ], 400);
            }

            // Validate quantity from ERP code matches item quantity
            if ($item->qty != $quantity) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Qty item tidak sama',
                    'data' => [
                        'expected_qty' => $item->qty,
                        'scanned_qty' => $quantity
                    ]
                ], 400);
            }

            // Check if lot number already exists
            $existingLot = LogStorePull::where('lot_no', $lotNo)
                ->where('slot_name', $slot->slot_name)
                ->first();
            
            if ($existingLot) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Lot number sudah ada dalam slot ini',
                    'data' => [
                        'lot_no' => $lotNo,
                        'existing_action' => $existingLot->action
                    ]
                ], 400);
            }

            // Capacity check - per scan adds one box to the slot
            if (($slot->current_qty + 1) > $slot->capacity) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Slot sudah penuh',
                    'data' => [
                        'current_qty' => $slot->current_qty,
                        'quantity_to_add' => 1,
                        'capacity' => $slot->capacity,
                        'will_exceed_by' => ($slot->current_qty + 1) - $slot->capacity
                    ]
                ], 400);
            }

            // Update slot quantity - per scan increments by one box
            $newQty = $slot->current_qty + 1;
            $slot->update(['current_qty' => $newQty]);

            // Create log entry with actual ERP code and quantity
            $logData = [
                'erp_code' => $actualErpCode,
                'part_no' => $item->part_no,
                'slot_id' => $slot->id,
                'slot_name' => $slot->slot_name,
                'rack_name' => $slot->rack->rack_name,
                'lot_no' => $lotNo,
                'action' => 'store',
                'user_id' => $user->id,
                'name' => $user->name ?? 'Unknown User',
                'qty' => intval($quantity)
            ];
            
            LogStorePull::create($logData);

            DB::commit();

            // Refresh slot data
            $slot->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan 1 box (ERP: ' . $actualErpCode . ')',
                'data' => [
                    'current_qty' => $slot->current_qty,
                    'capacity' => $slot->capacity,
                    'slot_name' => $slot->slot_name,
                    'rack_name' => $slot->rack->rack_name,
                    'part_no' => $item->part_no,
                    'lot_no' => $lotNo,
                    'erp_code' => $actualErpCode,
                    'part_image_url' => $item->part_image_url ?? null,
                    'packaging_image_url' => $item->packaging_image_url ?? null,
                    'item' => $item
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('storeByErp error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Scan slot for pull operation
     */
    public function scanSlotForPull(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'slot_name' => 'required|string|exists:slots,slot_name'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
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

            // Check if slot is filled
            if (!$slot->item_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot kosong'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Slot valid untuk pull',
                'data' => [
                    'slot' => $slot,
                    'current_qty' => $slot->current_qty,
                    'capacity' => $slot->capacity,
                    'part_no' => $slot->item->part_no,
                    'erp_code' => $slot->item->erp_code,
                    'rack_name' => $slot->rack->rack_name
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error scanning slot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pull items by lot number
     */
    public function pullByLotNumber(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'slot_name' => 'required|string|exists:slots,slot_name',
                'part_no' => 'required|string',
                'erp_code' => 'required|string',
                'lot_no' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $slot = Slot::with(['rack', 'item'])
                ->where('slot_name', $request->slot_name)
                ->first();
            
            if (!$slot) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            // Check if slot is filled
            if (!$slot->item_id) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Slot kosong'
                ], 400);
            }

            // Check if part_no matches
            if ($slot->item->part_no !== $request->part_no) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Part number tidak sesuai dengan slot',
                    'data' => [
                        'expected_part' => $slot->item->part_no,
                        'scanned_part' => $request->part_no
                    ]
                ], 400);
            }

            // Check if there's still stock
            if ($slot->current_qty <= 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Stock sudah habis'
                ], 400);
            }

            // Reduce quantity
            $newQty = $slot->current_qty - 1;
            $slot->update(['current_qty' => $newQty]);

            // If quantity becomes 0, remove assignment
            if ($newQty <= 0) {
                $slot->update(['item_id' => null]);
                $slot->item->update(['slot_id' => null]);
            }

            // Create log entry for single box pull
            $logData = [
                'erp_code' => $request->erp_code,
                'part_no' => $request->part_no,
                'slot_id' => $slot->id,
                'slot_name' => $slot->slot_name,
                'rack_name' => $slot->rack->rack_name,
                'lot_no' => $request->lot_no,
                'action' => 'pull',
                'user_id' => $user->id,
                'name' => $user->name ?? 'Unknown User',
                'qty' => 1
            ];
            
            LogStorePull::create($logData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '1 box dengan lot ' . $request->lot_no . ' berhasil diambil dari slot',
                'data' => [
                    'current_qty' => $newQty,
                    'capacity' => $slot->capacity,
                    'is_empty' => $newQty <= 0,
                    'lot_no' => $request->lot_no,
                    'slot_name' => $slot->slot_name,
                    'rack_name' => $slot->rack->rack_name
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('pullByLotNumber error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get slot information
     */
    public function getSlotInfo(Request $request, $slotName): JsonResponse
    {
        try {
            $slot = Slot::with(['item', 'rack'])->where('slot_name', $slotName)->first();
            
            if (!$slot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'slot' => $slot,
                    'current_qty' => $slot->current_qty,
                    'capacity' => $slot->capacity,
                    'is_full' => $slot->current_qty >= $slot->capacity,
                    'is_empty' => $slot->current_qty <= 0,
                    'part_no' => $slot->item ? $slot->item->part_no : null,
                    'erp_code' => $slot->item ? $slot->item->erp_code : null,
                    'item' => $slot->item,
                    'rack' => $slot->rack
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching slot info: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search items
     */
    public function searchItems(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|min:2',
                'limit' => 'nullable|integer|min:1|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = $request->input('query');
            $limit = $request->input('limit', 20);

            $items = Item::where('erp_code', 'LIKE', "%{$query}%")
                ->orWhere('part_no', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->orWhere('model', 'LIKE', "%{$query}%")
                ->orWhere('customer', 'LIKE', "%{$query}%")
                ->with(['slot'])
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $items,
                    'total_found' => $items->count(),
                    'query' => $query,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get lot numbers for a specific slot
     */
    public function getSlotLotNumbers($slotName): JsonResponse
    {
        try {
            $lotNumbers = LogStorePull::where('slot_name', $slotName)
                ->where('action', 'store')
                ->whereNotIn('lot_no', function($query) use ($slotName) {
                    $query->select('lot_no')
                        ->from('log_store_pull')
                        ->where('slot_name', $slotName)
                        ->where('action', 'pull');
                })
                ->pluck('lot_no')
                ->unique();

            return response()->json([
                'success' => true,
                'data' => [
                    'slot_name' => $slotName,
                    'lot_numbers' => $lotNumbers,
                    'total_lots' => $lotNumbers->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching lot numbers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity history
     */
    public function getActivityHistory(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'action' => 'nullable|string|in:store,pull',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = LogStorePull::query()
                ->where('user_id', $user->id);

            // Filter by action
            if ($request->has('action')) {
                $query->where('action', $request->action);
            }

            // Filter by date range
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $limit = $request->input('limit', 20);
            $activities = $query->orderBy('created_at', 'desc')
                ->paginate($limit);

            $activities->getCollection()->transform(function ($activity) {
                return [
                    'id' => $activity->id,
                    'action' => $activity->action,
                    'quantity' => $activity->qty,
                    'lot_no' => $activity->lot_no,
                    'slot_name' => $activity->slot_name,
                    'rack_name' => $activity->rack_name,
                    'erp_code' => $activity->erp_code,
                    'part_no' => $activity->part_no,
                    'user_name' => $activity->name,
                    'timestamp' => $activity->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'activities' => $activities->items(),
                    'pagination' => [
                        'current_page' => $activities->currentPage(),
                        'last_page' => $activities->lastPage(),
                        'per_page' => $activities->perPage(),
                        'total' => $activities->total(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching activity history: ' . $e->getMessage()
            ], 500);
        }
    }
}