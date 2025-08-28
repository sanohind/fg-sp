@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <nav class="text-sm font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('admin.user.index') }}" class="text-[#0A2856] hover:text-[#0A2856]/80">Users</a>
                    <svg class="fill-current w-3 h-3 mx-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li class="flex items-center">
                    <span class="text-[#0A2856]">User Details</span>
                </li>
            </ol>
        </nav>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
        </div>
    </div>

    <!-- User Information -->
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
    <div class="mt-8 pt-6">
        <div class="flex justify-center space-x-4">
            <a href="{{ route('admin.user.index') }}" 
               class="px-6 py-1.5 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 text-center min-w-[100px]">
                Back to Users
            </a>
            <a href="{{ route('admin.user.edit', $user->id) }}" 
               class="px-6 py-1.5 bg-[#0A2856] text-white rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-all duration-200 text-center min-w-[80px]">
                Edit User
            </a>
        </div>
    </div>
</div>

 
@endsection