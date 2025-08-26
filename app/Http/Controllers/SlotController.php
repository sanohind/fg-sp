<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use App\Models\SlotHistory;
use App\Models\Rack;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SlotController extends Controller
{
    public function index()
    {
        $slots = Slot::with(['rack', 'item'])->get();
        return view('admin.slot.index', compact('slots'));
    }

    public function create()
    {
        $racks = Rack::all();
        return view('admin.slot.create', compact('racks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rack_id' => 'required|exists:racks,id',
            'slot_name' => 'required|string|max:255|unique:slots',
            'capacity' => 'required|integer|min:1',
        ], [
            'rack_id.required' => 'Rack harus dipilih.',
            'rack_id.exists' => 'Rack tidak ditemukan.',
            'slot_name.required' => 'Slot Name harus diisi.',
            'slot_name.unique' => 'Slot Name sudah ada dalam sistem.',
            'capacity.required' => 'Capacity harus diisi.',
            'capacity.integer' => 'Capacity harus berupa angka.',
            'capacity.min' => 'Capacity minimal 1.',
        ]);

        try {
            Slot::create([
                'rack_id' => $request->rack_id,
                'slot_name' => $request->slot_name,
                'capacity' => $request->capacity,
                'current_qty' => 0,
            ]);

            return redirect()->route('admin.slots.index')->with('success', 'Slot berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan slot. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $slot = Slot::findOrFail($id);
        $racks = Rack::all();
        return view('admin.slot.edit', compact('slot', 'racks'));
    }

    public function update(Request $request, $id)
    {
        $slot = Slot::findOrFail($id);
        
        $request->validate([
            'rack_id' => 'required|exists:racks,id',
            'slot_name' => 'required|string|max:255|unique:slots,slot_name,' . $id,
            'capacity' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
        ], [
            'rack_id.required' => 'Rack harus dipilih.',
            'rack_id.exists' => 'Rack tidak ditemukan.',
            'slot_name.required' => 'Slot Name harus diisi.',
            'slot_name.unique' => 'Slot Name sudah ada dalam sistem.',
            'capacity.required' => 'Capacity harus diisi.',
            'capacity.integer' => 'Capacity harus berupa angka.',
            'capacity.min' => 'Capacity minimal 1.',
            'reason.required' => 'Alasan perubahan harus diisi.',
        ]);

        try {
            $oldData = [
                'rack_id' => $slot->rack_id,
                'slot_name' => $slot->slot_name,
                'capacity' => $slot->capacity,
            ];

            $slot->update([
                'rack_id' => $request->rack_id,
                'slot_name' => $request->slot_name,
                'capacity' => $request->capacity,
            ]);

            // Record history untuk setiap field yang berubah
            if (($oldData['rack_id'] ?? null) != $request->rack_id) {
                SlotHistory::create([
                    'slot_id' => $slot->id,
                    'action' => 'update',
                    'field_changed' => 'rack_id',
                    'old_value' => $oldData['rack_id'] ?? null,
                    'new_value' => $request->rack_id,
                    'changed_by' => Auth::id(),
                    'name' => 'Rack ID Updated',
                    'notes' => $request->reason,
                ]);
            }

            if (($oldData['slot_name'] ?? null) != $request->slot_name) {
                SlotHistory::create([
                    'slot_id' => $slot->id,
                    'action' => 'update',
                    'field_changed' => 'slot_name',
                    'old_value' => $oldData['slot_name'] ?? null,
                    'new_value' => $request->slot_name,
                    'changed_by' => Auth::id(),
                    'name' => 'Slot Name Updated',
                    'notes' => $request->reason,
                ]);
            }

            if (($oldData['capacity'] ?? null) != $request->capacity) {
                SlotHistory::create([
                    'slot_id' => $slot->id,
                    'action' => 'update',
                    'field_changed' => 'capacity',
                    'old_value' => $oldData['capacity'] ?? null,
                    'new_value' => $request->capacity,
                    'changed_by' => Auth::id(),
                    'name' => 'Capacity Updated',
                    'notes' => $request->reason,
                ]);
            }

            return redirect()->route('admin.slots.index')->with('success', 'Slot berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui slot. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        try {
            $slot = Slot::findOrFail($id);
            
            // Record history untuk delete operation
            SlotHistory::create([
                'slot_id' => $slot->id,
                'action' => 'delete',
                'field_changed' => 'slot',
                'old_value' => json_encode([
                    'rack_id' => $slot->rack_id,
                    'slot_name' => $slot->slot_name,
                    'capacity' => $slot->capacity,
                    'item_id' => $slot->item_id
                ]),
                'new_value' => null,
                'changed_by' => Auth::id(),
                'name' => 'Slot Deleted',
                'notes' => 'Slot deleted',
            ]);

            $slot->delete();

            return redirect()->route('admin.slots.index')->with('success', 'Slot berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.slots.index')->with('error', 'Gagal menghapus slot. Silakan coba lagi.');
        }
    }

    public function assignPart($id)
    {
        $slot = Slot::findOrFail($id);
        $items = Item::whereNull('slot_id')->get(); // Only unassigned items
        return view('admin.slot.assign-part', compact('slot', 'items'));
    }

    public function storeAssignPart(Request $request, $id)
    {
        $slot = Slot::findOrFail($id);
        
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'reason' => 'required|string|max:500',
        ]);

        $item = Item::findOrFail($request->item_id);
        
        // Check if item is already assigned
        if ($item->slot_id) {
            return back()->with('error', 'Item is already assigned to another slot');
        }

        $oldItemId = $slot->item_id;

        // Update slot with item
        $slot->update(['item_id' => $request->item_id]);
        
        // Update item with slot
        $item->update(['slot_id' => $slot->id]);

        // Record history untuk assign part operation
        SlotHistory::create([
            'slot_id' => $slot->id,
            'action' => 'update',
            'field_changed' => 'item_id',
            'old_value' => $oldItemId,
            'new_value' => $slot->item_id,
            'changed_by' => Auth::id(),
            'name' => 'Item Assigned',
            'notes' => $request->reason,
        ]);

        return redirect()->route('admin.slot.index')->with('success', 'Part assigned successfully');
    }

    public function changePart($id)
    {
        $slot = Slot::findOrFail($id);
        $items = Item::whereNull('slot_id')->orWhere('id', $slot->item_id)->get();
        return view('admin.slot.change-part', compact('slot', 'items'));
    }

    public function storeChangePart(Request $request, $id)
    {
        $slot = Slot::findOrFail($id);
        
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'reason' => 'required|string|max:500',
        ]);

        $oldItemId = $slot->item_id;
        $newItem = Item::findOrFail($request->item_id);

        // If new item is already assigned to another slot
        if ($newItem->slot_id && $newItem->slot_id != $slot->id) {
            return back()->with('error', 'Item is already assigned to another slot');
        }

        // Remove old item assignment
        if ($oldItemId) {
            $oldItem = Item::find($oldItemId);
            $oldItem->update(['slot_id' => null]);
        }

        // Assign new item
        $slot->update(['item_id' => $request->item_id]);
        $newItem->update(['slot_id' => $slot->id]);

        // Record history untuk change part operation
        SlotHistory::create([
            'slot_id' => $slot->id,
            'action' => 'update',
            'field_changed' => 'item_id',
            'old_value' => $oldItemId,
            'new_value' => $slot->item_id,
            'changed_by' => Auth::id(),
            'name' => 'Item Changed',
            'notes' => $request->reason,
        ]);

        return redirect()->route('admin.slot.index')->with('success', 'Part changed successfully');
    }

    public function detail($id)
    {
        $slot = Slot::with(['rack', 'item'])->findOrFail($id);
        return view('admin.slot.detail', compact('slot'));
    }

    public function history($id)
    {
        $slot = Slot::findOrFail($id);
        $histories = SlotHistory::where('slot_id', $id)->with(['changedBy', 'item'])->orderBy('created_at', 'desc')->get();
        
        return view('admin.slot.history', compact('slot', 'histories'));
    }
}
