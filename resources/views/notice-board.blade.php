@extends('layouts.app')
@section('content')
<div class="w-full p-2 sm:p-4 lg:p-6">
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 w-full min-h-[800px]">
        <!-- Header -->
        <div class="text-center mb-8 sm:mb-12">
            <div class="w-full flex justify-center mb-4 sm:mb-6">
                <img src="{{ asset('images/Screenshot_192.png') }}"
                    class="rounded-full h-20 w-20 sm:h-24 sm:w-24 lg:h-32 lg:w-32 object-contain border-2 sm:border-4 border-blue-500 shadow-lg"
                    alt="Council of Homoeopathic Medicine, West Bengal  Logo" />
            </div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-2 sm:mb-4 px-2">
                Council of Homoeopathic Medicine, West Bengal
            </h1>
            <h2 class="text-lg sm:text-xl font-semibold text-blue-600 mb-4">
                Notice Board
            </h2>
        </div>

        <!-- Filter Section -->
        <div class="mb-8 p-4 sm:p-6 bg-blue-50 rounded-lg border border-blue-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Notices</h3>
            <div id="filter-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Notice Type Filter -->
                    <div>
                        <label for="notice_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Notice Type
                        </label>
                        <select id="notice_type_id" name="notice_type_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Select</option>
                            @forelse($noticeTypes as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->notice_type }}
                                </option>
                            @empty
                                <option disabled>No notice types available</option>
                            @endforelse
                        </select>
                    </div>

                    <!-- From Date Filter -->
                    <div>
                        <label for="from_date" class="block text-sm font-medium text-gray-700 mb-2">
                            From Date
                        </label>
                        <input type="date" id="from_date" name="from_date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>

                    <!-- To Date Filter -->
                    <div>
                        <label for="to_date" class="block text-sm font-medium text-gray-700 mb-2">
                            To Date
                        </label>
                        <input type="date" id="to_date" name="to_date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex gap-3 items-end">
                        <button type="button" id="filter-btn"
                            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span id="filter-btn-text">Filter</span>
                        </button>
                        <button type="button" id="reset-btn"
                            class="inline-flex items-center bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notices Table -->
        <div class="overflow-x-auto" id="notices-container">
            @include('partials.notice-table')
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('filter-btn');
    const resetBtn = document.getElementById('reset-btn');
    const noticeTypeSelect = document.getElementById('notice_type_id');
    const fromDateInput = document.getElementById('from_date');
    const toDateInput = document.getElementById('to_date');
    const noticesContainer = document.getElementById('notices-container');

    // Filter button click
    filterBtn.addEventListener('click', function() {
        applyFilters();
    });

    // Reset button click
    resetBtn.addEventListener('click', function() {
        noticeTypeSelect.value = '';
        fromDateInput.value = '';
        toDateInput.value = '';
        applyFilters();
    });

    // Enter key on input fields
    [noticeTypeSelect, fromDateInput, toDateInput].forEach(element => {
        element.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                applyFilters();
            }
        });
    });

    function applyFilters() {
        const noticeTypeId = noticeTypeSelect.value;
        const fromDate = fromDateInput.value;
        const toDate = toDateInput.value;

        // Show loading state
        filterBtn.disabled = true;
        filterBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Loading...';

        // Send AJAX request
        fetch('{{ route("notice.filter") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                notice_type_id: noticeTypeId,
                from_date: fromDate,
                to_date: toDate
            })
        })
        .then(response => response.json())
        .then(data => {
            // Update table with new content
            noticesContainer.innerHTML = data.html;

            // Attach event listeners to new pagination links
            attachPaginationListeners();

            // Reset button state
            filterBtn.disabled = false;
            filterBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>Filter';

            // Smooth scroll to table
            noticesContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(error => {
            console.error('Error:', error);
            filterBtn.disabled = false;
            filterBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>Filter';
            alert('An error occurred while filtering. Please try again.');
        });
    }

    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('#pagination-links a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page');
                const noticeTypeId = noticeTypeSelect.value;
                const fromDate = fromDateInput.value;
                const toDate = toDateInput.value;

                // Show loading state
                filterBtn.disabled = true;
                filterBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Loading...';

                fetch('{{ route("notice.filter") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        notice_type_id: noticeTypeId,
                        from_date: fromDate,
                        to_date: toDate,
                        page: page
                    })
                })
                .then(response => response.json())
                .then(data => {
                    noticesContainer.innerHTML = data.html;
                    attachPaginationListeners();
                    filterBtn.disabled = false;
                    filterBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>Filter';
                    noticesContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                })
                .catch(error => {
                    console.error('Error:', error);
                    filterBtn.disabled = false;
                    filterBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>Filter';
                });
            });
        });
    }

    // Initial pagination setup
    attachPaginationListeners();
});
</script>
@endpush