@extends('layouts.app-operator')

@section('title', 'Posting F/G')

@section('content')
<body class="bg-gray-50 p-4 pt-10">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-4 mt-14">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Posting F/G</h1>

        <!-- Status Display -->
        <div id="status-display" class="mb-4 p-4 rounded-md hidden">
            <div id="status-content"></div>
        </div>

        <!-- Slot Information Display -->
        <div id="slot-info" class="mb-4 p-4 bg-blue-50 rounded-md hidden">
            <h3 class="font-semibold text-blue-800 mb-2">Slot Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium">Slot Name:</span>
                    <span id="slot-name"></span>
                </div>
                <div>
                    <span class="font-medium">Rack Name:</span>
                    <span id="rack-name"></span>
                </div>
                <div>
                    <span class="font-medium">Capacity:</span>
                    <span id="slot-capacity"></span>
                </div>
                <div>
                    <span class="font-medium">Status:</span>
                    <span id="slot-status"></span>
                </div>
            </div>
        </div>

        <!-- Top row with Part No, Rack, and Available fields -->
        <div class="grid grid-cols-4 gap-4 mb-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Part No.</label>
                <input type="text" id="part-no-display"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-100"
                    readonly>
            </div>
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rack</label>
                <input type="text" id="rack-display"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-100"
                    readonly>
            </div>
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Available</label>
                <input type="text" id="available-display"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-100"
                    readonly>
            </div>
        </div>

        <!-- Large center section for Rack Address -->
        <div id="scan-area"
            class="flex items-center justify-center min-h-64 bg-white rounded-md border-2 border-dashed border-gray-300 mb-4 cursor-pointer hover:border-blue-400 transition-colors">
            <div class="text-center">
                <h2 class="text-gray-500 mb-2">Scan Slot QR Code</h2>
                <p class="text-sm text-gray-400">Click here or scan to select slot</p>
            </div>
        </div>

        <!-- Box Information Display -->
        <div id="box-info" class="mb-4 p-4 bg-green-50 rounded-md hidden">
            <h3 class="font-semibold text-green-800 mb-2">Box Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium">Part No:</span>
                    <span id="box-part-no"></span>
                </div>
                <div>
                    <span class="font-medium">ERP Code:</span>
                    <span id="box-erp-code"></span>
                </div>
                <div>
                    <span class="font-medium">Lot No:</span>
                    <span id="box-lot-no"></span>
                </div>
                <div>
                    <span class="font-medium">Status:</span>
                    <span id="box-status"></span>
                </div>
            </div>
        </div>

        <!-- Bottom Part No field for box scanning -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Scan Box QR Code</label>
            <input type="text" id="box-scan-input"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Scan box QR code or enter manually">
        </div>

        <!-- Action Buttons -->
        <div class="text-center space-x-4">
            <button id="scan-slot-btn"
                class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 px-8 rounded-md transition duration-200 ease-in-out transform hover:scale-105 shadow-lg">
                <span class="inline-flex items-center">
                    <img src="{{ asset('barcode.png') }}" alt="Scan Slot" class="w-5 h-5 mr-2">
                    Scan Slot
                </span>
            </button>
            <button id="scan-box-btn" disabled
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-md transition duration-200 ease-in-out transform hover:scale-105 shadow-lg disabled:bg-gray-400 disabled:cursor-not-allowed">
                <span class="inline-flex items-center">
                    <img src="{{ asset('barcode.png') }}" alt="Scan Box" class="w-5 h-5 mr-2">
                    Scan Box
                </span>
            </button>
        </div>
    </div>

    <!-- Hidden form for AJAX requests -->
    <form id="slot-scan-form" class="hidden">
        @csrf
        <input type="hidden" id="slot-name-input" name="slot_name">
    </form>

    <form id="box-scan-form" class="hidden">
        @csrf
        <input type="hidden" id="box-slot-name" name="slot_name">
        <input type="hidden" id="box-part-no-input" name="part_no">
        <input type="hidden" id="box-erp-code-input" name="erp_code">
        <input type="hidden" id="box-lot-no-input" name="lot_no">
    </form>
</body>

<script>
let currentSlot = null;

// Scan Slot functionality
document.getElementById('scan-slot-btn').addEventListener('click', function() {
    // Simulate QR code scanning - in real implementation, this would be handled by QR scanner
    const slotName = prompt('Enter slot name (or scan QR code):');
    if (slotName) {
        scanSlot(slotName);
    }
});

