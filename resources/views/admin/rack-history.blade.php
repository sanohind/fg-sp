@extends('layouts.app')

@section('title', 'Rack History')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('admin.rack.index') }}" class="text-[#0A2856] hover:text-[#0A2856]/80">Racks</a>
                    <svg class="fill-current w-3 h-3 mx-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li class="flex items-center">
                    <span class="text-[#0A2856]">Rack History</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Rack History</h1>
    </div>

    <!-- Rack Info Card -->
    <div class="bg-white p-4 rounded-lg shadow-sm border mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Rack: {{ $rack->rack_name }}</h2>
        <p class="text-gray-600">Total Slots: {{ $rack->total_slots }}</p>
    </div>

    <!-- History Table -->
    <div class="overflow-x-auto">
        <table id="rackHistoryTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0A2856]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Changes</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Reason</th>
                </tr>
                <!-- Search Row -->
                <tr class="bg-gray-100">
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                    <th class="px-6 py-2"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($histories as $history)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $history->created_at ? $history->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($history->action == 'created')
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Created
                        </span>
                        @elseif($history->action == 'updated')
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            Updated
                        </span>
                        @elseif($history->action == 'deleted')
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Deleted
                        </span>
                        @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                            {{ ucfirst($history->action) }}
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $history->user->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="space-y-1">
                            @if($history->old_rack_name !== $history->new_rack_name)
                            <div>
                                <span class="font-medium">Rack Name:</span>
                                <span class="text-red-600">{{ $history->old_rack_name ?? 'N/A' }}</span>
                                <span class="mx-2">→</span>
                                <span class="text-green-600">{{ $history->new_rack_name ?? 'N/A' }}</span>
                            </div>
                            @endif
                            @if($history->old_total_slots !== $history->new_total_slots)
                            <div>
                                <span class="font-medium">Total Slots:</span>
                                <span class="text-red-600">{{ $history->old_total_slots ?? 'N/A' }}</span>
                                <span class="mx-2">→</span>
                                <span class="text-green-600">{{ $history->new_total_slots ?? 'N/A' }}</span>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $history->reason ?? 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">No history records found</td>
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
    $('#rackHistoryTable').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        initComplete: function () {
            this.api()
                .columns()
                .every(function (colIdx) {
                    let column = this;
                    
                    let input = document.createElement('input');
                    let placeholder = '';
                    switch(colIdx) {
                        case 0: placeholder = 'Date'; break;
                        case 1: placeholder = 'Action'; break;
                        case 2: placeholder = 'User'; break;
                        case 3: placeholder = 'Changes'; break;
                        case 4: placeholder = 'Reason'; break;
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