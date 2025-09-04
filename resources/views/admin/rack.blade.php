@extends('layouts.app')

@section('title', 'Rack')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">RACK</h1>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Total Rack</h3>
                    <div class="text-2xl font-bold text-gray-900">{{ $racks->count() }}</div>
                </div>
                <a href="{{ route('admin.rack.index') }}" class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center hover:bg-[#0A2856]/90">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                    </svg>
                </a>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Total Slots</h3>
                    <div class="text-2xl font-bold text-gray-900">{{ $racks->sum('total_slots') }}</div>
                </div>
                <a href="{{ route('admin.slot') }}" class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center hover:bg-[#0A2856]/90">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Assigned Slots</h3>
                    <div class="text-2xl font-bold text-gray-900">{{ $racks->sum('assigned_slots_count') }}</div>
                </div>
                <a href="{{ route('admin.slot') }}" class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center hover:bg-[#0A2856]/90">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-5">
        <a href="{{ route('admin.rack.create') }}" class="bg-[#0A2856] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors">
            Add Rack
        </a>
        <a href="{{ route('admin.rack.history.all') }}" class="bg-[#0A2856] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors">
            Change Log
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="rackTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0A2856]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Rack</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total Slots</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Available Slots</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Occupied Slots</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
                </tr>
                <!-- Search Row -->
                <tr class="bg-gray-100">
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($racks as $index => $rack)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $rack->rack_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $rack->total_slots }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $rack->total_slots - $rack->assigned_slots_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $rack->assigned_slots_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.rack.history', $rack->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 min-w-[50px] text-center">
                                History
                            </a>
                            <a href="{{ route('admin.rack.edit', $rack->id) }}" class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px] text-center">
                                Edit
                            </a>
                            @if($rack->assigned_slots_count == 0)
                                <form action="{{ route('admin.rack.destroy', $rack->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this rack?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
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
$(document).ready(function() {
    $('#rackTable').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        initComplete: function () {
            this.api()
                .columns()
                .every(function (colIdx) {
                    let column = this;
                    
                    // Skip kolom No. (kolom 0)
                    if (colIdx === 0 || colIdx === 5) {
                        return;
                    }
                    
                    let input = document.createElement('input');
                    let placeholder = '';
                    switch(colIdx) {
                        case 1: placeholder = 'Rack'; break;
                        case 2: placeholder = 'Total Slots'; break;
                        case 3: placeholder = 'Available Slots'; break;
                        case 4: placeholder = 'Occupied Slots'; break;
                        default: placeholder = 'Search...';
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
});
</script>
@endsection