document.getElementById('scan-area').addEventListener('click', function() {
    const slotName = prompt('Enter slot name (or scan QR code):');
    if (slotName) {
        scanSlot(slotName);
    }
});

function scanSlot(slotName) {
    const form = document.getElementById('slot-scan-form');
    document.getElementById('slot-name-input').value = slotName;
    
    fetch('{{ route("operator.posting.scan-slot") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            slot_name: slotName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            showStatus(data.error, 'error');
            hideSlotInfo();
        } else {
            currentSlot = data.slot;
            showSlotInfo(data.slot);
            showStatus('Slot scanned successfully!', 'success');
            document.getElementById('scan-box-btn').disabled = false;
            document.getElementById('box-scan-input').focus();
        }
    })
    .catch(error => {
        showStatus('Error scanning slot: ' + error.message, 'error');
    });
}

// Scan Box functionality
document.getElementById('box-scan-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const boxData = this.value;
        // Parse box data (assuming format: part_no|erp_code|lot_no)
        const parts = boxData.split('|');
        if (parts.length >= 3) {
            scanBox(parts[0], parts[1], parts[2]);
        } else {
            showStatus('Invalid box QR code format', 'error');
        }
    }
});

document.getElementById('scan-box-btn').addEventListener('click', function() {
    const boxData = prompt('Enter box data (part_no|erp_code|lot_no) or scan QR code:');
    if (boxData) {
        const parts = boxData.split('|');
        if (parts.length >= 3) {
            scanBox(parts[0], parts[1], parts[2]);
        } else {
            showStatus('Invalid box QR code format', 'error');
        }
    }
});

function scanBox(partNo, erpCode, lotNo) {
    if (!currentSlot) {
        showStatus('Please scan slot first', 'error');
        return;
    }

    fetch('{{ route("operator.posting.scan-box") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            slot_name: currentSlot.slot_name,
            part_no: partNo,
            erp_code: erpCode,
            lot_no: lotNo
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            showStatus(data.error, 'error');
        } else {
            showBoxInfo(partNo, erpCode, lotNo, 'Success');
            showStatus(data.message, 'success');
            document.getElementById('box-scan-input').value = '';
            
            // Update display fields
            document.getElementById('part-no-display').value = partNo;
            document.getElementById('rack-display').value = currentSlot.rack.rack_name;
            document.getElementById('available-display').value = data.current_qty + '/' + currentSlot.capacity;
            
            if (data.is_full) {
                showStatus('Slot is now full!', 'warning');
            }
        }
    })
    .catch(error => {
        showStatus('Error scanning box: ' + error.message, 'error');
    });
}

function showStatus(message, type) {
    const statusDisplay = document.getElementById('status-display');
    const statusContent = document.getElementById('status-content');
    
    statusDisplay.className = `mb-4 p-4 rounded-md`;
    statusContent.textContent = message;
    
    switch(type) {
        case 'success':
            statusDisplay.classList.add('bg-green-100', 'text-green-800');
            break;
        case 'error':
            statusDisplay.classList.add('bg-red-100', 'text-red-800');
            break;
        case 'warning':
            statusDisplay.classList.add('bg-yellow-100', 'text-yellow-800');
            break;
    }
    
    statusDisplay.classList.remove('hidden');
    
    setTimeout(() => {
        statusDisplay.classList.add('hidden');
    }, 5000);
}

function showSlotInfo(slot) {
    const slotInfo = document.getElementById('slot-info');
    document.getElementById('slot-name').textContent = slot.slot_name;
    document.getElementById('rack-name').textContent = slot.rack.rack_name;
    document.getElementById('slot-capacity').textContent = slot.capacity;
    document.getElementById('slot-status').textContent = slot.item_id ? 'Occupied' : 'Empty';
    
    slotInfo.classList.remove('hidden');
}

function hideSlotInfo() {
    document.getElementById('slot-info').classList.add('hidden');
}

function showBoxInfo(partNo, erpCode, lotNo, status) {
    const boxInfo = document.getElementById('box-info');
    document.getElementById('box-part-no').textContent = partNo;
    document.getElementById('box-erp-code').textContent = erpCode;
    document.getElementById('box-lot-no').textContent = lotNo;
    document.getElementById('box-status').textContent = status;
    
    boxInfo.classList.remove('hidden');
}
</script>
@endsection