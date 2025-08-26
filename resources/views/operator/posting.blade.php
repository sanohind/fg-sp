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
        <!-- <div id="slot-info" class="mb-4 p-4 bg-blue-50 rounded-md hidden">
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
        </div> -->

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

        <!-- Image preview area (shows packaging image after slot scan, part image after ERP scan) -->
        <div id="image-preview"
            class="flex flex-col items-center justify-center min-h-64 bg-white rounded-md border-2 border-dashed border-gray-300 mb-4 r transition-colors">
            <div class="text-center">
                <img id="item-image" src="" alt="Item preview" class="max-h-64 object-contain mx-auto hidden">
                <h2 id="image-placeholder" class="text-gray-500 mb-2">Image Preview</h2>
                <p id="image-caption" class="text-xs text-gray-500 mt-2 hidden"></p>
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
            <input type="text" id="box-scan-input" autofocus
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Scan: rack_name lalu ERP code">
            <p class="text-xs text-gray-500 mt-1">1. Scan Slot; 2. Scan ERP Code</p>
        </div>

        <!-- Action Buttons -->
        <div class="text-center space-x-4">
            <button id="scan-slot-btn"
                class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 px-8 rounded-md transition duration-200 ease-in-out transform hover:scale-105 shadow-lg">
                <span class="inline-flex items-center">
                    <img src="{{ asset('barcode.png') }}" alt="Scan Slot" class="w-5 h-5 mr-2">
                    Scan 
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
let scannedSlot = null; // moved earlier so handlers can reference

// Scan Slot functionality
document.getElementById('scan-slot-btn').addEventListener('click', function() {
    // If slot not scanned yet, read slot_name from input field
    if (!scannedSlot) {
        const raw = document.getElementById('box-scan-input').value.trim();
        if (!raw) {
            showStatus('Scan/ketik slot_name pada field, lalu tekan Scan', 'warning');
            document.getElementById('box-scan-input').focus();
            return;
        }
        scanSlotName(raw);
        return;
    }
    // Slot already scanned -> treat button as ERP scan trigger using input value
    const erp = document.getElementById('box-scan-input').value.trim();
    if (!erp) {
        showStatus('Masukkan/scan ERP code', 'warning');
        document.getElementById('box-scan-input').focus();
        return;
    }
    scanErp(erp);
});

document.getElementById('image-preview').addEventListener('click', function() {
    // No prompt; focus input for scan
    document.getElementById('box-scan-input').focus();
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
            var scanBtn = document.getElementById('scan-box-btn');
            if (scanBtn) scanBtn.disabled = false;
            document.getElementById('box-scan-input').focus();
            // Show packaging image (with fallbacks)
            const pkgUrl = resolvePackagingUrl(data);
            if (pkgUrl) showImage(pkgUrl, 'Packaging image');
        }
    })
    .catch(error => {
        showStatus('Error scanning slot: ' + error.message, 'error');
    });
}

// Scan Box functionality
let lastScanType = null; // 'rack' or 'erp'

document.getElementById('box-scan-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const raw = this.value.trim();
        if (!raw) return;

        // If slot not yet scanned, treat input as slot_name
        if (!scannedSlot) {
            scanSlotName(raw);
            return;
        }

        // Otherwise treat as ERP code
        scanErp(raw);
    }
});

const scanBoxBtn = document.getElementById('scan-box-btn');
if (scanBoxBtn) {
    scanBoxBtn.addEventListener('click', function() {
        document.getElementById('box-scan-input').focus();
    });
}

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
    const nameEl = document.getElementById('slot-name');
    const rackEl = document.getElementById('rack-name');
    const capEl = document.getElementById('slot-capacity');
    const statusEl = document.getElementById('slot-status');
    if (nameEl) nameEl.textContent = slot.slot_name;
    if (rackEl) rackEl.textContent = slot.rack.rack_name;
    if (capEl) capEl.textContent = slot.capacity;
    if (statusEl) statusEl.textContent = slot.item_id ? 'Occupied' : 'Empty';
    if (slotInfo) slotInfo.classList.remove('hidden');
}

