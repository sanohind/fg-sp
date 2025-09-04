@extends('layouts.app-operator')

@section('title', 'Scan History')

@section('content')
<div class="p-4 pt-10 sm:p-6">
    <div class="flex items-center justify-between mb-4 mt-16">
        <h1 class="text-xl font-semibold text-gray-900">Scan History</h1>
        <a href="{{ route('operator.index') }}" class="px-4 py-2 rounded-md border text-sm hover:bg-gray-50">Kembali</a>
    </div>

    <!-- Filters -->
    <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
            <label class="block text-xs text-gray-600 mb-1">Status</label>
            <select name="action" class="w-full border rounded-md px-3 py-2 text-sm">
                <option value="">Semua</option>
                <option value="store" {{ request('action')==='store' ? 'selected' : '' }}>Store</option>
                <option value="pull" {{ request('action')==='pull' ? 'selected' : '' }}>Pull</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border rounded-md px-3 py-2 text-sm" />
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border rounded-md px-3 py-2 text-sm" />
        </div>
        <div class="flex items-end">
            <button class="sanoh-darkblue px-4 py-2 rounded-md text-sm">Terapkan</button>
        </div>
    </form>

    <div class="overflow-x-auto bg-white border rounded-lg">
        <table id="historyTable" class="min-w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-medium text-white bg-[#0A2856] px-4 py-2">ERP Code</th>
                    <th class="text-left text-xs font-medium text-white bg-[#0A2856] px-4 py-2">Slot</th>
                    <th class="text-left text-xs font-medium text-white bg-[#0A2856] px-4 py-2">Status</th>
                    <th class="text-left text-xs font-medium text-white bg-[#0A2856] px-4 py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="border-b">
                    <td class="px-4 py-2 text-sm">{{ $log->erp_code }}</td>
                    <td class="px-4 py-2 text-sm">{{ $log->slot_name }}</td>
                    <td class="px-4 py-2 text-sm">
                        <span class="px-2 py-1 rounded text-xs {{ $log->action==='store' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ strtoupper($log->action) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-sm">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada history.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->appends(request()->query())->links() }}
    </div>
</div>



