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
        <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
            <h3 class="text-gray-600 text-sm uppercase mb-2">Total Rack</h3>
            <div class="text-3xl font-bold text-gray-900">10</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
            <h3 class="text-gray-600 text-sm uppercase mb-2">Filled</h3>
            <div class="text-3xl font-bold text-gray-900">9</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
            <h3 class="text-gray-600 text-sm uppercase mb-2">Empty</h3>
            <div class="text-3xl font-bold text-gray-900">1</div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-5">
        <a href="{{ route('admin.rack.add') }}" class="bg-[#0A2856] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors">
            Add Rack
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
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">A</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">120</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">120</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                Hapus
                            </button>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">B</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">150</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">120</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                Hapus
                            </button>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">C</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">120</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">117</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                Hapus
                            </button>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">D</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">150</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">146</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                Hapus
                            </button>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">5</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">E</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">120</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">120</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                Hapus
                            </button>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">6</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">F</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">100</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">30</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">70</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                Hapus
                            </button>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">7</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">G</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">100</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">6</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">94</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                Hapus
                            </button>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">8</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">H</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">140</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">9</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">131</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                Hapus
                            </button>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">9</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">I</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">150</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">40</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">110</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                Hapus
                            </button>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">J</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">100</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">100</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                Hapus
                            </button>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
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
    $('#rackTable').DataTable({
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
