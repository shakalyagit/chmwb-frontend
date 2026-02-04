<?php

namespace App\Http\Controllers;

use App\Models\ApplicationHead;
use App\Models\ApplicationMedia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    /**
     * Display the thank you page
     */
    public function show($referenceId)
    {
        $applicationHead = ApplicationHead::where('reference_id', $referenceId)->firstOrFail();
        $detail = $applicationHead->details;
        $reasons = $applicationHead->reasons;

        return view('thank-you', compact('applicationHead', 'detail', 'reasons'));
    }

    /**
     * Download application data as PDF
     */
    public function downloadPdf($referenceId)
    {
        $applicationHead = ApplicationHead::where('reference_id', $referenceId)->firstOrFail();
        $detail = $applicationHead->details;
        $reasons = $applicationHead->reasons;
        $profile_pic = ApplicationMedia::where('application_head_id', $applicationHead->id)->where('document_type', 'photo')->first();

        $pdf = Pdf::loadView('pdf.application-form', [
            'applicationHead' => $applicationHead,
            'detail' => $detail,
            'reasons' => $reasons,
            // 'profile_pic' => empty($profile_pic)?'':'/storage/'.$profile_pic->url,
            'profile_pic' => empty($profile_pic)?'':storage_path('app/public/' . $profile_pic->url),
        ]);

        $filename = 'Application_' . $referenceId . '.pdf';

        return $pdf->download($filename);
    }
}
