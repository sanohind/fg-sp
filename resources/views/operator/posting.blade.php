@extends('layouts.app-operator')

@section('title', 'Menu Operator')

@section('content')

<body class="bg-gray-50 p-4 pt-10">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-4 mt-14">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Posting F/G</h1>

        <!-- Top row with Part No, Rack, and Available fields -->
        <div class="grid grid-cols-4 gap-4 mb-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Part No.</label>
                <input type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rack</label>
                <input type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Available</label>
                <input type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <!-- Large center section for Rack Address -->
        <div
            class="flex items-center justify-center min-h-64 bg-white rounded-md border-2 border-dashed border-gray-300 mb-4">
            <h2>
                <span class="text-gray-500">Scan Rack Address</span>

            </h2>
        </div>

        <!-- Bottom Part No field -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Part No.</label>
            <input type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <!-- Scan Rack button -->
        <div class="text-center">
            <button
                class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 px-16 rounded-md transition duration-200 ease-in-out transform hover:scale-105 shadow-lg">
                <span class="inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12V9m4.01 3h.01M5 12V9h.01m0 0V9h.01M12 4h.01M12 4V1m0 15.01V19m0 0h4.01M12 19H8m0-9.01V12">
                        </path>
                    </svg>
                    Scan Rack
                </span>
            </button>
        </div>
    </div>
</body>