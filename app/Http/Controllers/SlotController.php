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
    private function getCurrentUserId()
    {
        return session('user.id');
    }

    public function index()
    {
        $slots = Slot::with(['rack', 'item'])->get();
        
        // Calculate statistics
        $totalSlots = $slots->count();
        $filledSlots = $slots->where('item_id', '!=', null)->count();
        $emptySlots = $totalSlots - $filledSlots;
        
        return view('admin.slot', compact('slots', 'totalSlots', 'filledSlots', 'emptySlots'));
    }

    public function create()
    {
        $racks = Rack::all();
        return view('admin.add-slot', compact('racks'));
    }

    public function store(Request $request)
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
            // Debug logging
            \Log::info('Slot creation attempt', [
                'request_data' => $request->all(),
                'user_id' => $this->getCurrentUserId()
            ]);

            // Find rack by name
            $rack = Rack::where('rack_name', $request->rack)->first();
            if (!$rack) {
                \Log::error('Rack not found', ['rack_name' => $request->rack]);
                return back()->withInput()->with('error', 'Rack tidak ditemukan.');
            }

            \Log::info('Rack found', ['rack' => $rack->toArray()]);

            $slot = Slot::create([
                'rack_id' => $rack->id,
                'slot_name' => $request->slot_name,
                'capacity' => $request->capacity,
                'current_qty' => 0,
            ]);

            \Log::info('Slot created successfully', ['slot' => $slot->toArray()]);

            return redirect()->route('admin.slot')->with('success', 'Slot berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('Slot creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Gagal menambahkan slot. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $slot = Slot::findOrFail($id);
        $racks = Rack::all();
        return view('admin.edit-slot', compact('slot', 'racks'));
    }

    public function update(Request $request, $id)
    {
        $slot = Slot::findOrFail($id);
        
        $request->validate([
            'rack' => 'required|string|max:255',
            'slot_name' => 'required|string|max:255|unique:slots,slot_name,' . $id,
            'capacity' => 'required|integer|min:1',
            'notes' => 'required|string|max:500',
        ], [
            'rack.required' => 'Rack harus dipilih.',
            'slot_name.required' => 'Slot Name harus diisi.',
            'slot_name.unique' => 'Slot Name sudah ada dalam sistem.',
            'capacity.required' => 'Capacity harus diisi.',
            'capacity.integer' => 'Capacity harus berupa angka.',
            'capacity.min' => 'Capacity minimal 1.',
            'notes.required' => 'Notes harus diisi.',
        ]);

        try {
            // Find rack by name
            $rack = Rack::where('rack_name', $request->rack)->first();
            if (!$rack) {
                return back()->withInput()->with('error', 'Rack tidak ditemukan.');
            }

            $oldData = [
                'rack_id' => $slot->rack_id,
                'slot_name' => $slot->slot_name,
                'capacity' => $slot->capacity,
            ];

            $slot->update([
                'rack_id' => $rack->id,
                'slot_name' => $request->slot_name,
                'capacity' => $request->capacity,
            ]);

            // Record history untuk setiap field yang berubah
            if (($oldData['rack_id'] ?? null) != $rack->id) {
                SlotHistory::create([
                    'slot_id' => $slot->id,
                    'action' => 'update',
                    'field_changed' => 'rack_id',
                    'old_value' => $oldData['rack_id'] ?? null,
                    'new_value' => $rack->id,
                    'changed_by' => $this->getCurrentUserId(),
                    'name' => 'Rack ID Updated',
                    'notes' => $request->notes,
                ]);
            }

            if (($oldData['slot_name'] ?? null) != $request->slot_name) {
                SlotHistory::create([
                    'slot_id' => $slot->id,
                    'action' => 'update',
                    'field_changed' => 'slot_name',
                    'old_value' => $oldData['slot_name'] ?? null,
                    'new_value' => $request->slot_name,
                    'changed_by' => $this->getCurrentUserId(),
                    'name' => 'Slot Name Updated',
                    'notes' => $request->notes,
                ]);
            }

            if (($oldData['capacity'] ?? null) != $request->capacity) {
                SlotHistory::create([
                    'slot_id' => $slot->id,
                    'action' => 'update',
                    'field_changed' => 'capacity',
                    'old_value' => $oldData['capacity'] ?? null,
                    'new_value' => $request->capacity,
                    'changed_by' => $this->getCurrentUserId(),
                    'name' => 'Capacity Updated',
                    'notes' => $request->notes,
                ]);
            }

            return redirect()->route('admin.slot')->with('success', 'Slot berhasil diperbarui!');
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
                'changed_by' => $this->getCurrentUserId(),
                'name' => 'Slot Deleted',
                'notes' => 'Slot deleted',
            ]);

            $slot->delete();

            return redirect()->route('admin.slot')->with('success', 'Slot berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.slot')->with('error', 'Gagal menghapus slot. Silakan coba lagi.');
        }
    }

    public function assignPart($id)
    {
        $slot = Slot::findOrFail($id);
        
        // Get items that are not assigned to any slot
        $assignedItemIds = Slot::whereNotNull('item_id')->pluck('item_id')->toArray();
        $items = Item::whereNotIn('id', $assignedItemIds)->get();
        
        return view('admin.assign-part', compact('slot', 'items'));
    }

    public function storeAssignPart(Request $request, $id)
    {
        $slot = Slot::findOrFail($id);
        
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'reason' => 'required|string|max:500',
        ]);

        $item = Item::findOrFail($request->item_id);
        
        // Check if item is already assigned to another slot
        $existingSlot = Slot::where('item_id', $request->item_id)->first();
        if ($existingSlot) {
            return back()->with('error', 'Item is already assigned to another slot');
        }

        $oldItemId = $slot->item_id;

        // Update slot with item
        $slot->update(['item_id' => $request->item_id]);

        // Record history untuk assign part operation
        SlotHistory::create([
            'slot_id' => $slot->id,
            'action' => 'update',
            'field_changed' => 'item_id',
            'old_value' => $oldItemId,
            'new_value' => $slot->item_id,
            'changed_by' => $this->getCurrentUserId(),
            'name' => 'Item Assigned',
            'notes' => $request->reason,
        ]);

        return redirect()->route('admin.slot')->with('success', 'Part assigned successfully');
    }

    public function changePart($id)
    {
        $slot = Slot::findOrFail($id);
        
        // Get items that are not assigned to any slot, plus the current item in this slot
        $assignedItemIds = Slot::whereNotNull('item_id')->where('id', '!=', $slot->id)->pluck('item_id')->toArray();
        $items = Item::whereNotIn('id', $assignedItemIds)->orWhere('id', $slot->item_id)->get();
        
        return view('admin.change-part', compact('slot', 'items'));
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
        $existingSlot = Slot::where('item_id', $request->item_id)->where('id', '!=', $slot->id)->first();
        if ($existingSlot) {
            return back()->with('error', 'Item is already assigned to another slot');
        }

        // Remove old item assignment - no need to update item table since it doesn't have slot_id
        // The relationship is managed through the slots table

        // Assign new item
        $slot->update(['item_id' => $request->item_id]);

        // Record history untuk change part operation
        SlotHistory::create([
            'slot_id' => $slot->id,
            'action' => 'update',
            'field_changed' => 'item_id',
            'old_value' => $oldItemId,
            'new_value' => $slot->item_id,
            'changed_by' => $this->getCurrentUserId(),
            'name' => 'Item Changed',
            'notes' => $request->reason,
        ]);

        return redirect()->route('admin.slot')->with('success', 'Part changed successfully');
    }

    public function detail($id)
    {
        $slot = Slot::with(['rack', 'item'])->findOrFail($id);
        return view('admin.slot-detail', compact('slot'));
    }

    public function history($id)
    {
        $slot = Slot::findOrFail($id);
        $histories = SlotHistory::where('slot_id', $id)->with('changedBy')->orderBy('created_at', 'desc')->get();
        
        return view('admin.slot-history', compact('slot', 'histories'));
    }
}
