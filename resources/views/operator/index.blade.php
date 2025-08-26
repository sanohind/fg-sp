@extends('layouts.app-operator')

@section('title', 'Menu Operator')

@section('content')
<!-- Main Content -->
<div class="py-16">
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 sm:gap-6 max-w-4xl mx-auto">
            <!-- Juudl halaman -->
            <h1 class="text-2xl font-bold text-gray-900">Menu Operator</h1>
            <!-- Card: Posting F/G -->
            <a href="{{ route('operator.posting') }}"
                class="block bg-white rounded-lg shadow border p-4 sm:px-8 sm:py-8 hover:shadow-md transition group">
                <div class="flex flex-row items-center justify-between">
                    <div class="text-lg sm:text-xl font-semibold text-[#0A2856]">Store F/G</div>
                    <img src="{{ asset('image_5.png') }}" alt="Posting FG"
                        class="h-16 sm:h-20 w-auto select-none pointer-events-none">
                </div>
            </a>

            <!-- Card: Pulling -->
            <a href="pulling"
                class="block bg-white rounded-lg shadow border p-6 sm:px-8 sm:py-8 hover:shadow-md transition group">
                <div class="flex flex-row items-center justify-between">
                    <div class="text-lg sm:text-xl font-semibold text-[#0A2856]">Pulling F/G</div>
                    <img src="{{ asset('image_4.png') }}" alt="Pulling"
                        class="h-16 sm:h-20 w-auto select-none pointer-events-none">
                </div>
            </a>

        </div>
    </div>
</div>