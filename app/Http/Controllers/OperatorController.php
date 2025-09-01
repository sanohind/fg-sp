<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use App\Models\Item;
use App\Models\LogStorePull;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:operator');
    }

    public function index()
    {
        $assignedSlots = Slot::with(['rack', 'item'])
            ->whereNotNull('item_id')
            ->get();

        $recentActivities = LogStorePull::with(['user', 'slot'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('operator.index', compact('assignedSlots', 'recentActivities'));
    }

    public function posting()
    {
        return view('operator.posting');
    }

    // Scan slot by slot_name to determine target slot and item
    public function scanSlotnameForPosting(Request $request)
    {
        try {
            // Check authentication first - try multiple methods
            $user = null;
            $authMethod = 'none';
            
            // Method 1: Try Auth facade
            if (Auth::check()) {
                $user = Auth::user();
                $authMethod = 'Auth::user()';
            }
            
            // Method 2: Try session-based user
            if (!$user && $request->session()->has('user')) {
                $sessionUser = $request->session()->get('user');
                if ($sessionUser && isset($sessionUser['id'])) {
                    $user = (object) $sessionUser; // Convert to object for compatibility
                    $authMethod = 'session';
                }
            }
            
            // Method 3: Try request user (Sanctum)
            if (!$user && $request->user()) {
                $user = $request->user();
                $authMethod = 'request->user()';
            }
            
            if (!$user) {
                \Log::error('All authentication methods failed in scanSlotnameForPosting', [
                    'auth_check' => Auth::check(),
                    'session_has_user' => $request->session()->has('user'),
                    'request_user' => $request->user() ? 'exists' : 'null'
                ]);
                return response()->json([
                    'error' => 'User tidak terautentikasi. Silakan login ulang.'
                ], 401);
            }
            
            \Log::info('Authentication successful in scanSlotnameForPosting', [
                'method' => $authMethod,
                'user_id' => $user->id ?? 'unknown',
                'user_name' => $user->name ?? 'unknown'
            ]);
            
            // Validate request data
            $request->validate([
                'slot_name' => 'required|string|min:3|max:4'
            ]);
            
            // Additional validation for slot_name length
            $slotName = $request->slot_name;
            if (strlen($slotName) < 3 || strlen($slotName) > 4) {
                return response()->json([
                    'error' => 'Slot name harus memiliki panjang 3-4 karakter'
                ], 400);
            }

            // Check if slot exists first
            $slot = Slot::with(['rack', 'item'])
                ->where('slot_name', $request->slot_name)
                ->first();

            if (!$slot) {
                return response()->json([
                    'error' => 'Slot "' . $request->slot_name . '" tidak ditemukan dalam sistem'
                ], 404);
            }

            // Check if slot has an item assigned
            if (!$slot->item_id) {
                return response()->json([
                    'error' => 'Slot "' . $request->slot_name . '" tidak memiliki item yang diatur'
                ], 400);
            }

            return response()->json([
                'slot' => $slot,
                'rack' => $slot->rack,
                'item' => $slot->item,
                'package_image' => $slot->item ? ($slot->item->packaging_image_url ?? null) : null,
                'packaging_image_url' => $slot->item ? ($slot->item->packaging_image_url ?? null) : null,
                'current_qty' => $slot->current_qty,
                'capacity' => $slot->capacity,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Data tidak valid: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in scanSlotnameForPosting', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    // Store by ERP after slot scanned (increment count)
    public function storeByErp(Request $request)
    {
        try {
            // Check authentication first - try multiple methods
            $user = null;
            $authMethod = 'none';
            
            // Method 1: Try Auth facade
            if (Auth::check()) {
                $user = Auth::user();
                $authMethod = 'Auth::user()';
            }
            
            // Method 2: Try session-based user
            if (!$user && $request->session()->has('user')) {
                $sessionUser = $request->session()->get('user');
                if (isset($sessionUser['id'])) {
                    $user = (object) $sessionUser; // Convert to object for compatibility
                    $authMethod = 'session';
                }
            }
            
            // Method 3: Try request user (Sanctum)
            if (!$user && $request->user()) {
                $user = $request->user();
                $authMethod = 'request->user()';
            }
            
            if (!$user) {
                \Log::error('All authentication methods failed in storeByErp', [
                    'auth_check' => Auth::check(),
                    'session_has_user' => $request->session()->has('user'),
                    'request_user' => $request->user() ? 'exists' : 'null'
                ]);
                return response()->json([
                    'error' => 'User tidak terautentikasi. Silakan login ulang.'
                ], 401);
            }
            
            \Log::info('Authentication successful', [
                'method' => $authMethod,
                'user_id' => $user->id ?? 'unknown',
                'user_name' => $user->name ?? 'unknown'
            ]);
            
            // Additional debugging
            \Log::info('Authentication details', [
                'auth_check' => Auth::check(),
                'auth_id' => Auth::id(),
                'user_object' => $user ? 'exists' : 'null',
                'user_id' => $user ? $user->id : 'null',
                'user_name' => $user ? ($user->name ?? 'no_name') : 'null'
            ]);
            
            // Debug logging
            \Log::info('storeByErp called', [
                'request' => $request->all(),
                'user_id' => $user->id,
                'user_name' => $user->name ?? 'unknown'
            ]);
            
            // Test database connection
            try {
                DB::connection()->getPdo();
                \Log::info('Database connection OK');
            } catch (\Exception $dbError) {
                \Log::error('Database connection failed', ['error' => $dbError->getMessage()]);
                throw $dbError;
            }

            $request->validate([
                'slot_name' => 'required|string|exists:slots,slot_name',
                'erp_code' => 'required|string|min:58|max:63',
                'lot_no' => 'nullable|string'
            ]);
            
            // Parse ERP code structure
            $fullScanString = $request->erp_code;
        $erpParts = explode(';', $fullScanString);
        
        if (count($erpParts) !== 8) {
            return response()->json([
                'error' => 'Format ERP code tidak valid. Harus memiliki 8 kolom yang dipisahkan dengan semicolon (;)'
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
            return response()->json([
                'error' => 'Lot number tidak boleh kosong dalam ERP code'
            ], 400);
        }
        
        \Log::info('ERP code parsed successfully', [
            'actual_erp_code' => $actualErpCode,
            'quantity' => $quantity,
            'lot_no' => $lotNo,
            'full_scan_string' => $fullScanString
        ]);

        // Resolve slot by name
        $slot = Slot::with(['rack', 'item'])
            ->where('slot_name', $request->slot_name)
            ->first();

        if (!$slot) {
            return response()->json([
                'error' => 'Slot tidak ditemukan'
            ], 400);
        }

        $item = $slot->item;

        // Bandingkan dengan actual ERP code (kolom 1) bukan full string
        if (!$item || $item->erp_code !== $actualErpCode) {
            return response()->json([
                'error' => 'ERP code tidak sesuai dengan item pada slot ini',
                'expected' => $item ? $item->erp_code : 'null',
                'scanned' => $actualErpCode
            ], 400);
        }

        // Validate quantity from ERP code matches item quantity
        if ($item->qty != $quantity) {
            return response()->json([
                'error' => 'Qty item tidak sama',
                'expected_qty' => $item->qty,
                'scanned_qty' => $quantity
            ], 400);
        }

        // Check if lot number already exists in log_store_pull table
        $existingLot = LogStorePull::where('lot_no', $lotNo)
            ->where('slot_name', $slot->slot_name)
            ->first();
        
        if ($existingLot) {
            return response()->json([
                'error' => 'Lot number sudah ada dalam slot ini',
                'lot_no' => $lotNo,
                'existing_action' => $existingLot->action
            ], 400);
        }

        // Capacity check - per scan adds one box to the slot
        if (($slot->current_qty + 1) > $slot->capacity) {
            return response()->json([
                'error' => 'Slot sudah penuh',
                'current_qty' => $slot->current_qty,
                'quantity_to_add' => 1,
                'capacity' => $slot->capacity,
                'will_exceed_by' => ($slot->current_qty + 1) - $slot->capacity
            ], 400);
        }

        // Update slot quantity - per scan increments by one box
        $newQty = $slot->current_qty + 1;
        $slot->update(['current_qty' => $newQty]);

        // Create log entry dengan actual ERP code dan quantity
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

        // Refresh slot data
        $slot->refresh();
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan 1 box (ERP: ' . $actualErpCode . ')',
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
        ]);
        } catch (\Exception $e) {
        \Log::error('storeByErp error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);
        
        return response()->json([
            'error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ], 500);
    }
    }

    // Scan slot QR code untuk posting
    public function scanSlotForPosting(Request $request)
    {
        $request->validate([
            'slot_name' => 'required|string|exists:slots,slot_name'
        ]);

        $slot = Slot::with(['rack', 'item'])
            ->where('slot_name', $request->slot_name)
            ->first();

        if (!$slot) {
            return response()->json(['error' => 'Slot tidak ditemukan'], 404);
        }

        // Jika slot sudah terisi, tampilkan info
        if ($slot->item_id) {
            return response()->json([
                'error' => 'Slot sudah terisi',
                'slot' => $slot,
                'current_qty' => $slot->item->qty,
                'capacity' => $slot->capacity,
                'part_no' => $slot->item->part_no,
                'erp_code' => $slot->item->erp_code
            ], 400);
        }

        // Jika slot kosong, tampilkan info slot
        return response()->json([
            'slot' => $slot,
            'available' => true
        ]);
    }

    // Scan box QR code untuk posting
    public function scanBoxForPosting(Request $request)
    {
        $request->validate([
            'slot_name' => 'required|string|exists:slots,slot_name',
            'part_no' => 'required|string|exists:items,part_no',
            'erp_code' => 'required|string|exists:items,erp_code',
            'lot_no' => 'required|string'
        ]);

        $slot = Slot::with(['rack'])
            ->where('slot_name', $request->slot_name)
            ->first();

        $item = Item::where('part_no', $request->part_no)
            ->where('erp_code', $request->erp_code)
            ->first();

        // Cek apakah item sudah ter-assign ke slot lain
        if ($item->slot_id && $item->slot_id != $slot->id) {
            return response()->json([
                'error' => 'Item sudah ter-assign ke slot lain',
                'current_slot' => $item->slot->slot_name
            ], 400);
        }

        // Jika slot kosong, assign item pertama
        if (!$slot->item_id) {
            $slot->update(['item_id' => $item->id]);
            $item->update(['slot_id' => $slot->id, 'qty' => 1]);
        } else {
            // Cek apakah part_no sama dengan yang sudah ada di slot
            if ($slot->item->part_no !== $request->part_no) {
                return response()->json([
                    'error' => 'Part number tidak sesuai dengan slot',
                    'expected_part' => $slot->item->part_no,
                    'scanned_part' => $request->part_no
                ], 400);
            }

            // Cek kapasitas
            if ($slot->item->qty >= $slot->capacity) {
                return response()->json([
                    'error' => 'Slot sudah penuh',
                    'current_qty' => $slot->item->qty,
                    'capacity' => $slot->capacity
                ], 400);
            }

            // Tambah quantity
            $item->update(['qty' => $item->qty + 1]);
        }

        // Log aktivitas dengan lot number yang unik
        LogStorePull::create([
            'erp_code' => $request->erp_code,
            'part_no' => $request->part_no,
            'slot_id' => $slot->id,
            'slot_name' => $slot->slot_name,
            'rack_name' => $slot->rack->rack_name,
            'lot_no' => $request->lot_no,
            'action' => 'store',
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'qty' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Box dengan lot ' . $request->lot_no . ' berhasil ditambahkan ke slot',
            'current_qty' => $item->qty,
            'capacity' => $slot->capacity,
            'is_full' => $item->qty >= $slot->capacity,
            'lot_no' => $request->lot_no
        ]);
    }

    // Pulling Process
    public function pulling()
    {
        return view('operator.pulling');
    }

    // Scan slot QR code untuk pulling
    public function scanSlotForPulling(Request $request)
    {
        $request->validate([
            'slot_name' => 'required|string|exists:slots,slot_name'
        ]);

        $slot = Slot::with(['rack', 'item'])
            ->where('slot_name', $request->slot_name)
            ->first();

        if (!$slot) {
            return response()->json(['error' => 'Slot tidak ditemukan'], 404);
        }

        // Cek apakah slot terisi
        if (!$slot->item_id) {
            return response()->json([
                'error' => 'Slot kosong',
                'slot' => $slot
            ], 400);
        }

        return response()->json([
            'slot' => $slot,
            'current_qty' => $slot->item->qty,
            'capacity' => $slot->capacity,
            'part_no' => $slot->item->part_no,
            'erp_code' => $slot->item->erp_code
        ]);
    }

    // Scan box QR code untuk pulling
    public function scanBoxForPulling(Request $request)
    {
        $request->validate([
            'slot_name' => 'required|string|exists:slots,slot_name',
            'part_no' => 'required|string',
            'erp_code' => 'required|string',
            'lot_no' => 'required|string'
        ]);

        $slot = Slot::with(['rack', 'item'])
            ->where('slot_name', $request->slot_name)
            ->first();

        // Cek apakah slot terisi
        if (!$slot->item_id) {
            return response()->json([
                'error' => 'Slot kosong'
            ], 400);
        }

        // Cek apakah part_no sesuai
        if ($slot->item->part_no !== $request->part_no) {
            return response()->json([
                'error' => 'Part number tidak sesuai dengan slot',
                'expected_part' => $slot->item->part_no,
                'scanned_part' => $request->part_no
            ], 400);
        }

        // Cek apakah masih ada stock
        if ($slot->item->qty <= 0) {
            return response()->json([
                'error' => 'Stock sudah habis'
            ], 400);
        }

        // Kurangi quantity
        $newQty = $slot->item->qty - 1;
        $slot->item->update(['qty' => $newQty]);

        // Jika quantity menjadi 0, hapus assignment
        if ($newQty <= 0) {
            $slot->update(['item_id' => null]);
            $slot->item->update(['slot_id' => null]);
        }

        // Log aktivitas dengan lot number yang unik
        LogStorePull::create([
            'erp_code' => $request->erp_code,
            'part_no' => $request->part_no,
            'slot_id' => $slot->id,
            'slot_name' => $slot->slot_name,
            'rack_name' => $slot->rack->rack_name,
            'lot_no' => $request->lot_no,
            'action' => 'pull',
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'qty' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Box dengan lot ' . $request->lot_no . ' berhasil diambil dari slot',
            'current_qty' => $newQty,
            'capacity' => $slot->capacity,
            'is_empty' => $newQty <= 0,
            'lot_no' => $request->lot_no
        ]);
    }

    // Get slot info by QR code
    public function getSlotInfo($slotName)
    {
        $slot = Slot::with(['rack', 'item'])
            ->where('slot_name', $slotName)
            ->first();

        if (!$slot) {
            return response()->json(['error' => 'Slot tidak ditemukan'], 404);
        }

        return response()->json([
            'slot' => $slot,
            'current_qty' => $slot->item ? $slot->item->qty : 0,
            'capacity' => $slot->capacity,
            'is_full' => $slot->item ? ($slot->item->qty >= $slot->capacity) : false,
            'is_empty' => !$slot->item || $slot->item->qty <= 0,
            'part_no' => $slot->item ? $slot->item->part_no : null,
            'erp_code' => $slot->item ? $slot->item->erp_code : null
        ]);
    }

    // Search item by part_no or erp_code
    public function searchItem(Request $request)
    {
        $query = $request->get('query');
        
        $items = Item::where('part_no', 'LIKE', "%{$query}%")
            ->orWhere('erp_code', 'LIKE', "%{$query}%")
            ->with('slot')
            ->get();

        return response()->json($items);
    }

    // Get lot numbers for a specific slot (untuk tracking)
    public function getSlotLotNumbers($slotName)
    {
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
            'slot_name' => $slotName,
            'lot_numbers' => $lotNumbers,
            'total_lots' => $lotNumbers->count()
        ]);
    }
}