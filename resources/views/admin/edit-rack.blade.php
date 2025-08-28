@extends('layouts.app')

@section('title', 'Edit Rack')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('admin.rack.index') }}" class="text-[#0A2856] hover:text-[#0A2856]/80">Racks</a>
                    <svg class="fill-current w-3 h-3 mx-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.30c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li class="flex items-center">
                    <span class="text-[#0A2856]">Edit Rack</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Edit Rack</h1>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.rack.update', $rack->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Rack Name -->
            <div>
                <label for="rack_name" class="block text-sm font-medium text-gray-700 mb-1">Rack Name</label>
                <input type="text" 
                       id="rack_name" 
                       name="rack_name" 
                       value="{{ old('rack_name', $rack->rack_name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm @error('rack_name') border-red-500 @enderror"
                       placeholder="Enter rack name">
                @error('rack_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Total Slots -->
            <div>
                <label for="total_slots" class="block text-sm font-medium text-gray-700 mb-1">Total Slots</label>
                <input type="number" 
                       id="total_slots" 
                       name="total_slots" 
                       value="{{ old('total_slots', $rack->total_slots) }}"
                       min="1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm @error('total_slots') border-red-500 @enderror"
                       placeholder="Enter total slots">
                @error('total_slots')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Reason for Change -->
        <div class="mt-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Reason for Change</label>
            <textarea id="notes" 
                      name="notes" 
                      rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm @error('notes') border-red-500 @enderror"
                      placeholder="Please provide a reason for this change">{{ old('notes') }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('admin.rack.index') }}" 
               class="px-6 py-1.5 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 text-center min-w-[100px]">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-1.5 bg-[#0A2856] text-white rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-all duration-200 text-center min-w-[80px]">
                Update Rack
            </button>
        </div>
    </form>
</div>

 
@endsection