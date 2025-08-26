<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use App\Models\Item;
use App\Models\LogStorePull;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // Posting Process
    public function posting()
    {
        return view('operator.posting');
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
            'lot_no' => $request->lot_no, // Lot number unik per box
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
            'lot_no' => $request->lot_no, // Lot number unik per box
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
