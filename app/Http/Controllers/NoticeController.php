<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client;

class NoticeController extends Controller
{
    public function notice_board(Request $request)
    {
        // Get all notice types for filter dropdown
        $noticeTypes = DB::table('notice_types')->orderBy('notice_type', 'asc')->get();

        // Build query
        $query = DB::table('notices')
            ->join('notice_types', 'notices.notice_type_id', '=', 'notice_types.id')
            ->where('notices.status', 'Publish')
            ->where('notices.publish_date_time', '<=', DB::raw('NOW()'))
            ->select('notices.*', 'notice_types.notice_type');

        // Apply notice type filter
        if ($request->filled('notice_type_id')) {
            $query->where('notices.notice_type_id', $request->notice_type_id);
        }

        // Apply date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('notices.publish_date_time', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('notices.publish_date_time', '<=', $request->to_date);
        }

        $notices = $query->orderBy('notices.publish_date_time', 'desc')->paginate(10);

        return view('notice-board', compact('notices', 'noticeTypes'));
    }

    /**
     * Handle AJAX filter requests
     */
    public function filter(Request $request)
    {
        // Build query
        $query = DB::table('notices')
            ->join('notice_types', 'notices.notice_type_id', '=', 'notice_types.id')
            ->where('notices.status', 'Publish')
            ->where('notices.publish_date_time', '<=', DB::raw('NOW()'))
            ->select('notices.*', 'notice_types.notice_type');

        // Apply notice type filter
        if ($request->filled('notice_type_id')) {
            $query->where('notices.notice_type_id', $request->notice_type_id);
        }

        // Apply date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('notices.publish_date_time', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('notices.publish_date_time', '<=', $request->to_date);
        }

        $notices = $query->orderBy('notices.publish_date_time', 'desc')->paginate(10);

        return response()->json([
            'html' => view('partials.notice-table', compact('notices'))->render(),
        ]);
    }

    public function show($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
        } catch (\Exception $e) {
            abort(404, 'Invalid notice ID');
        }

        $notice = DB::table('notices')
            ->join('notice_types', 'notices.notice_type_id', '=', 'notice_types.id')
            ->where('notices.id', $id)
            ->where('notices.publish_date_time', '<=', DB::raw('NOW()'))
            ->select('notices.*', 'notice_types.notice_type')
            ->first();

        if (!$notice) {
            abort(404, 'Notice not found');
        }

        $media = DB::table('media')
            ->where('ref_table', 'notices')
            ->where('ref_id', $id)
            ->get();

        return view('notice-detail', compact('notice', 'media'));
    }

    public function download($id)
    {
        $media = DB::table('media')->where('media_id', $id)->first();

        if (!$media) {
            abort(404, 'Media not found');
        }

        $url = env('MEDIA_URL') . '/' . $media->file_path;

        return response()->streamDownload(function () use ($url) {
            $stream = fopen($url, 'rb');

            if ($stream) {
                while (!feof($stream)) {
                    echo fread($stream, 1024 * 8);
                    flush();
                }
                fclose($stream);
            }
        }, $media->file_name);
    }
}
