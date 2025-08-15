@extends('layouts.app')

@section('title', 'Items')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">ITEMS</h1>
    </div>

    <!-- Total Items Card -->
    <div class="max-w-sm mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
            <h3 class="text-gray-600 text-sm uppercase mb-2">Total Items</h3>
            <div class="text-3xl font-bold text-gray-900">70</div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-5">
        <a href="#" class="bg-[#0A2856] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors">
            Add Item
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="itemsTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0A2856]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">ERP Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Part No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RL2IN016206BZ0700087</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">16206-BZ070-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">CLAMP, BRAKE TUBE</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADM</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RL3IN032909BZ1000087</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">32909-BZ100-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">FUEL TUBE</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADM</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RL1IN011409BZ0800087</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">11409-BZ080-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GUIDE SUB-ASSY</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADM</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RL2IN023901BZ12000KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">23901-BZ120-00-KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">FUEL TANK BREATHER</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADM</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RL1IN077747BZ06000KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">77747-BZ060-00-KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">BRAKE PIPE</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADM</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RL2IN016206BZ0700087</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">16206-BZ070-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">CLAMP, BRAKE TUBE</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADM</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RL3IN032909BZ1000087</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">32909-BZ100-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">FUEL TUBE</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADM</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RL1IN011409BZ0800087</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">11409-BZ080-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GUIDE SUB-ASSY</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADM</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RL2IN023901BZ12000KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">23901-BZ120-00-KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">FUEL TANK BREATHER</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADM</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RL1IN077222BZ03000KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">77222-BZ030-00-KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">FUEL TANK BREATHER, NO.2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADM</td>
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
    $('#itemsTable').DataTable({
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
