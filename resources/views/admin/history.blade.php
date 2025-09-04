@extends('layouts.app')

@section('title', 'History')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">HISTORY</h1>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Stored</h3>
                    <div class="text-2xl font-bold text-gray-900">{{ $storedCount }}</div>
                </div>
                <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Pulled</h3>
                    <div class="text-2xl font-bold text-gray-900">{{ $pulledCount }}</div>
                </div>
                <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center space-x-2">
                <label for="dateFrom" class="text-sm font-medium text-gray-700">From:</label>
                <input type="date" id="dateFrom" 
                       class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]">
            </div>
            <div class="flex items-center space-x-2">
                <label for="dateTo" class="text-sm font-medium text-gray-700">To:</label>
                <input type="date" id="dateTo" 
                       class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]">
            </div>
            <button id="applyDateFilter" 
                    class="px-4 py-2 bg-[#0A2856] text-white rounded-md hover:bg-[#0A2856]/90 text-sm font-medium">
                Apply Filter
            </button>
            <button id="clearDateFilter" 
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 text-sm font-medium">
                Clear Filter
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="historyTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0A2856]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Part No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Lot No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Slot Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                </tr>
                <!-- Search Row -->
                <tr class="bg-gray-100">
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($logs as $index => $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->part_no ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->lot_no ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->slot_name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($log->action == 'store')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Stored
                            </span>
                        @elseif($log->action == 'pull')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Pulled
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst($log->action) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-date="{{ $log->created_at ? $log->created_at->format('Y-m-d') : '' }}">
                        {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">No history records found</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>

let historyDataTable;
let customDateFilter = null; // Track custom filter

$(document).ready(function() {
    // Initialize DataTable
    historyDataTable = $('#historyTable').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        initComplete: function () {
            this.api()
                .columns()
                .every(function (colIdx) {
                    let column = this;
                    let title = column.header().textContent;
                    
                    // Skip kolom No. (kolom 0)
                    if (colIdx === 0) {
                        return;
                    }
                    
                    let input = document.createElement('input');
                    let placeholder = '';
                    switch(colIdx) {
                        case 1: placeholder = 'Part No'; break;
                        case 2: placeholder = 'Lot No'; break;
                        case 3: placeholder = 'Slot Name'; break;
                        case 4: placeholder = 'Status'; break;
                        case 5: placeholder = 'Actor'; break;
                        case 6: placeholder = 'Date'; break;
                        default: placeholder = title;
                    }
                    input.placeholder = placeholder;
                    input.className = 'border border-gray-300 rounded-md px-2 py-1 text-xs w-full focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856] bg-white min-w-0';

                    $(input).appendTo($(column.header()).parent().next().find('th').eq(colIdx))
                        .on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                });
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries per page",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // Date range filter functionality
    $('#applyDateFilter').on('click', function() {
        const dateFrom = $('#dateFrom').val();
        const dateTo = $('#dateTo').val();
        
        if (!dateFrom && !dateTo) {
            alert('Please select at least one date');
            return;
        }
        
        // Remove existing custom filter first
        if (customDateFilter !== null) {
            const index = $.fn.dataTable.ext.search.indexOf(customDateFilter);
            if (index > -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
        }
        
        // Create new custom filter
        customDateFilter = function(settings, data, dataIndex) {
            // Only apply to our specific table
            if (settings.nTable.id !== 'historyTable') {
                return true;
            }
            
            // Get the row element to access data-date attribute
            const row = settings.aoData[dataIndex].nTr;
            const dateCell = row.querySelector('td[data-date]');
            
            if (!dateCell) {
                return true; // Show rows without date data
            }
            
            const rowDateStr = dateCell.getAttribute('data-date');
            
            if (!rowDateStr || rowDateStr === '') {
                return true; // Show rows without dates
            }
            
            // Use the data-date attribute which is already in Y-m-d format
            // This avoids timezone issues from parsing displayed date
            const rowDate = new Date(rowDateStr + 'T00:00:00');
            
            // Apply date range filter
            if (dateFrom) {
                const fromDate = new Date(dateFrom + 'T00:00:00');
                if (rowDate < fromDate) {
                    return false;
                }
            }
            
            if (dateTo) {
                const toDate = new Date(dateTo + 'T23:59:59');
                if (rowDate > toDate) {
                    return false;
                }
            }
            
            return true;
        };
        
        // Add the new filter
        $.fn.dataTable.ext.search.push(customDateFilter);
        
        // Redraw table
        historyDataTable.draw();
    });

    // Clear date filter
    $('#clearDateFilter').on('click', function() {
        // Clear input values
        $('#dateFrom').val('');
        $('#dateTo').val('');
        
        // Remove custom filter if it exists
        if (customDateFilter !== null) {
            const index = $.fn.dataTable.ext.search.indexOf(customDateFilter);
            if (index > -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            customDateFilter = null;
        }
        
        // Redraw table
        historyDataTable.draw();
    });
});
</script>
@endsection