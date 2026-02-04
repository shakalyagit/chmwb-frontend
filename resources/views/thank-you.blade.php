<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted Successfully</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-b from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-2xl">
        <!-- Success Card -->
        <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
            <!-- Header with Success Icon -->
            <div class="bg-gradient-to-r from-green-400 to-green-600 px-8 py-12 text-center">
                <div class="flex justify-center mb-4">
                    <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Application Submitted Successfully!</h1>
                <p class="text-green-100">Thank you for submitting your application</p>
            </div>

            <!-- Content -->
            <div class="px-8 py-10">
                <!-- Reference ID Section -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded mb-8">
                    <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide mb-2">Your Reference ID</p>
                    <p class="text-3xl font-bold text-blue-600 font-mono">{{ $applicationHead->reference_id }}</p>
                    <p class="text-gray-500 text-sm mt-2">Please save this ID for future reference</p>
                </div>

                <!-- Application Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm font-semibold mb-1">Applicant Name</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $detail->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm font-semibold mb-1">Email Address</p>
                        <p class="text-lg font-semibold text-gray-800 break-all">{{ $detail->email ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm font-semibold mb-1">Mobile Number</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $detail->mobile ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm font-semibold mb-1">Submission Date</p>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $applicationHead->created_at->timezone('Asia/Kolkata')->format('d/m/Y h:i A') }}</p>
                    </div>
                </div>

                <!-- Application Reasons -->
                @if ($reasons && count($reasons) > 0)
                    <div class="bg-indigo-50 p-6 rounded mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Application For</h3>
                        <ul class="space-y-2">
                            @foreach ($reasons as $reason)
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-indigo-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span
                                        class="text-gray-700">{{ ucwords(str_replace('-', ' ', $reason->reason_id)) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Info Message -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                    <p class="text-yellow-800 text-sm">
                        <strong>Note:</strong> A confirmation email with your application details and PDF has been sent
                        to your registered email address. Please check your inbox and spam folder.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('thank-you.download', ['referenceId' => $applicationHead->reference_id]) }}"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download Application Data
                    </a>
                    <a href="/"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-lg text-center transition duration-200">
                        Back to Home
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-100 px-8 py-6 text-center text-gray-600 text-sm border-t">
                <p>If you have any questions, please contact us at <strong>info@chmwb.org</strong></p>
            </div>
        </div>
    </div>
</body>

</html>
