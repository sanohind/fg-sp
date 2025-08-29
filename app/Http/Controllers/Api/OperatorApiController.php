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
                            'quantity' => $activity->quantity,
                            'slot_name' => $activity->slot->slot_name,
                            'item_description' => $activity->slot->item->description,
                            'rack_name' => $activity->slot->rack->rack_name,
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
     * Scan slot for store operation
     */
    public function scanSlotForStore(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'slot_name' => 'required|string|max:50',
                'erp_code' => 'required|string|max:100',
                'quantity' => 'required|integer|min:1',
                'lot_number' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $slot = Slot::with(['item', 'rack'])->where('slot_name', $request->slot_name)->first();
            
            if (!$slot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            // Check if slot has available space
            if ($slot->getAvailableSpace() < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak memiliki ruang yang cukup',
                    'data' => [
                        'slot_name' => $slot->slot_name,
                        'available_space' => $slot->getAvailableSpace(),
                        'requested_quantity' => $request->quantity,
                        'current_quantity' => $slot->current_qty,
                        'capacity' => $slot->capacity,
                    ]
                ], 400);
            }

            // Check if item matches
            if ($slot->item && $slot->item->erp_code !== $request->erp_code) {
                return response()->json([
                    'success' => false,
                    'message' => 'ERP Code tidak sesuai dengan item di slot ini',
                    'data' => [
                        'slot_item_erp' => $slot->item->erp_code,
                        'scanned_erp' => $request->erp_code,
                    ]
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Slot valid untuk store',
                'data' => [
                    'slot' => [
                        'slot_name' => $slot->slot_name,
                        'rack_name' => $slot->rack->rack_name,
                        'current_quantity' => $slot->current_qty,
                        'available_space' => $slot->getAvailableSpace(),
                        'capacity' => $slot->capacity,
                    ],
                    'item' => $slot->item ? [
                        'erp_code' => $slot->item->erp_code,
                        'part_no' => $slot->item->part_no,
                        'description' => $slot->item->description,
                        'model' => $slot->item->model,
                        'customer' => $slot->item->customer,
                    ] : null,
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
     * Store items by ERP code
     */
    public function storeByErp(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'slot_name' => 'required|string|max:50',
                'erp_code' => 'required|string|max:100',
                'quantity' => 'required|integer|min:1',
                'lot_number' => 'nullable|string|max:100',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $slot = Slot::with(['item', 'rack'])->where('slot_name', $request->slot_name)->first();
            
            if (!$slot) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            // Check if slot has available space
            if ($slot->getAvailableSpace() < $request->quantity) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak memiliki ruang yang cukup'
                ], 400);
            }

            // Check if item matches
            if ($slot->item && $slot->item->erp_code !== $request->erp_code) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'ERP Code tidak sesuai dengan item di slot ini'
                ], 400);
            }

            // Update slot quantity
            $oldQuantity = $slot->current_qty;
            $slot->current_qty += $request->quantity;
            $slot->save();

            // Create log
            $log = LogStorePull::create([
                'user_id' => $request->user()->id,
                'slot_id' => $slot->id,
                'action' => 'store',
                'quantity' => $request->quantity,
                'lot_number' => $request->lot_number,
                'notes' => $request->notes,
                'timestamp' => now(),
            ]);

            // Create slot history
            SlotHistory::create([
                'slot_id' => $slot->id,
                'action' => 'store',
                'old_quantity' => $oldQuantity,
                'new_quantity' => $slot->current_qty,
                'quantity_changed' => $request->quantity,
                'user_id' => $request->user()->id,
                'timestamp' => now(),
            ]);

            // Create item history if item exists
            if ($slot->item) {
                ItemHistory::create([
                    'item_id' => $slot->item->id,
                    'action' => 'store',
                    'quantity' => $request->quantity,
                    'slot_id' => $slot->id,
                    'user_id' => $request->user()->id,
                    'timestamp' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil disimpan',
                'data' => [
                    'log_id' => $log->id,
                    'slot_name' => $slot->slot_name,
                    'rack_name' => $slot->rack->rack_name,
                    'quantity_stored' => $request->quantity,
                    'new_total_quantity' => $slot->current_qty,
                    'available_space' => $slot->getAvailableSpace(),
                    'timestamp' => $log->timestamp->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error storing item: ' . $e->getMessage()
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
                'slot_name' => 'required|string|max:50',
                'quantity' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $slot = Slot::with(['item', 'rack'])->where('slot_name', $request->slot_name)->first();
            
            if (!$slot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            // Check if slot has enough items
            if ($slot->current_qty < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak memiliki item yang cukup',
                    'data' => [
                        'slot_name' => $slot->slot_name,
                        'available_quantity' => $slot->current_qty,
                        'requested_quantity' => $request->quantity,
                    ]
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Slot valid untuk pull',
                'data' => [
                    'slot' => [
                        'slot_name' => $slot->slot_name,
                        'rack_name' => $slot->rack->rack_name,
                        'current_quantity' => $slot->current_qty,
                        'available_quantity' => $slot->current_qty,
                    ],
                    'item' => $slot->item ? [
                        'erp_code' => $slot->item->erp_code,
                        'part_no' => $slot->item->part_no,
                        'description' => $slot->item->description,
                        'model' => $slot->item->model,
                        'customer' => $slot->item->customer,
                    ] : null,
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
            $validator = Validator::make($request->all(), [
                'slot_name' => 'required|string|max:50',
                'quantity' => 'required|integer|min:1',
                'lot_number' => 'nullable|string|max:100',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $slot = Slot::with(['item', 'rack'])->where('slot_name', $request->slot_name)->first();
            
            if (!$slot) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            // Check if slot has enough items
            if ($slot->current_qty < $request->quantity) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Slot tidak memiliki item yang cukup'
                ], 400);
            }

            // Update slot quantity
            $oldQuantity = $slot->current_qty;
            $slot->current_qty -= $request->quantity;
            $slot->save();

            // Create log
            $log = LogStorePull::create([
                'user_id' => $request->user()->id,
                'slot_id' => $slot->id,
                'action' => 'pull',
                'quantity' => $request->quantity,
                'lot_number' => $request->lot_number,
                'notes' => $request->notes,
                'timestamp' => now(),
            ]);

            // Create slot history
            SlotHistory::create([
                'slot_id' => $slot->id,
                'action' => 'pull',
                'old_quantity' => $oldQuantity,
                'new_quantity' => $slot->current_qty,
                'quantity_changed' => $request->quantity,
                'user_id' => $request->user()->id,
                'timestamp' => now(),
            ]);

            // Create item history if item exists
            if ($slot->item) {
                ItemHistory::create([
                    'item_id' => $slot->item->id,
                    'action' => 'pull',
                    'quantity' => $request->quantity,
                    'slot_id' => $slot->id,
                    'user_id' => $request->user()->id,
                    'timestamp' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil diambil',
                'data' => [
                    'log_id' => $log->id,
                    'slot_name' => $slot->slot_name,
                    'rack_name' => $slot->rack->rack_name,
                    'quantity_pulled' => $request->quantity,
                    'remaining_quantity' => $slot->current_qty,
                    'timestamp' => $log->timestamp->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error pulling item: ' . $e->getMessage()
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
                    'slot' => [
                        'slot_name' => $slot->slot_name,
                        'rack_name' => $slot->rack->rack_name,
                        'current_quantity' => $slot->current_qty,
                        'capacity' => $slot->capacity,
                        'available_space' => $slot->getAvailableSpace(),
                        'occupancy_percentage' => $slot->getOccupancyPercentage(),
                        'is_empty' => $slot->isEmpty(),
                        'is_full' => $slot->isFull(),
                    ],
                    'item' => $slot->item ? [
                        'erp_code' => $slot->item->erp_code,
                        'part_no' => $slot->item->part_no,
                        'description' => $slot->item->description,
                        'model' => $slot->item->model,
                        'customer' => $slot->item->customer,
                        'part_image' => $slot->item->part_image_url,
                        'packaging_image' => $slot->item->packaging_image_url,
                    ] : null,
                    'rack' => [
                        'rack_name' => $slot->rack->rack_name,
                        'location' => $slot->rack->location,
                    ],
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
                ->with(['slots.rack'])
                ->limit($limit)
                ->get();

            $items = $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'erp_code' => $item->erp_code,
                    'part_no' => $item->part_no,
                    'description' => $item->description,
                    'model' => $item->model,
                    'customer' => $item->customer,
                    'total_quantity' => $item->qty,
                    'stored_quantity' => $item->getTotalStoredQuantity(),
                    'available_quantity' => $item->getAvailableQuantity(),
                    'part_image' => $item->part_image_url,
                    'packaging_image' => $item->packaging_image_url,
                    'slots' => $item->slots->map(function ($slot) {
                        return [
                            'slot_name' => $slot->slot_name,
                            'rack_name' => $slot->rack->rack_name,
                            'current_quantity' => $slot->current_qty,
                            'capacity' => $slot->capacity,
                        ];
                    }),
                ];
            });

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
     * Get activity history
     */
    public function getActivityHistory(Request $request): JsonResponse
    {
        try {
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

            $user = $request->user();
            $query = LogStorePull::with(['slot.item', 'slot.rack'])
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
                    'quantity' => $activity->quantity,
                    'lot_number' => $activity->lot_number,
                    'notes' => $activity->notes,
                    'slot_name' => $activity->slot->slot_name,
                    'rack_name' => $activity->slot->rack->rack_name,
                    'item_description' => $activity->slot->item ? $activity->slot->item->description : null,
                    'erp_code' => $activity->slot->item ? $activity->slot->item->erp_code : null,
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
