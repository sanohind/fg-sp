@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">HOME</h1>
    </div>

    <!-- Rack Summary -->
    <div class="mb-00">
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Rack Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white p-4 rounded-lg shadow-sm border relative">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Total Rack</h3>
                        <div class="text-2xl font-bold text-gray-900">10</div>
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
                        <div class="text-2xl font-bold text-gray-900">9</div>
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
                        <div class="text-2xl font-bold text-gray-900">1</div>
                    </div>
                    <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Slot Summary -->
    <div class="mb-0">
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Slot Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white p-4 rounded-lg shadow-sm border relative">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Total Slot</h3>
                        <div class="text-2xl font-bold text-gray-900">1200</div>
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
                        <div class="text-2xl font-bold text-gray-900">785</div>
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
                        <div class="text-2xl font-bold text-gray-900">415</div>
                    </div>
                    <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- History -->
    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-3">History</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white p-4 rounded-lg shadow-sm border relative">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Stored</h3>
                        <div class="text-2xl font-bold text-gray-900">70</div>
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
                        <h3 class="text-gray-600 text-sm mb-1">Pulled</h3>
                        <div class="text-2xl font-bold text-gray-900">30</div>
                    </div>
                    <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
