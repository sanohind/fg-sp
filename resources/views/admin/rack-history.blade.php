@extends('layouts.app')

@section('title', 'Rack History')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.rack.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-600 transition-colors">
                        ← Back
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Rack History</h1>
                </div>
            </div>
            <div class="mt-4">
                <h2 class="text-xl font-semibold text-gray-700">Rack: {{ $rack->rack_name }}</h2>
                <p class="text-gray-600">Total Slots: {{ $rack->total_slots }}</p>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Change History</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Changes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($histories as $history)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $history->created_at ? $history->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($history->action == 'created')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Created
                                    </span>
                                @elseif($history->action == 'updated')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Updated
                                    </span>
                                @elseif($history->action == 'deleted')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Deleted
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($history->action) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $history->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="space-y-1">
                                    @if($history->old_rack_name !== $history->new_rack_name)
                                        <div>
                                            <span class="font-medium">Rack Name:</span>
                                            <span class="text-red-600">{{ $history->old_rack_name ?? 'N/A' }}</span>
                                            <span class="mx-2">→</span>
                                            <span class="text-green-600">{{ $history->new_rack_name ?? 'N/A' }}</span>
                                        </div>
                                    @endif
                                    @if($history->old_total_slots !== $history->new_total_slots)
                                        <div>
                                            <span class="font-medium">Total Slots:</span>
                                            <span class="text-red-600">{{ $history->old_total_slots ?? 'N/A' }}</span>
                                            <span class="mx-2">→</span>
                                            <span class="text-green-600">{{ $history->new_total_slots ?? 'N/A' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $history->reason ?? 'N/A' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No history records found for this rack
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
