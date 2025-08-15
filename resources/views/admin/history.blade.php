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
        <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
            <h3 class="text-gray-600 text-sm uppercase mb-2">Stored</h3>
            <div class="text-3xl font-bold text-gray-900">70</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
            <h3 class="text-gray-600 text-sm uppercase mb-2">Pulled</h3>
            <div class="text-3xl font-bold text-gray-900">30</div>
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
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">16206-BZ070-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PL250205508080101</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">A11</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Stored
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Joko</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:39:56</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">23901-BZ120-00-KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PL250205508080102</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">C21</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Pulled
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Muhaimin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:38:45</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">77747-BZ060-00-KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PL250205508080103</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">G11</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Stored
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Angga</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:37:30</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">32909-BZ100-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PL250205508080104</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">B15</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Stored
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Joko</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:36:20</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">5</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">11409-BZ080-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PL250205508080105</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">D08</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Pulled
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Muhaimin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:35:15</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">6</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">16206-BZ070-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PL250205508080106</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">E12</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Stored
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Angga</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:34:10</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">7</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">23901-BZ120-00-KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PL250205508080107</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">F03</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Pulled
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Joko</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:33:05</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">8</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">77747-BZ060-00-KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PL250205508080108</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">H19</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Stored
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Muhaimin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:32:00</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">9</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">32909-BZ100-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PL250205508080109</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">I07</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Stored
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Angga</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:30:55</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">11409-BZ080-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PL250205508080110</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">J14</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Pulled
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Joko</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:29:50</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
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
    $('#historyTable').DataTable({
        initComplete: function () {
            this.api()
                .columns()
                .every(function () {
                    let column = this;
                    let title = column.header().textContent;
 
                    // Create input element
                    let input = document.createElement('input');
                    input.placeholder = title;
                    input.className = 'border border-gray-300 rounded-md px-2 py-1 text-sm w-full focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]';
                    column.footer().replaceChildren(input);
 
                    // Event listener for user input
                    input.addEventListener('keyup', () => {
                        if (column.search() !== input.value) {
                            column.search(input.value).draw();
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
