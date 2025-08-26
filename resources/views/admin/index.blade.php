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
                        <div class="text-2xl font-bold text-gray-900">{{ $totalRacks }}</div>
                    </div>
                    <a href="{{ route('admin.rack.index') }}" class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center hover:bg-[#0A2856]/90">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border relative">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Filled</h3>
                        <div class="text-2xl font-bold text-gray-900">{{ $assignedSlots }}</div>
                    </div>
                    <a href="{{ route('admin.slot') }}" class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center hover:bg-[#0A2856]/90">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border relative">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Empty</h3>
                        <div class="text-2xl font-bold text-gray-900">{{ $unassignedSlots }}</div>
                    </div>
                    <a href="{{ route('admin.slot') }}" class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center hover:bg-[#0A2856]/90">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
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
                        <div class="text-2xl font-bold text-gray-900">{{ $totalSlots }}</div>
                    </div>
                    <a href="{{ route('admin.slot') }}" class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center hover:bg-[#0A2856]/90">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border relative">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Filled</h3>
                        <div class="text-2xl font-bold text-gray-900">{{ $assignedSlots }}</div>
                    </div>
                    <a href="{{ route('admin.slot') }}" class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center hover:bg-[#0A2856]/90">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border relative">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-600 text-sm mb-1">Empty</h3>
                        <div class="text-2xl font-bold text-gray-900">{{ $unassignedSlots }}</div>
                    </div>
                    <a href="{{ route('admin.slot') }}" class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center hover:bg-[#0A2856]/90">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Recent Activities</h2>
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slot</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lot No</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->created_at->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $log->action === 'store' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->slot_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->part_no }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->lot_no }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No recent activities
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Rack Utilization -->
    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Rack Utilization</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($racks as $rack)
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-lg font-semibold text-gray-900">Rack {{ $rack->rack_name }}</h3>
                    <span class="text-sm text-gray-500">{{ $rack->slots_count }} slots</span>
                </div>
                <div class="mb-2">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Utilization</span>
                        <span>{{ $rack->assigned_slots_count }}/{{ $rack->slots_count }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $percentage = $rack->slots_count > 0 ? ($rack->assigned_slots_count / $rack->slots_count) * 100 : 0;
                        @endphp
                        <div class="bg-[#0A2856] h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    {{ number_format($percentage, 1) }}% filled
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-8">
                No racks available
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
