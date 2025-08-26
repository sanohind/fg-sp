<!DOCTYPE html>
<html lang="en">

<head>
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
    </style>
</head>

<body class="bg-gray-50">
    <!-- Fixed Header -->
    <header class="fixed-header bg-white shadow-sm border-b">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <!-- Hamburger Menu Button (Mobile Only) -->


                    <div>
                        <img src="{{ asset('sanoh-logo.png') }}" alt="Sanoh Logo" class="h-10 w-auto"
                            onerror="this.style.display='none'">
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    

                    <!-- User Profile Section -->
                    <div class="relative">
                        <div class="flex items-center space-x-3">
                            <div class="flex flex-col items-end">
                                <span
                                    class="text-sm font-medium text-gray-900">{{ session('user.name') ?? (auth()->user()->name ?? 'User') }}</span>
                                <span class="text-xs text-gray-500">{{ session('user.role') ? ucfirst(session('user.role')) : (isset(auth()->user()->role) ? ucfirst(auth()->user()->role) : '') }}</span>
                            </div>
                            <button id="userDropdownBtn"
                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors cursor-pointer">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Dropdown Menu -->
                        <div id="userDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50 hidden">
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

    <!-- Main Content Area -->
    <div class="content-with-fixed-layout">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <!-- Page Content -->
            <div class="bg-white rounded-lg shadow">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Global Toast Notifications -->
    <!-- Success Notification Toast -->
    <div id="successToast"
        class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50"
        style="display: none;">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <div>
                <h4 class="font-semibold" id="toastTitle">Success!</h4>
                <p class="text-sm opacity-90" id="toastMessage">Operation completed successfully.</p>
            </div>
        </div>
    </div>

    <!-- Error Notification Toast -->
    <div id="errorToast"
        class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50"
        style="display: none;">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                <div>
                    <h4 class="font-semibold">Error!</h4>
                    <p class="text-sm opacity-90" id="errorToastMessage">An error occurred.</p>
                </div>
            </div>
            <button onclick="closeErrorToast()"
                class="ml-4 text-white hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
    // CSRF Token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

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
            if (!userDropdownBtn.is(e.target) && !userDropdown.is(e.target) && userDropdown.has(e
                    .target).length === 0) {
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

    // Global Toast Notification Functions
    function showSuccessToast(title, message) {
        const toast = document.getElementById('successToast');
        if (toast) {
            const titleEl = toast.querySelector('#toastTitle');
            const messageEl = toast.querySelector('#toastMessage');
            if (titleEl) titleEl.textContent = title;
            if (messageEl) messageEl.textContent = message;

            // Show and animate
            toast.style.display = 'block';
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 10);

            // Auto hide after 3 seconds
            setTimeout(() => {
                hideSuccessToast();
            }, 3000);
        }
    }

    function hideSuccessToast() {
        const toast = document.getElementById('successToast');
        if (toast) {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');

            // Hide completely after animation
            setTimeout(() => {
                toast.style.display = 'none';
            }, 300);
        }
    }

    function showErrorToast(title, message) {
        const toast = document.getElementById('errorToast');

        if (toast) {
            const messageEl = toast.querySelector('#errorToastMessage');
            if (messageEl) messageEl.textContent = message;

            // Show and animate
            toast.style.display = 'block';
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 10);

            // Auto hide after 6 seconds
            setTimeout(() => {
                hideErrorToast();
            }, 6000);
        }
    }

    function hideErrorToast() {
        const toast = document.getElementById('errorToast');

        if (toast) {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');

            // Hide completely after animation
            setTimeout(() => {
                toast.style.display = 'none';
            }, 300);
        }
    }

    function closeErrorToast() {
        hideErrorToast();
    }
    </script>

    @yield('scripts')
</body>

</html>