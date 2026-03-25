<!-- Active Filters Display -->
@if(request('notice_type_id') || request('from_date') || request('to_date'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
        <p class="text-sm text-green-800">
            <strong>Active Filters:</strong>
            @if(request('notice_type_id'))
                @php
                    $noticeTypes = \Illuminate\Support\Facades\DB::table('notice_types')->orderBy('notice_type', 'asc')->get();
                    $selectedType = $noticeTypes->firstWhere('id', request('notice_type_id'));
                @endphp
                Notice Type: <span class="font-semibold">{{ $selectedType?->notice_type }}</span>
            @endif
            @if(request('from_date'))
                | From Date: <span class="font-semibold">{{ \Carbon\Carbon::parse(request('from_date'))->format('d-M-Y') }}</span>
            @endif
            @if(request('to_date'))
                | To Date: <span class="font-semibold">{{ \Carbon\Carbon::parse(request('to_date'))->format('d-M-Y') }}</span>
            @endif
        </p>
    </div>
@endif

@if($notices->count() > 0)
    <table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden shadow-md">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-2 sm:px-3 py-3 text-left text-sm font-semibold">Published Date</th>
                <th class="px-2 sm:px-3 py-3 text-left text-sm font-semibold">Notice Type</th>
                <th class="px-2 sm:px-3 py-3 text-left text-sm font-semibold">Subject</th>
                <th class="px-2 sm:px-3 py-3 text-center text-sm font-semibold"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($notices as $notice)
                @php
                    $mediaCount = \Illuminate\Support\Facades\DB::table('media')->where('ref_table', 'notices')->where('ref_id', $notice->id)->count();
                    $encryptedId = \Illuminate\Support\Facades\Crypt::encryptString($notice->id);
                @endphp
                <tr class="hover:bg-blue-50 transition-colors duration-200">
                    <td class="px-2 sm:px-3 py-4 text-sm text-gray-600">
                        {{ date('d-m-Y', strtotime($notice->publish_date_time)) }}
                    </td>
                    <td class="px-2 sm:px-3 py-4 text-sm text-gray-800 font-medium">
                        <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ $notice->notice_type }}
                        </span>
                    </td>
                    <td class="px-2 sm:px-3 py-4 text-sm text-gray-700">
                        {!! nl2br(wordwrap($notice->notice_subject, 110, "\n", true)) !!}
                    </td>
                    <td class="px-2 sm:px-3 py-4 text-center">
                        <a href="{{ route('notice.show', $encryptedId) }}"
                            class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-2 sm:px-3 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-lg font-medium">No Notices Available</p>
                            <p class="text-sm mt-1">Currently there are no active notices to display.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    @if($notices->hasPages())
        <div class="mt-6 flex justify-center">
            <div id="pagination-links">
                {{ $notices->appends(request()->query())->render('pagination::tailwind') }}
            </div>
        </div>
    @endif
@else
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center py-12 px-4">
        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Notices Available</h3>
        <p class="text-gray-500 text-center">There are currently no notices matching your filters.</p>
    </div>
@endif