// New: scan slot_name first
function scanSlotName(slotName) {
    fetch('{{ route("operator.posting.scan-slotname") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ slot_name: slotName })
    })
    .then(r => r.json())
    .then(data => {
        console.log('scanSlotName response:', data);
        if (data.error) {
            showStatus(data.error, 'error');
            scannedSlot = null;
            return;
        }
        scannedSlot = data.slot.slot_name;
        currentSlot = data.slot;
        // Prefill UI fields
        document.getElementById('rack-display').value = data.rack.rack_name;
        document.getElementById('part-no-display').value = data.item ? data.item.part_no : '';
        document.getElementById('available-display').value = data.current_qty + '/' + data.capacity;
        showSlotInfo(data.slot);
        showStatus('Slot scanned: ' + scannedSlot + '. Now scan ERP code.', 'success');
        document.getElementById('box-scan-input').value = '';
        document.getElementById('box-scan-input').focus();
        const pkgUrl = resolvePackagingUrl(data);
        console.log('resolved packaging url:', pkgUrl);
        if (pkgUrl) {
            showImage(pkgUrl, 'Packaging image');
        }
    })
    .catch(err => showStatus('Error scanning slot: ' + err.message, 'error'));
}

// New: after rack scanned, scan ERP code to increment
function scanErp(erpCode) {
    if (!scannedSlot) {
        showStatus('Scan slot terlebih dahulu', 'error');
        return;
    }
    fetch('{{ route("operator.posting.store-by-erp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ slot_name: scannedSlot, erp_code: erpCode })
    })
    .then(r => r.json())
    .then(data => {
        console.log('storeByErp response:', data);
        if (data.error) {
            showStatus(data.error, 'error');
            return;
        }
        // Update UI
        document.getElementById('part-no-display').value = data.part_no;
        document.getElementById('rack-display').value = data.rack_name;
        document.getElementById('available-display').value = data.current_qty + '/' + currentSlot.capacity;
        showStatus(data.message, 'success');
        document.getElementById('box-scan-input').value = '';
        document.getElementById('box-scan-input').focus();
        // swap to part image if available (with fallback)
        const partUrl = resolvePartUrl(data);
        console.log('resolved part url:', partUrl);
        if (partUrl) showImage(partUrl, 'Part image');
    })
    .catch(err => showStatus('Error storing by ERP: ' + err.message, 'error'));
}

function hideSlotInfo() {
    const el = document.getElementById('slot-info');
    if (el) el.classList.add('hidden');
}

function showBoxInfo(partNo, erpCode, lotNo, status) {
    const boxInfo = document.getElementById('box-info');
    document.getElementById('box-part-no').textContent = partNo;
    document.getElementById('box-erp-code').textContent = erpCode;
    document.getElementById('box-lot-no').textContent = lotNo;
    document.getElementById('box-status').textContent = status;
    
    boxInfo.classList.remove('hidden');
}

// helper: show image in the preview area
function showImage(url, caption) {
    const img = document.getElementById('item-image');
    const placeholder = document.getElementById('image-placeholder');
    const cap = document.getElementById('image-caption');
    if (!url) {
        showStatus('Image URL kosong', 'warning');
        return;
    }
    img.src = url;
    img.onload = function() {
        img.classList.remove('hidden');
        placeholder.classList.add('hidden');
        cap.textContent = caption || '';
        cap.classList.toggle('hidden', !caption);
    };
    img.onerror = function() {
        img.classList.add('hidden');
        placeholder.classList.remove('hidden');
        cap.classList.add('hidden');
        showStatus('Gagal memuat gambar: ' + url, 'error');
        console.error('Image failed to load:', url);
    };
}

// Build image URLs if backend didn't send absolute URLs
function resolvePackagingUrl(data) {
    if (data.packaging_image_url) return data.packaging_image_url;
    if (data.package_image) return data.package_image;
    if (data.item && data.item.packaging_img) {
        const val = data.item.packaging_img;
        if (/^https?:\/\//i.test(val)) return val;
        if (val.startsWith('storage/') || val.startsWith('/storage/')) return '{{ asset('') }}' + val.replace(/^\//,'');
        // If contains nested folders already, just prefix with storage/
        if (val.includes('/')) return '{{ asset('storage') }}/' + val.replace(/^\//,'');
        // filename only
        return '{{ asset('storage/packaging') }}/' + val;
    }
    return null;
}

function resolvePartUrl(data) {
    if (data.part_image_url) return data.part_image_url;
    if (data.item && data.item.part_img) {
        const val = data.item.part_img;
        if (/^https?:\/\//i.test(val)) return val;
        if (val.startsWith('storage/') || val.startsWith('/storage/')) return '{{ asset('') }}' + val.replace(/^\//,'');
        if (val.includes('/')) return '{{ asset('storage') }}/' + val.replace(/^\//,'');
        return '{{ asset('storage/parts') }}/' + val;
    }
    return null;
}
</script>
