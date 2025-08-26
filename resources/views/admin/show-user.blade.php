@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.user.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-600 transition-colors">
                        ← Back
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.user.edit', $user->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                        Edit User
                    </a>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Username</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->username }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->role->role_name ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">User ID</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->id }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.user.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-gray-600 transition-colors">
                        Back to Users
                    </a>
                    <a href="{{ route('admin.user.edit', $user->id) }}" class="bg-[#0A2856] text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors">
                        Edit User
                    </a>
                </div>
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
