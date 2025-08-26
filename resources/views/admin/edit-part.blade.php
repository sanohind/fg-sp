@extends('layouts.app')

@section('title', 'Edit Part')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.items') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-600 transition-colors">
                        ← Back
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Part</h1>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.item.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ERP Code -->
                    <div>
                        <label for="erp_code" class="block text-sm font-medium text-gray-700 mb-2">ERP Code *</label>
                        <input type="text" name="erp_code" id="erp_code" value="{{ old('erp_code', $item->erp_code) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]" required>
                        @error('erp_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Part No -->
                    <div>
                        <label for="part_no" class="block text-sm font-medium text-gray-700 mb-2">Part No *</label>
                        <input type="text" name="part_no" id="part_no" value="{{ old('part_no', $item->part_no) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]" required>
                        @error('part_no')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" id="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]" required>{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Model *</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $item->model) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]" required>
                        @error('model')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Customer -->
                    <div>
                        <label for="customer" class="block text-sm font-medium text-gray-700 mb-2">Customer *</label>
                        <input type="text" name="customer" id="customer" value="{{ old('customer', $item->customer) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]" required>
                        @error('customer')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="qty" class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                        <input type="number" name="qty" id="qty" value="{{ old('qty', $item->qty) }}" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]" required>
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
                        <input type="file" name="part_image" id="part_image" accept="image/*" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]">
                        <p class="text-sm text-gray-500 mt-1">Leave blank to keep current image</p>
                        @error('part_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Packaging Image -->
                    <div>
                        <label for="packaging_image" class="block text-sm font-medium text-gray-700 mb-2">New Packaging Image</label>
                        <input type="file" name="packaging_image" id="packaging_image" accept="image/*" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]">
                        <p class="text-sm text-gray-500 mt-1">Leave blank to keep current image</p>
                        @error('packaging_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.items') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-[#0A2856] text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors">
                        Update Part
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div id="success-alert" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('success') }}
</div>
<script>
    setTimeout(function() {
        document.getElementById('success-alert').style.display = 'none';
    }, 3000);
</script>
@endif

@if(session('error'))
<div id="error-alert" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('error') }}
</div>
<script>
    setTimeout(function() {
        document.getElementById('error-alert').style.display = 'none';
    }, 3000);
</script>
@endif
@endsection
