@extends('layouts.app')

@section('title', 'Items')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">ITEMS</h1>
    </div>

    <!-- Total Items Card -->
    <div class="max-w-sm mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm border relative">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-600 text-sm mb-1">Total Items</h3>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalItems }}</div>
                </div>
                <button class="w-6 h-6 bg-[#0A2856] rounded-md flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-5">
        <div>
            <a href="{{ route('admin.item.create') }}" class="bg-[#0A2856] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors me-2">
                Add Item
            </a>
            <button onclick="openUploadModal()" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                Upload Excel
            </button>
        </div>
        <a href="{{ route('admin.item.history.all') }}" class="bg-[#0A2856] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#0A2856]/90 transition-colors">
            Change Log
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="itemsTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0A2856]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">ERP Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Part No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($itemsWithSlotInfo as $index => $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->erp_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->part_no }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->description }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->customer }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            @if(!$item->is_assigned)
                                <form action="{{ route('admin.item.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 min-w-[50px]">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.item.history', $item->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 min-w-[50px] inline-block text-center">
                                History
                            </a>
                            <a href="{{ route('admin.item.edit', $item->id) }}" class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 min-w-[50px] inline-block text-center">
                                Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">-</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Upload Excel Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-[#0A2856] rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Upload Excel File</h3>
                    <p class="text-sm text-gray-500">Import items from spreadsheet</p>
                </div>
            </div>
            <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4">
            <form action="{{ route('admin.item.upload-excel') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                
                <!-- File Upload Area -->
                <div class="mb-6">
                    <div class="relative">
                        <input 
                            id="excel_file" 
                            name="excel_file" 
                            type="file" 
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                            accept=".xlsx,.xls" 
                            required 
                        />
                        <div id="dropArea" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-[#0A2856] hover:bg-gray-50 transition-all duration-200">
                            <div id="defaultContent">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-900 mb-2">Drop your Excel file here</p>
                                <p class="text-sm text-gray-500 mb-4">or <span class="text-[#0A2856] font-medium">click to browse</span></p>
                                <div class="flex items-center justify-center space-x-2 text-xs text-gray-400">
                                    <span>Supports:</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">XLSX</span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">XLS</span>
                                </div>
                            </div>
                            <div id="filePreview" class="hidden">
                                <svg class="mx-auto h-12 w-12 text-green-500 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p id="fileName" class="text-lg font-medium text-gray-900 mb-1"></p>
                                <p id="fileSize" class="text-sm text-gray-500 mb-3"></p>
                                <button type="button" onclick="resetFileInput()" class="text-sm text-[#0A2856] hover:underline">Choose different file</button>
                            </div>
                        </div>
                    </div>
                    @error('excel_file')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Format Info -->
                <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Excel Format Requirements</h4>
                            <ul class="text-xs text-blue-700 space-y-1 mb-3">
                                <li>• Headers in row 8: ERP Code, Part No, Description, Model, Customer, Quantity</li>
                                <li>• Data starts from row 9</li>
                                <li>• Maximum file size: 10MB</li>
                            </ul>
                            <a href="{{ asset('items_format.xlsx') }}" class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 font-medium group">
                                <svg class="w-3 h-3 mr-1 group-hover:translate-y-0.5 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Download Template
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button 
                        type="button" 
                        onclick="closeUploadModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="px-6 py-2 bg-[#0A2856] text-white rounded-lg text-sm font-medium hover:bg-[#0A2856]/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <svg id="uploadIcon" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <svg id="loadingIcon" class="w-4 h-4 mr-2 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="submitText">Upload File</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// DataTable initialization
$(document).ready(function() {
    $('#itemsTable').DataTable({
        initComplete: function () {
            this.api()
                .columns()
                .every(function () {
                    let column = this;
                    let title = column.header().textContent;
 
                    // Create input element
                    let input = document.createElement('input');
                    input.placeholder = title;
                    input.className = 'border border-gray-300 rounded-md px-2 py-1 text-sm w-full focus:outline-none focus:ring-2 focus:ring-[#0A2856] focus:border-[#0A2856]';
                    
                    // Check if footer exists and has content
                    if (column.footer() && column.footer().textContent !== undefined) {
                        column.footer().replaceChildren(input);
                    }
 
                    // Event listener for user input
                    input.addEventListener('keyup', () => {
                        if (column.search() !== input.value) {
                            column.search(input.value).draw();
                        }
                    });
                });
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries per page",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});

// Modal functions
function openUploadModal() {
    document.getElementById('uploadModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    resetForm();
}

function resetForm() {
    const form = document.getElementById('uploadForm');
    if (form) {
        form.reset();
    }
    resetFileInput();
    resetSubmitButton();
}

function resetFileInput() {
    const defaultContent = document.getElementById('defaultContent');
    const filePreview = document.getElementById('filePreview');
    const dropArea = document.getElementById('dropArea');
    
    if (defaultContent) defaultContent.classList.remove('hidden');
    if (filePreview) filePreview.classList.add('hidden');
    if (dropArea) {
        dropArea.className = 'border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-[#0A2856] hover:bg-gray-50 transition-all duration-200';
    }
}

function resetSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    const uploadIcon = document.getElementById('uploadIcon');
    const loadingIcon = document.getElementById('loadingIcon');
    const submitText = document.getElementById('submitText');
    
    if (submitBtn) submitBtn.disabled = false;
    if (uploadIcon) uploadIcon.classList.remove('hidden');
    if (loadingIcon) loadingIcon.classList.add('hidden');
    if (submitText) submitText.textContent = 'Upload File';
}

// Enhanced file upload handling
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('excel_file');
    const dropArea = document.getElementById('dropArea');
    const defaultContent = document.getElementById('defaultContent');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const uploadForm = document.getElementById('uploadForm');
    
    // Only initialize if elements exist (modal is present)
    if (!fileInput || !dropArea) return;
    
    // File input change handler
    fileInput.addEventListener('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });
    
    // Drag and drop handlers
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        dropArea.classList.add('border-[#0A2856]', 'bg-blue-50');
    }
    
    function unhighlight(e) {
        dropArea.classList.remove('border-[#0A2856]', 'bg-blue-50');
    }
    
    dropArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    }
    
    function handleFileSelect(file) {
        if (file && fileName && fileSize && defaultContent && filePreview) {
            const fileSizeInMB = (file.size / 1024 / 1024).toFixed(2);
            
            fileName.textContent = file.name;
            fileSize.textContent = `${fileSizeInMB} MB`;
            
            defaultContent.classList.add('hidden');
            filePreview.classList.remove('hidden');
            
            dropArea.classList.add('border-green-300', 'bg-green-50');
            dropArea.classList.remove('border-gray-300');
        }
    }
    
    // Form submission with loading state
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const uploadIcon = document.getElementById('uploadIcon');
            const loadingIcon = document.getElementById('loadingIcon');
            const submitText = document.getElementById('submitText');
            
            // Show loading state
            if (submitBtn) submitBtn.disabled = true;
            if (uploadIcon) uploadIcon.classList.add('hidden');
            if (loadingIcon) loadingIcon.classList.remove('hidden');
            if (submitText) submitText.textContent = 'Uploading...';
        });
    }
});

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('uploadModal');
    if (modal && e.target === modal) {
        closeUploadModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('uploadModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeUploadModal();
        }
    }
});
</script>
@endsection