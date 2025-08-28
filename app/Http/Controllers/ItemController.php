<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    private function getCurrentUserId()
    {
        return session('user.id');
    }

    public function index()
    {
        $items = Item::all();
        
        // Calculate total items
        $totalItems = $items->count();
        
        // Get items with slot assignment info
        $itemsWithSlotInfo = $items->map(function($item) {
            $item->is_assigned = \App\Models\Slot::where('item_id', $item->id)->exists();
            return $item;
        });
        
        return view('admin.items', compact('itemsWithSlotInfo', 'totalItems'));
    }

    public function create()
    {
        return view('admin.add-part');
    }

    public function store(Request $request)
    {
        $request->validate([
            'erp_code' => 'required|string|max:255|unique:items',
            'part_no' => 'required|string|max:255',
            'description' => 'required|string',
            'model' => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
            'part_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'packaging_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'erp_code.required' => 'ERP Code harus diisi.',
            'erp_code.unique' => 'ERP Code sudah ada dalam sistem.',
            'part_no.required' => 'Part No harus diisi.',
            'description.required' => 'Description harus diisi.',
            'model.required' => 'Model harus diisi.',
            'customer.required' => 'Customer harus diisi.',
            'qty.required' => 'Quantity harus diisi.',
            'qty.integer' => 'Quantity harus berupa angka.',
            'qty.min' => 'Quantity minimal 0.',
            'part_image.image' => 'Part Image harus berupa gambar.',
            'part_image.mimes' => 'Part Image harus berformat jpeg, png, jpg, atau gif.',
            'part_image.max' => 'Part Image maksimal 2MB.',
            'packaging_image.image' => 'Packaging Image harus berupa gambar.',
            'packaging_image.mimes' => 'Packaging Image harus berformat jpeg, png, jpg, atau gif.',
            'packaging_image.max' => 'Packaging Image maksimal 2MB.',
        ]);

        try {
            $data = [
                'erp_code' => $request->erp_code,
                'part_no' => $request->part_no,
                'description' => $request->description,
                'model' => $request->model,
                'customer' => $request->customer,
                'qty' => $request->qty,
            ];

            // Handle part image upload
            if ($request->hasFile('part_image')) {
                $partImagePath = $request->file('part_image')->store('items/part_images', 'public');
                $data['part_img'] = $partImagePath;
            }

            // Handle packaging image upload
            if ($request->hasFile('packaging_image')) {
                $packagingImagePath = $request->file('packaging_image')->store('items/packaging_images', 'public');
                $data['packaging_img'] = $packagingImagePath;
            }

            $item = Item::create($data);

            // Tidak perlu record history untuk create operation

            return redirect()->route('admin.item.index')->with('success', 'Item berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan item. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('admin.edit-part', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        
        $request->validate([
            'erp_code' => 'required|string|max:255|unique:items,erp_code,' . $id,
            'part_no' => 'required|string|max:255',
            'description' => 'required|string',
            'model' => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
            'part_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'packaging_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'required|string|max:500',
        ], [
            'erp_code.required' => 'ERP Code harus diisi.',
            'erp_code.unique' => 'ERP Code sudah ada dalam sistem.',
            'part_no.required' => 'Part No harus diisi.',
            'description.required' => 'Description harus diisi.',
            'model.required' => 'Model harus diisi.',
            'customer.required' => 'Customer harus diisi.',
            'qty.required' => 'Quantity harus diisi.',
            'qty.integer' => 'Quantity harus berupa angka.',
            'qty.min' => 'Quantity minimal 0.',
            'part_image.image' => 'Part Image harus berupa gambar.',
            'part_image.mimes' => 'Part Image harus berformat jpeg, png, jpg, atau gif.',
            'part_image.max' => 'Part Image maksimal 2MB.',
            'packaging_image.image' => 'Packaging Image harus berupa gambar.',
            'packaging_image.mimes' => 'Packaging Image harus berformat jpeg, png, jpg, atau gif.',
            'packaging_image.max' => 'Packaging Image maksimal 2MB.',
            'notes.required' => 'Notes harus diisi.',
        ]);

        try {
            $oldData = [
                'erp_code' => $item->erp_code,
                'part_no' => $item->part_no,
                'description' => $item->description,
                'model' => $item->model,
                'customer' => $item->customer,
                'qty' => $item->qty,
            ];

            $data = [
                'erp_code' => $request->erp_code,
                'part_no' => $request->part_no,
                'description' => $request->description,
                'model' => $request->model,
                'customer' => $request->customer,
                'qty' => $request->qty,
            ];

            // Handle part image upload
            if ($request->hasFile('part_image')) {
                // Delete old image if exists
                if ($item->part_img) {
                    Storage::disk('public')->delete($item->part_img);
                }
                $partImagePath = $request->file('part_image')->store('items/part_images', 'public');
                $data['part_img'] = $partImagePath;
            }

            // Handle packaging image upload
            if ($request->hasFile('packaging_image')) {
                // Delete old image if exists
                if ($item->packaging_img) {
                    Storage::disk('public')->delete($item->packaging_img);
                }
                $packagingImagePath = $request->file('packaging_image')->store('items/packaging_images', 'public');
                $data['packaging_img'] = $packagingImagePath;
            }

            $item->update($data);

            // Record history untuk setiap field yang berubah
            $changedFields = [];
            foreach ($oldData as $field => $oldValue) {
                if ($oldValue != $data[$field]) {
                    $changedFields[] = $field;
                    ItemHistory::create([
                        'item_id' => $item->id,
                        'action' => 'update',
                        'field_changed' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $data[$field],
                        'changed_by' => $this->getCurrentUserId(),
                        'name' => ucfirst($field) . ' Updated',
                        'notes' => $request->notes,
                    ]);
                }
            }

            return redirect()->route('admin.item.index')->with('success', 'Item berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui item. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);
            
            // Record history untuk delete operation
            ItemHistory::create([
                'item_id' => $item->id,
                'action' => 'delete',
                'field_changed' => 'item',
                'old_value' => json_encode([
                    'erp_code' => $item->erp_code,
                    'part_no' => $item->part_no,
                    'description' => $item->description,
                    'model' => $item->model,
                    'customer' => $item->customer,
                    'qty' => $item->qty
                ]),
                'new_value' => null,
                'changed_by' => $this->getCurrentUserId(),
                'name' => 'Item Deleted',
                'notes' => 'Item deleted',
            ]);

            // Delete images if they exist
            if ($item->part_img) {
                Storage::disk('public')->delete($item->part_img);
            }
            if ($item->packaging_img) {
                Storage::disk('public')->delete($item->packaging_img);
            }

            $item->delete();

            return redirect()->route('admin.item.index')->with('success', 'Item berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.item.index')->with('error', 'Gagal menghapus item. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        return view('admin.item.show', compact('item'));
    }

    public function history($id)
    {
        $item = Item::findOrFail($id);
        $histories = ItemHistory::where('item_id', $id)->with('changedBy')->orderBy('created_at', 'desc')->get();
        
        return view('admin.item-history', compact('item', 'histories'));
    }
}
