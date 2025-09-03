<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemHistory;
use App\Imports\ItemsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


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
            'model' => 'nullable|string|max:255',
            'customer' => 'nullable|string|max:255',
            'qty' => 'required|integer|min:0',
            'part_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'packaging_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'erp_code.required' => 'ERP Code harus diisi.',
            'erp_code.unique' => 'ERP Code sudah ada dalam sistem.',
            'part_no.required' => 'Part No harus diisi.',
            'description.required' => 'Description harus diisi.',
            'model.max' => 'Model maksimal 255 karakter.',
            'customer.max' => 'Customer maksimal 255 karakter.',
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
            'model' => 'nullable|string|max:255',
            'customer' => 'nullable|string|max:255',
            'qty' => 'required|integer|min:0',
            'part_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'packaging_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'required|string|max:500',
        ], [
            'erp_code.required' => 'ERP Code harus diisi.',
            'erp_code.unique' => 'ERP Code sudah ada dalam sistem.',
            'part_no.required' => 'Part No harus diisi.',
            'description.required' => 'Description harus diisi.',
            'model.max' => 'Model maksimal 255 karakter.',
            'customer.max' => 'Customer maksimal 255 karakter.',
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

            return redirect()->route('admin.item.index')->with('success', 'Item updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update item. Please try again.');
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

            return redirect()->route('admin.item.index')->with('success', 'Item deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.item.index')->with('error', 'Failed to delete item. Please try again.');
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



    /**
     * Handle Excel upload and import
     */
    public function uploadExcel(Request $request)
    {
        \Log::info('Upload Excel method called', [
            'request_method' => $request->method(),
            'has_file' => $request->hasFile('excel_file'),
            'all_data' => $request->all(),
            'files' => $request->allFiles(),
            'content_type' => $request->header('Content-Type'),
            'content_length' => $request->header('Content-Length'),
            'user_agent' => $request->header('User-Agent')
        ]);

        if (!$request->hasFile('excel_file')) {
            \Log::error('No file uploaded', [
                'files' => $request->allFiles(),
                'all_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);
            // Clear any existing session messages to prevent duplication
            session()->forget(['success', 'error', 'import_errors']);
            return redirect()->route('admin.item.index')->with('error', 'No file uploaded. Please select an Excel file.');
        }

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // Max 10MB
        ], [
            'excel_file.required' => 'File Excel harus dipilih',
            'excel_file.file' => 'File yang diupload bukan file yang valid',
            'excel_file.mimes' => 'File harus berformat Excel (.xlsx atau .xls)',
            'excel_file.max' => 'Ukuran file maksimal 10MB',
        ]);

        \Log::info('Validation passed, starting import process');

        try {
            $file = $request->file('excel_file');
            
            \Log::info('Starting Excel import', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            // Import Excel file
            $import = new ItemsImport();
            Excel::import($import, $file);

            // Get import statistics
            $stats = $import->getStatistics();

            \Log::info('Excel import completed', $stats);

            if ($stats['success_count'] > 0) {
                $message = "Berhasil mengimport {$stats['success_count']} item";
                if ($stats['error_count'] > 0) {
                    $message .= " dengan {$stats['error_count']} error";
                }
                \Log::info('Redirecting with success message', ['message' => $message]);
                // Clear any existing session messages to prevent duplication
                session()->forget(['success', 'error', 'import_errors']);
                return redirect()->route('admin.item.index')->with('success', $message);
            } else {
                $errorMessage = 'No data was successfully imported. ';
                if ($stats['error_count'] > 0) {
                    $errorMessage .= 'Check the following error: ' . implode(', ', array_slice($stats['errors'], 0, 3));
                    if (count($stats['errors']) > 3) {
                        $errorMessage .= ' and ' . (count($stats['errors']) - 3) . ' other errors';
                    }
                } else {
                    $errorMessage .= 'Check the Excel format and ensure data starts from row 9.';
                }
                \Log::info('Redirecting with error message', ['message' => $errorMessage]);
                // Clear any existing session messages to prevent duplication
                session()->forget(['success', 'error', 'import_errors']);
                return redirect()->route('admin.item.index')->with('error', $errorMessage);
            }

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            \Log::error('Excel validation failed', [
                'errors' => $e->failures(),
                'file' => $request->file('excel_file')->getClientOriginalName()
            ]);
            
            $errorMessage = 'Invalid Excel file: ';
            foreach ($e->failures() as $failure) {
                $errorMessage .= implode(', ', $failure->errors()) . ' ';
            }
            
            // Clear any existing session messages to prevent duplication
            session()->forget(['success', 'error', 'import_errors']);
            return redirect()->route('admin.item.index')->with('error', trim($errorMessage));
            
        } catch (\Exception $e) {
            \Log::error('Excel import failed', [
                'error' => $e->getMessage(),
                'file' => $request->file('excel_file')->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);

            // Clear any existing session messages to prevent duplication
            session()->forget(['success', 'error', 'import_errors']);
            return redirect()->route('admin.item.index')->with('error', 'Failed to import Excel file: ' . $e->getMessage());
        }
    }

    /**
 * Debug method untuk test upload
 */
public function debugUpload(Request $request)
{
    // Log semua informasi PHP dan server
    \Log::info('=== DEBUG UPLOAD START ===');
    
    // PHP Configuration
    $phpConfig = [
        'file_uploads' => ini_get('file_uploads') ? 'ON' : 'OFF',
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'max_file_uploads' => ini_get('max_file_uploads'),
        'max_execution_time' => ini_get('max_execution_time'),
        'memory_limit' => ini_get('memory_limit'),
        'upload_tmp_dir' => ini_get('upload_tmp_dir') ?: 'default',
    ];
    
    \Log::info('PHP Configuration:', $phpConfig);
    
    // Request information
    $requestInfo = [
        'method' => $request->method(),
        'content_type' => $request->header('Content-Type'),
        'content_length' => $request->header('Content-Length'),
        'has_file_laravel' => $request->hasFile('excel_file'),
        'all_data' => $request->all(),
        'files_laravel' => $request->allFiles(),
    ];
    
    \Log::info('Request Info:', $requestInfo);
    
    // $_FILES global
    \Log::info('$_FILES Global:', $_FILES);
    
    // $_POST global
    \Log::info('$_POST Global:', $_POST);
    
    // Server information
    $serverInfo = [
        'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? 'not_set',
        'CONTENT_TYPE' => $_SERVER['CONTENT_TYPE'] ?? 'not_set',
        'CONTENT_LENGTH' => $_SERVER['CONTENT_LENGTH'] ?? 'not_set',
        'HTTP_CONTENT_LENGTH' => $_SERVER['HTTP_CONTENT_LENGTH'] ?? 'not_set',
    ];
    
    \Log::info('Server Info:', $serverInfo);
    
    // Check tmp directory
    $tmpDir = sys_get_temp_dir();
    $tmpDirWritable = is_writable($tmpDir);
    
    \Log::info('Temp Directory:', [
        'path' => $tmpDir,
        'writable' => $tmpDirWritable,
        'exists' => is_dir($tmpDir)
    ]);
    
    \Log::info('=== DEBUG UPLOAD END ===');
    
    // Return debug response
    return response()->json([
        'success' => true,
        'message' => 'Debug complete, check logs',
        'php_config' => $phpConfig,
        'request_info' => $requestInfo,
        'files_global' => $_FILES,
        'post_global' => $_POST,
        'server_info' => $serverInfo,
        'tmp_dir' => [
            'path' => $tmpDir,
            'writable' => $tmpDirWritable,
            'exists' => is_dir($tmpDir)
        ]
    ]);
}


}
