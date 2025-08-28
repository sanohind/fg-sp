@extends('layouts.app')

@section('title', 'Slot History All')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('admin.slot') }}" class="text-[#0A2856] hover:text-[#0A2856]/80">Slots</a>
                    <svg class="fill-current w-3 h-3 mx-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li class="flex items-center">
                    <span class="text-[#0A2856]">All Slot History</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">All Slot History</h1>
    </div>

    <!-- History Table -->
    <div class="overflow-x-auto">
        <table id="slotHistoryAllTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0A2856]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Slot</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Changes</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Notes</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($histories as $history)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $history->created_at ? $history->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($history->action == 'update')
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            Updated
                        </span>
                        @elseif($history->action == 'delete')
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
                        {{ $history->changedBy->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($history->slot)
                            <div>
                                <div class="font-medium">{{ $history->slot->slot_name }}</div>
                                <div class="text-gray-500">Capacity: {{ $history->slot->capacity }}</div>
                                @if($history->slot->rack)
                                    <div class="text-gray-500">Rack: {{ $history->slot->rack->rack_name }}</div>
                                @endif
                            </div>
                        @else
                            <span class="text-gray-400">Slot Deleted</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="space-y-1">
                            @if($history->action == 'delete')
                                <div>
                                    <span class="font-medium">Slot Deleted:</span>
                                    @php
                                        $deletedData = json_decode($history->old_value, true);
                                    @endphp
                                    @if($deletedData)
                                        <div class="mt-1 text-xs">
                                            <div><span class="font-medium">Slot Name:</span> {{ $deletedData['slot_name'] ?? 'N/A' }}</div>
                                            <div><span class="font-medium">Capacity:</span> {{ $deletedData['capacity'] ?? 'N/A' }}</div>
                                            <div><span class="font-medium">Rack ID:</span> {{ $deletedData['rack_id'] ?? 'N/A' }}</div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div>
                                    <span class="font-medium">{{ ucfirst($history->field_changed) }}:</span>
                                    <span class="text-red-600">{{ $history->old_value ?? 'N/A' }}</span>
                                    <span class="mx-2">→</span>
                                    <span class="text-green-600">{{ $history->new_value ?? 'N/A' }}</span>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $history->notes ?? 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">No history records found</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

 
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#slotHistoryAllTable').DataTable({
            initComplete: function() {
                this.api()
                    .columns()
                    .every(function() {
                        let column = this;
                        let title = column.header().textContent;

                        // Create input element
                        let input = document.createElement('input');
                        input.placeholder = title;
                        input.className = 'border border-gray-300 rounded-md px-2 py-1 text-sm w-full focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]';

                        // Check if footer exists and has content
                        if (column.footer() && column.footer().textContent !== undefined) {
                            column.footer().replaceChildren(input);
                        }

                        // Event listener for user input
                        input.addEventListener('keyup', () => {
                            if (column.search() !== input.value) {
                                column.search(input.value).draw();
                            }
                        });
                    });
            },
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
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
