@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">HOME</h1>
    </div>

    <!-- Rack Summary -->
    <div class="mb-10">
        <h2 class="text-xl font-semibold text-gray-900 mb-5">Rack Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
                <h3 class="text-gray-600 text-sm uppercase mb-2">Total Rack</h3>
                <div class="text-3xl font-bold text-gray-900">10</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
                <h3 class="text-gray-600 text-sm uppercase mb-2">Filled</h3>
                <div class="text-3xl font-bold text-gray-900">9</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
                <h3 class="text-gray-600 text-sm uppercase mb-2">Empty</h3>
                <div class="text-3xl font-bold text-gray-900">1</div>
            </div>
        </div>
    </div>

    <!-- Slot Summary -->
    <div class="mb-10">
        <h2 class="text-xl font-semibold text-gray-900 mb-5">Slot Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
                <h3 class="text-gray-600 text-sm uppercase mb-2">Total Slot</h3>
                <div class="text-3xl font-bold text-gray-900">1200</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
                <h3 class="text-gray-600 text-sm uppercase mb-2">Filled</h3>
                <div class="text-3xl font-bold text-gray-900">785</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
                <h3 class="text-gray-600 text-sm uppercase mb-2">Empty</h3>
                <div class="text-3xl font-bold text-gray-900">415</div>
            </div>
        </div>
    </div>

    <!-- History -->
    <div>
        <h2 class="text-xl font-semibold text-gray-900 mb-5">History</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
                <h3 class="text-gray-600 text-sm uppercase mb-2">Stored</h3>
                <div class="text-3xl font-bold text-gray-900">70</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center border">
                <h3 class="text-gray-600 text-sm uppercase mb-2">Pulled</h3>
                <div class="text-3xl font-bold text-gray-900">30</div>
            </div>
        </div>
    </div>
</div>
@endsection
