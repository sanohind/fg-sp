@extends('layouts.app')

@section('title', 'Slot Detail')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('admin.slot') }}" class="text-[#0A2856] hover:text-[#0A2856]/80">Slot</a>
                    <svg class="fill-current w-3 h-3 mx-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li class="flex items-center">
                    <span class="text-[#0A2856]">Detail</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Slot Detail</h1>
    </div>

    <!-- Slot Details Section -->
    <div class="bg-white rounded-lg shadow-sm border mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Slot Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Rack -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rack</label>
                    <input type="text" 
                           value="A"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 text-sm" 
                           readonly>
                </div>

                <!-- Slot Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slot Name</label>
                    <input type="text" 
                           value="A11"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 text-sm" 
                           readonly>
                </div>

                <!-- Capacity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                    <input type="number" 
                           value="4"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 text-sm" 
                           readonly>
                </div>

                <!-- Part No -->
<div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Part No</label>
                    <input type="text" 
                           value="16206-BZ070-00-87"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 text-sm" 
                           readonly>
                </div>

                <!-- Part Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Part Name</label>
                    <input type="text" 
                           value="CLAMP, BRAKE TUBE"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 text-sm" 
                           readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Items in Slot</h2>
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table id="itemsTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#0A2856]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Lot No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Qty/box</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actor</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">PL2502055080801015</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Stored
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:39:56</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Muhaimin</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">PL2502055080801016</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Stored
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:39:59</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Muhaimin</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">PL2502055080801017</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Stored
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:40:01</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Muhaimin</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">PL2502055080801018</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Stored
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23/07/2025 15:40:02</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Muhaimin</td>
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
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
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
