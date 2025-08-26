<?php

namespace App\Http\Controllers;

use App\Models\LogStorePull;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = LogStorePull::with(['user', 'slot']);

        // Filter by date range if provided
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by action type if provided
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by slot if provided
        if ($request->filled('slot_name')) {
            $query->where('slot_name', 'LIKE', "%{$request->slot_name}%");
        }

        // Filter by part_no if provided
        if ($request->filled('part_no')) {
            $query->where('part_no', 'LIKE', "%{$request->part_no}%");
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.history.index', compact('logs'));
    }

    public function show($id)
    {
        $log = LogStorePull::with(['user', 'slot'])->findOrFail($id);
        return view('admin.history.show', compact('log'));
    }

    public function export(Request $request)
    {
        $query = LogStorePull::with(['user', 'slot']);

        // Apply same filters as index
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('slot_name')) {
            $query->where('slot_name', 'LIKE', "%{$request->slot_name}%");
        }

        if ($request->filled('part_no')) {
            $query->where('part_no', 'LIKE', "%{$request->part_no}%");
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'history_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'ERP Code',
                'Part No',
                'Slot Name',
                'Rack Name',
                'Lot No',
                'Action',
                'User',
                'Quantity',
                'Created At'
            ]);

            // CSV data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->erp_code,
                    $log->part_no,
                    $log->slot_name,
                    $log->rack_name,
                    $log->lot_no,
                    $log->action,
                    $log->name,
                    $log->qty,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
