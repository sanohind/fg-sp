@extends('layouts.app')

@section('title', 'Add Rack')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('admin.rack') }}" class="text-gray-500 hover:text-gray-700">Rack</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                </li>
                <li class="flex items-center">
                    <span class="text-gray-700">Add Rack</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Add Rack</h1>
    </div>

    <!-- Form -->
    <form action="#" method="POST">
        @csrf
        <div class="space-y-6">
            <!-- Rack Name -->
            <div>
                <label for="rack_name" class="block text-sm font-medium text-gray-700 mb-1">Rack Name</label>
                <input type="text" name="rack_name" id="rack_name" class="shadow-sm focus:ring-[#0A2856] focus:border-[#0A2856] block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter rack name">
            </div>

            <!-- Total Slots -->
            <div>
                <label for="total_slots" class="block text-sm font-medium text-gray-700 mb-1">Total Slots</label>
                <input type="number" name="total_slots" id="total_slots" class="shadow-sm focus:ring-[#0A2856] focus:border-[#0A2856] block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter total slots">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.rack') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-400 transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-[#0A2856] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
