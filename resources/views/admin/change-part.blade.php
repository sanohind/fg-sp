@extends('layouts.app')

@section('title', 'Change Part')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('admin.slot') }}" class="text-[#0A2856] hover:text-[#0A2856]/80">Slots</a>
                    <svg class="fill-current w-3 h-3 mx-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li class="flex items-center">
                    <span class="text-[#0A2856]">Change Part</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Change Part</h1>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.slots.store-change-part', $slot->id) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Rack -->
            <div>
                <label for="rack" class="block text-sm font-medium text-gray-700 mb-1">Rack</label>
                <input type="text" 
                       name="rack" 
                       id="rack" 
                       value="{{ $slot->rack->rack_name ?? '' }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 focus:outline-none text-sm" 
                       readonly>
            </div>

            <!-- Slot Name -->
            <div>
                <label for="slot_name" class="block text-sm font-medium text-gray-700 mb-1">Slot Name</label>
                <input type="text" 
                       name="slot_name" 
                       id="slot_name" 
                       value="{{ $slot->slot_name ?? '' }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 focus:outline-none text-sm" 
                       readonly>
            </div>

            <!-- Capacity -->
            <div>
                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                <input type="number" 
                       name="capacity" 
                       id="capacity" 
                       value="{{ $slot->capacity ?? '' }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 focus:outline-none text-sm" 
                       readonly>
            </div>

            <!-- Current Part -->
            <div>
                <label for="current_part" class="block text-sm font-medium text-gray-700 mb-1">Current Part</label>
                <input type="text" 
                       name="current_part" 
                       id="current_part" 
                       value="{{ $slot->item->part_no ?? 'No part assigned' }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 focus:outline-none text-sm" 
                       readonly>
            </div>

            <!-- New Part No -->
            <div>
                <label for="item_id" class="block text-sm font-medium text-gray-700 mb-1">New Part No</label>
                <select name="item_id" id="item_id" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm appearance-none bg-no-repeat bg-right pr-8 @error('item_id') border-red-500 @enderror" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.4-12.8z%22/%3E%3C/svg%3E');">
                    <option value="">Choose New Part</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>{{ $item->part_no }} - {{ $item->description }}</option>
                    @endforeach
                </select>
                @error('item_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Part Name -->
            <div class="md:col-span-2">
                <label for="part_name" class="block text-sm font-medium text-gray-700 mb-1">New Part Name</label>
                <input type="text" 
                       name="part_name" 
                       id="part_name" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm" 
                       placeholder="Part name will be auto-filled">
            </div>

            <!-- Notes -->
            <div class="md:col-span-2">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea id="reason" 
                          name="reason" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0A2856] focus:border-[#0A2856] transition-all duration-200 text-sm @error('reason') border-red-500 @enderror"
                          placeholder="Please provide a note for this part change" required>{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('admin.slot') }}" 
               class="px-6 py-1.5 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 text-center min-w-[100px]">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-1.5 bg-[#0A2856] text-white rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-all duration-200 text-center min-w-[80px]">
                Change Part
            </button>
        </div>
    </form>
</div>

<script>
// Auto-fill part name when part no is selected
document.getElementById('item_id').addEventListener('change', function() {
    const itemId = this.value;
    const partNameField = document.getElementById('part_name');
    
    // Get part name from items data
    const items = @json($items);
    const selectedItem = items.find(item => item.id == itemId);
    
    partNameField.value = selectedItem ? selectedItem.description : '';
});
</script>
@endsection
