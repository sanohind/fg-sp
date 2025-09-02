@extends('layouts.app')

@section('title', 'Edit Part')

@section('content')
<div class="p-6">
    <div>
        <!-- Header -->
        <div class="mb-8">
            <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="{{ route('admin.item.index') }}" class="text-[#0A2856] hover:text-[#0A2856]/80">Items</a>
                        <svg class="fill-current w-3 h-3 mx-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                        </svg>
                    </li>
                    <li class="flex items-center">
                        <span class="text-[#0A2856]">Edit Part</span>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Edit Part</h1>
        </div>

        <!-- Form -->
        <div>
            <form action="{{ route('admin.item.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ERP Code -->
                    <div>
                        <label for="erp_code" class="block text-sm font-medium text-gray-700 mb-2">ERP Code *</label>
                        <input type="text" name="erp_code" id="erp_code" value="{{ old('erp_code', $item->erp_code) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm" required>
                        @error('erp_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Part No -->
                    <div>
                        <label for="part_no" class="block text-sm font-medium text-gray-700 mb-2">Part No *</label>
                        <input type="text" name="part_no" id="part_no" value="{{ old('part_no', $item->part_no) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm" required>
                        @error('part_no')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm" required>{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Model <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        <input type="text" name="model" id="model" value="{{ old('model', $item->model) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm" placeholder="Enter model (optional)">
                        @error('model')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Customer -->
                    <div>
                        <label for="customer" class="block text-sm font-medium text-gray-700 mb-2">Customer <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        <input type="text" name="customer" id="customer" value="{{ old('customer', $item->customer) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm h-[38px]" placeholder="Enter customer name (optional)">
                        @error('customer')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="qty" class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                        <input type="number" name="qty" id="qty" value="{{ old('qty', $item->qty) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm" required>
                        @error('qty')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Images -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Current Images</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($item->part_img)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Part Image</label>
                                <img src="{{ asset('storage/' . $item->part_img) }}" alt="Part Image" class="w-32 h-32 object-cover rounded-md border">
                            </div>
                            @endif
                            @if($item->packaging_img)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Packaging Image</label>
                                <img src="{{ asset('storage/' . $item->packaging_img) }}" alt="Packaging Image" class="w-32 h-32 object-cover rounded-md border">
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Part Image -->
                    <div>
                        <label for="part_image" class="block text-sm font-medium text-gray-700 mb-2">New Part Image</label>
                        <input type="file" name="part_image" id="part_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm h-[38px] file:mr-4 file:py-0 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#0A2856] file:text-white hover:file:bg-[#0A2856]/90 file:h-6">
                        <p class="text-sm text-gray-500 mt-1">Leave blank to keep current image</p>
                        @error('part_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Packaging Image -->
                    <div>
                        <label for="packaging_image" class="block text-sm font-medium text-gray-700 mb-2">New Packaging Image</label>
                        <input type="file" name="packaging_image" id="packaging_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm h-[38px] file:mr-4 file:py-0 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#0A2856] file:text-white hover:file:bg-[#0A2856]/90 file:h-6">
                        <p class="text-sm text-gray-500 mt-1">Leave blank to keep current image</p>
                        @error('packaging_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes (for history) -->
                <div class="mt-6 md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm @error('notes') border-red-500 @enderror"
                              placeholder="Please provide a note for this update" required>{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-center space-x-4">
                    <a href="{{ route('admin.item.index') }}" 
                       class="px-6 py-1.5 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 text-center min-w-[100px]">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-1.5 bg-[#0A2856] text-white rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-all duration-200 text-center min-w-[80px]">
                        Update Part
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

 
@endsection
