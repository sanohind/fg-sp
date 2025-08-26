@extends('layouts.app')

@section('title', 'Add Rack')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">ADD RACK</h1>
                    <a href="{{ route('admin.rack.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-600 transition-colors">
            Back to Racks
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form action="{{ route('admin.rack.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Rack Name -->
                <div>
                    <label for="rack_name" class="block text-sm font-medium text-gray-700 mb-2">Rack Name</label>
                    <input type="text" 
                           id="rack_name" 
                           name="rack_name" 
                           value="{{ old('rack_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-transparent @error('rack_name') border-red-500 @enderror"
                           placeholder="Enter rack name"
                           required>
                    @error('rack_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Slots -->
                <div>
                    <label for="total_slots" class="block text-sm font-medium text-gray-700 mb-2">Total Slots</label>
                    <input type="number" 
                           id="total_slots" 
                           name="total_slots" 
                           value="{{ old('total_slots') }}"
                           min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-transparent @error('total_slots') border-red-500 @enderror"
                           placeholder="Enter total slots"
                           required>
                    @error('total_slots')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-[#0A2856] text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors">
                    Create Rack
                </button>
            </div>
        </form>
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
