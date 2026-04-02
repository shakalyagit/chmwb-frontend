@extends('layouts.app')
@section('content')
<div class="w-full p-2 sm:p-4 lg:p-6">
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 w-full">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('notice_board') }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Notice Board
            </a>
        </div>

        <!-- Header Section -->
        <div class="border-b-2 border-gray-200 pb-6 mb-8">
            <h1 class="text-2xl sm:text-2xl text-gray-900 mb-4">
                {{ $notice->notice_subject }}
            </h1>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <span class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-semibold text-sm">
                    {{ $notice->notice_type }}
                </span>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold text-gray-900 mb-1">Published Date:</p>
                    <p class="text-gray-600">{{ date('d-m-Y', strtotime($notice->publish_date_time)) }}</p>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        @if($notice->notice_body)
        <div class="mb-8 p-4 sm:p-6 bg-gray-50 rounded-lg border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Notice Content</h2>
            <div class="text-gray-700 leading-relaxed text-justify whitespace-pre-wrap">
                {!! $notice->notice_body !!}
            </div>
        </div>
        @endif

        <!-- Media Section (PDFs & Images) -->
        @if($media->count() > 0)
        <div class="mb-8">

            <!-- Media Display -->
            <div class="space-y-6">
                @foreach($media as $m)
                @php
                $isPdf = strpos($m->file_type, 'pdf') !== false;
                $isImage = strpos($m->file_type, 'image') === 0;
                @endphp

                @if($isImage)
                <!-- Image Display -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-md">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $m->file_name }}</p>
                            <p class="text-xs text-gray-500 mt-1">Image</p>
                        </div>
                        <a href="{{ route('media.download', $m->media_id) }}"
                            class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-2 px-4 rounded">
                            Download
                        </a>
                    </div>
                    <div class="p-4">
                        <img src="{{ env('MEDIA_URL') . '/' . $m->file_path }}"
                            alt="{{ $m->file_name }}"
                            class="w-full h-auto rounded-lg cursor-pointer hover:opacity-90 transition-opacity duration-200"
                            onclick="openImageLightbox('{{ env('MEDIA_URL') . '/' . $m->file_path }}', '{{ $m->file_name }}')">
                    </div>
                </div>
                @elseif($isPdf)
                <!-- PDF Display -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-md">
                    <div class="p-4 flex items-center justify-center">
                        <embed src="{{ env('MEDIA_URL') . '/' . $m->file_path }}#navpanes=0"
                            type="application/pdf"
                            class="w-full border border-gray-300 rounded-lg"
                            style="min-height: 1130px; width:800px;"
                            title="{{ $m->file_name }}">
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Image Lightbox Modal -->
<div id="imageLightbox" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="relative bg-gray-900 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-auto">
        <!-- Close Button -->
        <button onclick="closeImageLightbox()"
            class="absolute top-4 right-4 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 z-10 transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Image -->
        <img id="lightboxImage" src="" alt="" class="w-full h-auto">

        <!-- Image Name -->
        <div class="bg-gray-800 text-white p-4 text-center">
            <p id="lightboxImageName" class="font-semibold"></p>
        </div>
    </div>
</div>

<!-- Scripts for image lightbox -->
<script>
    async function downloadFile(url, filename) {
        try {
            const response = await fetch(url);
            const blob = await response.blob();
            const blobUrl = window.URL.createObjectURL(blob);

            const link = document.createElement('a');
            link.href = blobUrl;
            link.download = filename;
            link.style.display = 'none';

            document.body.appendChild(link);
            link.click();

            // Clean up
            document.body.removeChild(link);
            window.URL.revokeObjectURL(blobUrl);
        } catch (error) {
            console.error('Download failed:', error);
            // Fallback: open in new tab
            window.open(url, '_blank');
        }
    }

    function openImageLightbox(imagePath, imageName) {
        document.getElementById('imageLightbox').classList.remove('hidden');
        document.getElementById('lightboxImage').src = imagePath;
        document.getElementById('lightboxImageName').textContent = imageName;
        document.body.style.overflow = 'hidden';
    }

    function closeImageLightbox() {
        document.getElementById('imageLightbox').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close lightbox when clicking outside the image
    document.getElementById('imageLightbox')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closeImageLightbox();
        }
    });

    // Close lightbox with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageLightbox();
        }
    });
</script>
@endsection