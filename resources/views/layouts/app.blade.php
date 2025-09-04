<!DOCTYPE html>
<html lang="en">
<head>
    @php
        use Illuminate\Support\Facades\DB;
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Pull & Store FG System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('sanoh-favicon.png') }}?v=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('sanoh-favicon.png') }}?v=1">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
    .sanoh-blue {
        background-color: #1e3a8a;
    }

    .sanoh-blue-light {
        background-color: #3b82f6;
    }

    .progress-bar {
        background: linear-gradient(90deg, #fbbf24 0%, #fbbf24 100%);
    }

    /* Fixed header styles */
    .fixed-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 50;
        background: white;
    }

    /* Desktop sidebar styles */
    .fixed-sidebar {
        position: fixed;
        top: 80px;
        /* Height of header */
        left: 0;
        bottom: 0;
        width: 256px;
        /* w-64 = 16rem = 256px */
        z-index: 40;
        overflow-y: auto;
        transition: transform 0.3s ease-in-out;
    }

    /* Mobile sidebar styles */
    @media (max-width: 1023px) {
        .fixed-sidebar {
            transform: translateX(-100%);
            top: 0;
            height: 100vh;
            z-index: 60;
        }

        .fixed-sidebar.show {
            transform: translateX(0);
        }
    }

    /* Desktop content layout */
    .content-with-fixed-layout {
        margin-top: 80px;
        /* Height of header */
        margin-left: 256px;
        /* Width of sidebar */
        min-height: calc(100vh - 80px);
    }

    /* Mobile content layout */
    @media (max-width: 1023px) {
        .content-with-fixed-layout {
            margin-left: 0;
        }
    }

    /* Overlay for mobile */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 55;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease-in-out;
    }

    .sidebar-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    /* Hamburger menu animation */
    .hamburger-line {
        width: 24px;
        height: 2px;
        background-color: #374151;
        transition: all 0.3s ease;
        transform-origin: center;
    }

    .hamburger.active .hamburger-line:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }

    .hamburger.active .hamburger-line:nth-child(2) {
        opacity: 0;
    }

    .hamburger.active .hamburger-line:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -6px);
    }
    
    .sanoh-darkblue {
        background-color: #0A2856 !important;
        color: #fff !important;
    }
    .sanoh-darkblue-text {
        color: #0A2856 !important;
    }
    .sanoh-darkblue-border {
        border-color: #0A2856 !important;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        outline: none;
    }

    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #0A2856;
        box-shadow: 0 0 0 2px rgba(10, 40, 86, 0.1);
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        outline: none;
        margin-left: 0.5rem;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #0A2856;
        box-shadow: 0 0 0 2px rgba(10, 40, 86, 0.1);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #d1d5db;
        background: white;
        color: #374151;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.375rem;
        margin: 0 0.125rem;
        cursor: pointer;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f9fafb;
        color: #111827;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0A2856;
        color: white !important;
        border-color: #0A2856;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #0A2856;
        color: white !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #9ca3af;
        cursor: not-allowed;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: white;
        color: #9ca3af;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 0.875rem;
        color: #374151;
    }

    .dataTables_wrapper .dataTables_processing {
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    /* Individual column search inputs */
    .dataTables_wrapper tfoot input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.125rem 0.5rem;
        font-size: 0.875rem;
        width: 100%;
        outline: none;
    }

    .dataTables_wrapper tfoot input:focus {
        border-color: #0A2856;
        box-shadow: 0 0 0 2px rgba(10, 40, 86, 0.1);
    }

    /* Table styling */
    .dataTables_wrapper .dataTable {
        width: 100%;
        border-collapse: collapse;
    }

    .dataTables_wrapper .dataTable thead th {
        background-color: #0A2856;
        color: white;
        padding: 0.75rem 1.5rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .dataTables_wrapper .dataTable tbody td {
        padding: 0.75rem 1.5rem;
        white-space: nowrap;
        font-size: 0.875rem;
        color: #111827;
        border-bottom: 1px solid #e5e7eb;
    }

    .dataTables_wrapper .dataTable tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Responsive adjustments */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }

    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 1rem;
    }

    /* Layout improvements */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        display: inline-block;
        margin-bottom: 1rem;
    }

    .dataTables_wrapper .dataTables_filter {
        float: right;
    }

    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        display: inline-block;
        margin-top: 1rem;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
    }

    .dataTables_wrapper::after {
        content: "";
        display: table;
        clear: both;
    }

     /* Search row styling */
     .dataTables_wrapper .dataTable thead tr:nth-child(2) th {
            background-color: #ffffff !important;
            color: #374151 !important;
            padding: 0.5rem 1.5rem;
            border-bottom: 1px solid #d1d5db;
        }

        /* Search input styling in header */
        .dataTables_wrapper .dataTable thead input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            /* Ukuran font lebih kecil */
            width: 100%;
            min-width: 0;
            /* Memungkinkan input menyusut lebih kecil */
            outline: none;
            background-color: white;
            box-sizing: border-box;
        }

        .dataTables_wrapper .dataTable thead input:focus {
            border-color: #0A2856;
            box-shadow: 0 0 0 2px rgba(10, 40, 86, 0.1);
        }

        /* Ensure proper spacing and alignment */
        .dataTables_wrapper .dataTable thead th {
            vertical-align: middle;
        }

        /* Remove the old tfoot styling since we're not using it anymore */
        .dataTables_wrapper tfoot {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Fixed Header -->
    <header class="fixed-header bg-white shadow-sm border-b">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <!-- Hamburger Menu Button (Mobile Only) -->
                    <button id="hamburgerBtn"
                        class="lg:hidden flex flex-col space-y-1 p-2 hover:bg-gray-100 rounded-md transition-colors duration-200">
                        <div class="hamburger-line"></div>
                        <div class="hamburger-line"></div>
                        <div class="hamburger-line"></div>
                    </button>

                    <div>
                        <img src="{{ asset('sanoh-logo.png') }}" alt="Sanoh Logo" class="h-10 w-auto"
                            onerror="this.style.display='none'">
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-600" id="realtime-clock">
                        <!-- Waktu akan tampil di sini -->
                    </div>
                    
                    <!-- User Profile Section -->
                    <div class="relative">
                        <div class="flex items-center space-x-3">
                            <div class="flex flex-col items-end">
                                <span class="text-sm font-medium text-gray-900">{{ session('user.name') ?? (auth()->user()->name ?? 'User') }}</span>
                                <span class="text-xs text-gray-500">{{ session('user.role') ? ucfirst(session('user.role')) : (isset(auth()->user()->role) ? ucfirst(auth()->user()->role) : '') }}</span>
                            </div>
                            <button id="userDropdownBtn" class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors cursor-pointer">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Dropdown Menu -->
                        <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50 hidden">
                            <div class="py-1">
                                <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                                    <div class="font-medium">{{ session('user.name') ?? (auth()->user()->name ?? 'User') }}</div>
                                    <div class="text-gray-500">{{ session('user.username') ?? (auth()->user()->username ?? '') }}</div>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3 text-gray-400"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    

                </div>
                <script>
                function updateClock() {
                    const now = new Date();
                    const pad = n => n.toString().padStart(2, '0');
                    const formatted =
                        pad(now.getDate()) + '/' +
                        pad(now.getMonth() + 1) + '/' +
                        now.getFullYear() + ' ' +
                        pad(now.getHours()) + ':' +
                        pad(now.getMinutes()) + ':' +
                        pad(now.getSeconds());
                    document.getElementById('realtime-clock').textContent = formatted;
                }
                updateClock();
                setInterval(updateClock, 1000);
                </script>
            </div>
        </div>
    </header>

    <!-- Sidebar Overlay (Mobile Only) -->
    <div id="sidebarOverlay" class="sidebar-overlay lg:hidden"></div>

    <!-- Fixed Sidebar -->
    <div id="sidebar" class="fixed-sidebar">
        <div class="bg-white shadow h-full">
            <!-- Mobile Header (for close button) -->
            <div class="lg:hidden flex items-center justify-between p-4 border-b">
                <div>
                    <img src="{{ asset('sanoh-logo.png') }}" alt="Sanoh Logo" class="h-8 w-auto"
                        onerror="this.style.display='none'">
                </div>
                <button id="closeSidebarBtn" class="p-2 hover:bg-gray-100 rounded-md transition-colors duration-200">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="space-y-1 p-4">
                <a href="{{ route('admin.home') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-md 
                          {{ request()->routeIs('admin.home') ? 'bg-[#0A2856] text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-home mr-3"></i>
                    HOME
                </a>

                <a href="{{ route('admin.rack.index') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                                                      {{ request()->routeIs('admin.rack.*') ? 'bg-[#0A2856] text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-warehouse mr-3"></i>
                    RACK
                </a>

                <a href="{{ route('admin.slot') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                          {{ request()->routeIs('admin.slot') ? 'bg-[#0A2856] text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-boxes mr-3"></i>
                    SLOTS
                </a>

                <a href="{{ route('admin.item.index') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                            {{ request()->routeIs('admin.item.*') ? 'bg-[#0A2856] text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-box mr-3"></i>
                    ITEMS
                </a>

                <a href="{{ route('admin.history') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                          {{ request()->routeIs('admin.history') ? 'bg-[#0A2856] text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-history mr-3"></i>
                    HISTORY
                </a>

                @php
                    $userRole = null;
                    // Check session first (for session-based auth)
                    if (session('user.role')) {
                        $userRole = strtolower(session('user.role'));
                    }
                    // Fallback to auth() if session doesn't have role
                    elseif (auth()->check()) {
                        $user = auth()->user();
                        if ($user->role) {
                            $userRole = strtolower($user->role->role_name);
                        } else {
                            $userRole = strtolower(DB::table('roles')->where('id', $user->role_id)->value('role_name'));
                        }
                    }
                @endphp
                
                @if($userRole === 'superadmin')
                <a href="{{ route('admin.user.index') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                          {{ request()->routeIs('admin.user.*') ? 'bg-[#0A2856] text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-users mr-3"></i>
                    USER
                </a>
                @endif
            </nav>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="content-with-fixed-layout">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            

            

            <!-- Page Content -->
            <div class="bg-white rounded-lg shadow">
                @yield('content')
            </div>
        </div>
        @include('layouts.footer')
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    {{-- HAPUS LINE INI: <script src="{{ asset('js/toast.js') }}"></script> --}}
    <script>
    // CSRF Token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // HAPUS BAGIAN INI - SESSION MESSAGES (DUPLIKAT)
    {{--
    @if(session('success'))
        var sessionSuccess = '{{ session('success') }}';
    @endif
    
    @if(session('error'))
        var sessionError = '{{ session('error') }}';
    @endif
    
    @if(session('warning'))
        var sessionWarning = '{{ session('warning') }}';
    @endif
    
    @if(session('info'))
        var sessionInfo = '{{ session('info') }}';
    @endif

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    --}}

    // Responsive Sidebar Logic
    $(document).ready(function() {
        const hamburgerBtn = $('#hamburgerBtn');
        const sidebar = $('#sidebar');
        const sidebarOverlay = $('#sidebarOverlay');
        const closeSidebarBtn = $('#closeSidebarBtn');
        
        // User Dropdown Logic
        const userDropdownBtn = $('#userDropdownBtn');
        const userDropdown = $('#userDropdown');
        
        // Toggle user dropdown
        userDropdownBtn.on('click', function(e) {
            e.stopPropagation();
            userDropdown.toggleClass('hidden');
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!userDropdownBtn.is(e.target) && !userDropdown.is(e.target) && userDropdown.has(e.target).length === 0) {
                userDropdown.addClass('hidden');
            }
        });
        
        // Close dropdown on ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                userDropdown.addClass('hidden');
            }
        });

        // Toggle sidebar on hamburger click
        hamburgerBtn.on('click', function() {
            toggleSidebar();
        });

        // Close sidebar on overlay click
        sidebarOverlay.on('click', function() {
            closeSidebar();
        });

        // Close sidebar on close button click
        closeSidebarBtn.on('click', function() {
            closeSidebar();
        });

        // Close sidebar on ESC key press
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

        // Close sidebar when clicking on navigation links (mobile only)
        sidebar.find('nav a').on('click', function() {
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        });

        function toggleSidebar() {
            const isOpen = sidebar.hasClass('show');
            if (isOpen) {
                closeSidebar();
            } else {
                openSidebar();
            }
        }

        function openSidebar() {
            sidebar.addClass('show');
            sidebarOverlay.addClass('show');
            hamburgerBtn.addClass('active');
            $('body').addClass('overflow-hidden lg:overflow-auto');
        }

        function closeSidebar() {
            sidebar.removeClass('show');
            sidebarOverlay.removeClass('show');
            hamburgerBtn.removeClass('active');
            $('body').removeClass('overflow-hidden lg:overflow-auto');
        }

        // Handle window resize
        $(window).on('resize', function() {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    });

    // UNIFIED TOAST NOTIFICATION SYSTEM
    function showToast(message, type = 'success') {
        // Prevent duplicate toasts with same message
        const existingToasts = $('.toast-notification');
        let duplicateFound = false;
        
        existingToasts.each(function() {
            const toastText = $(this).find('p').text();
            if (toastText === message) {
                duplicateFound = true;
                return false; // Break the loop
            }
        });
        
        if (duplicateFound) {
            return; // Don't show duplicate
        }
        
        const toast = $(`
            <div class="toast-notification fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transform transition-all duration-300 ease-out translate-x-full">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            ${type === 'success' ? 
                                '<svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' :
                            type === 'error' ?
                                '<svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' :
                            type === 'warning' ?
                                '<svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>' :
                                '<svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                            }
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900">${message}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `);
        
        $('body').append(toast);
        
        // Animate in
        setTimeout(() => {
            toast.removeClass('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.addClass('translate-x-full');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    }

    // Make showToast globally available
    window.showToast = showToast;

    // Check for session messages - HANYA SATU KALI
    $(document).ready(function() {
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
        
        @if(session('warning'))
            showToast("{{ session('warning') }}", 'warning');
        @endif
        
        @if(session('info'))
            showToast("{{ session('info') }}", 'info');
        @endif
    });
    </script>

    @yield('scripts')
</body>
</html>
