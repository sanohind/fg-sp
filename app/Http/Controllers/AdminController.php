<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use App\Models\Slot;
use App\Models\Item;
use App\Models\User;
use App\Models\LogStorePull;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:superadmin,admin');
    }

    public function index()
    {
        // Get statistics for dashboard
        $totalRacks = Rack::count();
        $totalSlots = Slot::count();
        $totalItems = Item::count();
        $totalUsers = User::count();
        $assignedSlots = Slot::whereNotNull('item_id')->count();
        $unassignedSlots = Slot::whereNull('item_id')->count();
        
        // Recent activities
        $recentLogs = LogStorePull::with(['user', 'slot'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Rack utilization
        $racks = Rack::withCount(['slots', 'slots as assigned_slots_count' => function($query) {
            $query->whereNotNull('item_id');
        }])->get();

        return view('admin.index', compact(
            'totalRacks',
            'totalSlots', 
            'totalItems',
            'totalUsers',
            'assignedSlots',
            'unassignedSlots',
            'recentLogs',
            'racks'
        ));
    }

    // Items Management
    public function items()
    {
        $items = Item::all();
        $totalItems = $items->count();
        
        return view('admin.items', compact('items', 'totalItems'));
    }

    public function addItem()
    {
        return view('admin.add-part');
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'erp_code' => 'required|string|max:255|unique:items',
            'part_no' => 'required|string|max:255',
            'description' => 'required|string',
            'model' => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'package_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'part_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'erp_code.required' => 'ERP Code harus diisi.',
            'erp_code.unique' => 'ERP Code sudah ada dalam sistem.',
            'part_no.required' => 'Part No harus diisi.',
            'description.required' => 'Description harus diisi.',
            'model.required' => 'Model harus diisi.',
            'customer.required' => 'Customer harus diisi.',
            'quantity.required' => 'Quantity harus diisi.',
            'quantity.integer' => 'Quantity harus berupa angka.',
            'quantity.min' => 'Quantity minimal 0.',
            'package_image.image' => 'Package Image harus berupa gambar.',
            'package_image.mimes' => 'Package Image harus berformat jpeg, png, jpg, atau gif.',
            'package_image.max' => 'Package Image maksimal 2MB.',
            'part_image.image' => 'Part Image harus berupa gambar.',
            'part_image.mimes' => 'Part Image harus berformat jpeg, png, jpg, atau gif.',
            'part_image.max' => 'Part Image maksimal 2MB.',
        ]);

        try {
            $data = [
                'erp_code' => $request->erp_code,
                'part_no' => $request->part_no,
                'description' => $request->description,
                'model' => $request->model,
                'customer' => $request->customer,
                'qty' => $request->quantity,
            ];

            // Handle image uploads
            if ($request->hasFile('package_image')) {
                $packageImagePath = $request->file('package_image')->store('items/packaging', 'public');
                $data['packaging_img'] = $packageImagePath;
            }

            if ($request->hasFile('part_image')) {
                $partImagePath = $request->file('part_image')->store('items/parts', 'public');
                $data['part_img'] = $partImagePath;
            }

            Item::create($data);

            return redirect()->route('admin.items')->with('success', 'Item berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan item. Silakan coba lagi.');
        }
    }

    // Slots Management
    public function slot()
    {
        $slots = Slot::with(['rack', 'item'])->get();
        
        // Calculate statistics
        $totalSlots = $slots->count();
        $filledSlots = $slots->where('item_id', '!=', null)->count();
        $emptySlots = $totalSlots - $filledSlots;
        
        return view('admin.slot', compact('slots', 'totalSlots', 'filledSlots', 'emptySlots'));
    }

    public function addSlot()
    {
        $racks = Rack::all();
        return view('admin.add-slot', compact('racks'));
    }

    public function storeSlot(Request $request)
    {
        $request->validate([
            'rack' => 'required|string|max:255',
            'slot_name' => 'required|string|max:255|unique:slots',
            'capacity' => 'required|integer|min:1',
        ], [
            'rack.required' => 'Rack harus dipilih.',
            'slot_name.required' => 'Slot Name harus diisi.',
            'slot_name.unique' => 'Slot Name sudah ada dalam sistem.',
            'capacity.required' => 'Capacity harus diisi.',
            'capacity.integer' => 'Capacity harus berupa angka.',
            'capacity.min' => 'Capacity minimal 1.',
        ]);

        try {
            // Find rack by name
            $rack = Rack::where('rack_name', $request->rack)->first();
            if (!$rack) {
                return back()->withInput()->withErrors(['rack' => 'Rack tidak ditemukan.'])->with('error', 'Rack tidak ditemukan.');
            }

            Slot::create([
                'rack_id' => $rack->id,
                'slot_name' => $request->slot_name,
                'capacity' => $request->capacity,
                'current_qty' => 0,
            ]);

            return redirect()->route('admin.slot')->with('success', 'Slot berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan slot. Silakan coba lagi.');
        }
    }

    public function slotDetail($id)
    {
        $slot = Slot::with(['rack', 'item'])->findOrFail($id);
        return view('admin.slot-detail', compact('slot'));
    }

    public function assignPart($id)
    {
        $slot = Slot::with(['rack', 'item'])->findOrFail($id);
        $items = Item::all();
        return view('admin.assign-part', compact('slot', 'items'));
    }

    public function storeAssignPart(Request $request, $id)
    {
        $request->validate([
            'part_no' => 'required|exists:items,part_no',
        ], [
            'part_no.required' => 'Part No harus dipilih.',
            'part_no.exists' => 'Part No tidak ditemukan dalam sistem.',
        ]);

        try {
            $slot = Slot::findOrFail($id);
            $item = Item::where('part_no', $request->part_no)->first();

            if (!$slot) {
                return back()->with('error', 'Slot tidak ditemukan.');
            }

            if (!$item) {
                return back()->with('error', 'Item tidak ditemukan.');
            }

            $slot->update(['item_id' => $item->id]);

            return redirect()->route('admin.slot')->with('success', 'Part berhasil ditugaskan ke slot!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menugaskan part ke slot. Silakan coba lagi.');
        }
    }

    // History Management
    public function history()
    {
        $logs = LogStorePull::with(['user', 'slot'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Calculate statistics
        $storedCount = $logs->where('action', 'store')->count();
        $pulledCount = $logs->where('action', 'pull')->count();
        
        return view('admin.history', compact('logs', 'storedCount', 'pulledCount'));
    }

    // Rack Management
    public function rackIndex()
    {
        $racks = Rack::withCount(['slots', 'slots as assigned_slots_count' => function($query) {
            $query->whereNotNull('item_id');
        }])->get();
        
        return view('admin.rack', compact('racks'));
    }

    public function itemIndex()
    {
        $items = Item::with('slot')->get();
        return view('admin.items', compact('items'));
    }

    public function historyIndex()
    {
        $logs = LogStorePull::with(['user', 'slot'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.history', compact('logs'));
    }
}
