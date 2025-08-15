@extends('layouts.app')

@section('title', 'Slots')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">SLOTS</h1>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Total Slots</h3>
                    <div class="text-2xl font-bold text-gray-900">1320</div>
                </div>
                <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Filled</h3>
                    <div class="text-2xl font-bold text-gray-900">750</div>
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
                    <h3 class="text-gray-600 text-sm mb-1">Empty</h3>
                    <div class="text-2xl font-bold text-gray-900">570</div>
                </div>
                <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-5">
        <a href="{{ route('admin.slot.add') }}" class="bg-[#0A2856] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors">
            Add Slot
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="slotTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0A2856]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Slot</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Part No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Part Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Capacity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Occupied</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider w-48">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">A11</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">16206-BZ070-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">CLAMP, BRAKE TUBE</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.slot.detail') }}" class="bg-[#0A2856] text-white px-3 py-1 rounded text-xs hover:bg-[#0A2856]/90 min-w-[100px] inline-block text-center">
                                View Detail
                            </a>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">A12</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">32909-BZ100-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">FUEL TUBE</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.slot.detail') }}" class="bg-[#0A2856] text-white px-3 py-1 rounded text-xs hover:bg-[#0A2856]/90 min-w-[100px] inline-block text-center">
                                View Detail
                            </a>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">A13</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">11409-BZ080-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GUIDE SUB-ASSY</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.slot.assign-part') }}" class="bg-cyan-600 text-white px-3 py-1 rounded text-xs hover:bg-amber-700 min-w-[100px] inline-block text-center">
                                Change Part
                            </a>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">A14</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23901-BZ120-00-KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">FUEL TANK BREATHER</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.slot.detail') }}" class="bg-[#0A2856] text-white px-3 py-1 rounded text-xs hover:bg-[#0A2856]/90 min-w-[100px] inline-block text-center">
                                View Detail
                            </a>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">5</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">A15</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">77747-BZ060-00-KZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">BRAKE PIPE</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.slot.detail') }}" class="bg-[#0A2856] text-white px-3 py-1 rounded text-xs hover:bg-[#0A2856]/90 min-w-[100px] inline-block text-center">
                                View Detail
                            </a>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">6</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">A16</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">16206-BZ070-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">CLAMP, BRAKE TUBE</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.slot.detail') }}" class="bg-[#0A2856] text-white px-3 py-1 rounded text-xs hover:bg-[#0A2856]/90 min-w-[100px] inline-block text-center">
                                View Detail
                            </a>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">7</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">A17</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">11409-BZ080-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GUIDE SUB-ASSY</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.slot.assign-part') }}" class="bg-slate-600 text-white px-3 py-1 rounded text-xs hover:bg-slate-700 min-w-[100px] inline-block text-center">
                                Change Part
                            </a>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">8</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">A18</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">11409-BZ080-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GUIDE SUB-ASSY</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.slot.assign-part') }}" class="bg-slate-600 text-white px-3 py-1 rounded text-xs hover:bg-slate-700 min-w-[100px] inline-block text-center">
                                Change Part
                            </a>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">9</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">A19</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">32909-BZ100-00-87</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">FUEL TUBE</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.slot.detail') }}" class="bg-[#0A2856] text-white px-3 py-1 rounded text-xs hover:bg-[#0A2856]/90 min-w-[100px] inline-block text-center">
                                View Detail
                            </a>
                            <button class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px]">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">A20</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.slot.assign-part') }}" class="bg-indigo-600 text-white px-3 py-1 rounded text-xs hover:bg-indigo-700 min-w-[100px] inline-block text-center">
                                Assign Part
                            </a>
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
    $('#slotTable').DataTable({
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