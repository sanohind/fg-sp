@extends('layouts.app')

@section('title', 'Add Item')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('admin.items') }}" class="text-[#0A2856] hover:text-[#0A2856]/80">Items</a>
                    <svg class="fill-current w-3 h-3 mx-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li class="flex items-center">
                    <span class="text-[#0A2856]">Add Item</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Add Item</h1>
    </div>

    <!-- Form -->
    <form action="#" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-6">
            <!-- Row 1: ERP Code, Part No, Model (3 columns) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- ERP Code -->
                <div>
                    <label for="erp_code" class="block text-sm font-medium text-gray-700 mb-1">ERP Code</label>
                    <input type="text" 
                           name="erp_code" 
                           id="erp_code" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm" 
                           placeholder="Enter ERP code">
                </div>

                <!-- Part No -->
                <div>
                    <label for="part_no" class="block text-sm font-medium text-gray-700 mb-1">Part No</label>
                    <input type="text" 
                           name="part_no" 
                           id="part_no" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm" 
                           placeholder="Enter part number">
                </div>

                <!-- Model -->
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                    <input type="text" 
                           name="model" 
                           id="model" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm" 
                           placeholder="Enter model">
                </div>
            </div>

            <!-- Row 2: Description, Quantity (2 columns) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" 
                           name="description" 
                           id="description" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm" 
                           placeholder="Enter description">
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number" 
                           name="quantity" 
                           id="quantity" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm" 
                           placeholder="Enter quantity">
                </div>
            </div>

<!-- Row 3: Customer, Package Image, Part Image (3 columns) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Customer -->
    <div>
        <label for="customer" class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
        <input type="text" 
               name="customer" 
               id="customer" 
               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm h-[38px]" 
               placeholder="Enter customer name">
    </div>

    <!-- Package Image -->
    <div>
        <label for="package_image" class="block text-sm font-medium text-gray-700 mb-1">Package Image</label>
        <div class="flex space-x-2">
            <input type="file" 
                   name="package_image" 
                   id="package_image" 
                   accept="image/*"
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm h-[38px] file:mr-4 file:py-0 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#0A2856] file:text-white hover:file:bg-[#0A2856]/90 file:h-6">
        </div>
    </div>

    <!-- Part Image -->
    <div>
        <label for="part_image" class="block text-sm font-medium text-gray-700 mb-1">Part Image</label>
        <div class="flex space-x-2">
            <input type="file" 
                   name="part_image" 
                   id="part_image" 
                   accept="image/*"
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm h-[38px] file:mr-4 file:py-0 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#0A2856] file:text-white hover:file:bg-[#0A2856]/90 file:h-6">
        </div>
    </div>
</div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('admin.items') }}" 
               class="px-6 py-1.5 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 text-center min-w-[100px]">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-1.5 bg-[#0A2856] text-white rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-all duration-200 text-center min-w-[80px]">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
