@extends('layouts.app')

@section('title', 'User')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">USER</h1>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Total User</h3>
                    <div class="text-2xl font-bold text-gray-900">70</div>
                </div>
                <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Super Admin</h3>
                    <div class="text-2xl font-bold text-gray-900">1</div>
                </div>
                <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Admin</h3>
                    <div class="text-2xl font-bold text-gray-900">3</div>
                </div>
                <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Operator</h3>
                    <div class="text-2xl font-bold text-gray-900">66</div>
                </div>
                <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-5">
        <a href="{{ route('admin.user.add') }}" class="bg-[#0A2856] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors">
            Add User
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="userTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0A2856]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">superduper</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Super admin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Malika Andini</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">admin1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jefri Nichol</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">muhaimin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Operator</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Muhaimin Iskandar</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">joko</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Operator</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Joko Susilo</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">suparman</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Suparman</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">jack</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Operator</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jaki Darmanto</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">jono</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Operator</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Sujono</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">heru</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Operator</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Heru Budianto</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">admin2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Budi Santoso</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">operator1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Operator</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Siti Nurhaliza</td>
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
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#userTable').DataTable({
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
