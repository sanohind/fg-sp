@extends('layouts.app-operator')

@section('title', 'Scan History')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">SCAN HISTORY</h1>
        <a href="{{ route('operator.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-600 transition-colors">
            Kembali
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm border mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="action" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]">
                    <option value="">Semua</option>
                    <option value="store" {{ request('action')==='store' ? 'selected' : '' }}>Store</option>
                    <option value="pull" {{ request('action')==='pull' ? 'selected' : '' }}>Pull</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]" />
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-[#0A2856] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors me-2">
                    Terapkan Filter
                </button>
                <a href="{{ url()->current() }}" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-600 transition-colors">
                    Clear Filter
                </a>
            </div>
            <!-- <div class="flex items-end">
                <a href="{{ url()->current() }}" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-600 transition-colors">
                    Clear Filter
                </a>
            </div> -->
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="historyTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0A2856]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">ERP Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Slot</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
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
                @forelse($logs as $index => $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->erp_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->slot_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $log->action==='store' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ strtoupper($log->action) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
                @empty
                <tr>
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
    $('#historyTable').DataTable({
        orderCellsTop: true, // Menggunakan baris pertama untuk sorting
        fixedHeader: true,
        initComplete: function () {
            // Menambahkan search input ke baris kedua di thead
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
                    // Buat placeholder yang lebih pendek berdasarkan nama kolom
                    let placeholder = '';
                    switch(colIdx) {
                        case 1: placeholder = 'ERP Code'; break;
                        case 2: placeholder = 'Slot'; break;
                        case 3: placeholder = 'Status'; break;
                        case 4: placeholder = 'Date'; break;
                        default: placeholder = title;
                    }
                    input.placeholder = placeholder;
                    input.className = 'border border-gray-300 rounded-md px-2 py-1 text-xs w-full focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856] bg-white min-w-0';

                    // Menambahkan input ke baris kedua (search row) di thead
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
        },
        order: [[4, 'desc']], // Default sort by date descending
        columnDefs: [
            { orderable: false, targets: 0 } // Disable sorting for No. column
        ]
    });
});
</script>
@endsection