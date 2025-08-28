<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use App\Models\RackHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RackController extends Controller
{
    private function getCurrentUserId()
    {
        return session('user.id');
    }

    public function index()
    {
        $racks = Rack::withCount([
            'slots as slots_count',
            'slots as assigned_slots_count' => function($query) {
                $query->whereNotNull('item_id');
            }
        ])->get();
        
        return view('admin.rack', compact('racks'));
    }

    public function create()
    {
        return view('admin.add-rack');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rack_name' => 'required|string|max:255|unique:rack',
            'total_slots' => 'required|integer|min:1',
        ]);

        $rack = Rack::create([
            'rack_name' => $request->rack_name,
            'total_slots' => $request->total_slots,
        ]);

        // Tidak perlu record history untuk create operation

        return redirect()->route('admin.rack.index')->with('success', 'Rack created successfully');
    }

    public function edit($id)
    {
        $rack = Rack::findOrFail($id);
        return view('admin.edit-rack', compact('rack'));
    }

    public function update(Request $request, $id)
    {
        $rack = Rack::findOrFail($id);
        
        $request->validate([
            'rack_name' => 'required|string|max:255|unique:rack,rack_name,' . $id,
            'total_slots' => 'required|integer|min:1',
            'notes' => 'required|string|max:500',
        ]);

        $oldRackName = $rack->rack_name;
        $oldTotalSlots = $rack->total_slots;

        $rack->update([
            'rack_name' => $request->rack_name,
            'total_slots' => $request->total_slots,
        ]);

        // Record history untuk rack_name jika berubah
        if ($oldRackName != $rack->rack_name) {
            RackHistory::create([
                'rack_id' => $rack->id,
                'action' => 'update',
                'field_changed' => 'rack_name',
                'old_value' => $oldRackName,
                'new_value' => $rack->rack_name,
                'changed_by' => $this->getCurrentUserId(),
                'name' => 'Rack Name Updated',
                'notes' => $request->notes,
            ]);
        }

        // Record history untuk total_slots jika berubah
        if ($oldTotalSlots != $rack->total_slots) {
            RackHistory::create([
                'rack_id' => $rack->id,
                'action' => 'update',
                'field_changed' => 'total_slots',
                'old_value' => $oldTotalSlots,
                'new_value' => $rack->total_slots,
                'changed_by' => $this->getCurrentUserId(),
                'name' => 'Total Slots Updated',
                'notes' => $request->notes,
            ]);
        }

        return redirect()->route('admin.rack.index')->with('success', 'Rack updated successfully');
    }

    public function destroy($id)
    {
        $rack = Rack::findOrFail($id);
        
        // Record history untuk delete operation
        RackHistory::create([
            'rack_id' => $rack->id,
            'action' => 'delete',
            'field_changed' => 'rack',
            'old_value' => json_encode([
                'rack_name' => $rack->rack_name,
                'total_slots' => $rack->total_slots
            ]),
            'new_value' => null,
            'changed_by' => $this->getCurrentUserId(),
            'name' => 'Rack Deleted',
            'notes' => 'Rack deleted',
        ]);

        $rack->delete();

        return redirect()->route('admin.rack.index')->with('success', 'Rack deleted successfully');
    }

    public function history($id)
    {
        $rack = Rack::findOrFail($id);
        $histories = RackHistory::where('rack_id', $id)->with('changedBy')->orderBy('created_at', 'desc')->get();
        
        return view('admin.rack-history', compact('rack', 'histories'));
    }
}
