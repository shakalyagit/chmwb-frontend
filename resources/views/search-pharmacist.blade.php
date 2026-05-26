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
            <div
                class="bg-yellow-50 border-2 border-yellow-200 rounded-lg sm:rounded-xl p-4 sm:p-6 max-w-full sm:max-w-3xl mx-auto mt-3 bg-warning-subtle">
                <p class="text-sm sm:text-base text-red-800 leading-relaxed">
                    Work on this site is currently in progress. For any queries, please contact or visit the Council of Homoeopathic Medicine, West Bengal.
                </p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 max-w-3xl mx-auto mt-8 form-section space-y-6 sm:space-y-8">
            <div
                class="bg-blue-50 border-2 border-blue-200 rounded-lg sm:rounded-xl sm:p-3 max-w-full sm:max-w-3xl mx-auto bg-warning-subtle">
                <p class="text-sm sm:text-base text-gray-800 text-center">
                    <strong>Search Registered Homoeopathic Pharmacist</strong>
                </p>
            </div>
            <form id="pharmacistSearchForm">
                <div class="flex justify-center gap-6 mb-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="search_type" value="reg_no" checked class="text-blue-600">
                        <span class="text-gray-700 font-medium">Registration No</span>
                    </label>
    
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="search_type" value="name" class="text-blue-600">
                        <span class="text-gray-700 font-medium">Name</span>
                    </label>
                </div>
    
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text"
                        name="search_value"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2">
    
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
                        Search
                    </button>
                </div>
            </form>
            <div id="searchResult" class="mt-6"></div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $('#pharmacistSearchForm').on('submit', function(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config("services.recaptcha.site_key") }}', {
                action: 'search'
            }).then(function(token) {
                $.ajax({
                    url: "{{ route('search_pharmacist_action') }}",
                    type: "POST",
                    data: {
                        search_type: $('input[name="search_type"]:checked').val(),
                        search_value: $('input[name="search_value"]').val(),
                        'g-recaptcha-response': token,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('#searchResult').html('<p class="text-green-600">Verifying captcha...</p>');
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            $('#searchResult').html(res.html);
                        } else {
                            $('#searchResult').html(
                                `<div class="relative bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                    <span>${res.message}</span>
                                    <button onclick="this.parentElement.remove()" 
                                    class="absolute top-2 right-2 text-red-700 hover:text-red-900 font-bold text-lg">
                                        &times;
                                    </button>
                                </div>`
                            );
                        }
                    }
                });
            });
        });
    });
</script>
@endpush