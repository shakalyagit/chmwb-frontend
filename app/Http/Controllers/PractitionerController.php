<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PractitionerController extends Controller
{
    public function search_physician()
    {
        return view('search-physician');
    }
    public function search_physician_action(Request $request)
    {

        $token = $request->input('g-recaptcha-response');

        $secret = config('services.recaptcha.secret_key');

        $url = "https://www.google.com/recaptcha/api/siteverify";

        $data = [
            'secret' => $secret,
            'response' => $token
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        $captcha = json_decode($response, true);

        if (!$captcha['success'] || $captcha['score'] < 0.5) {
            return response()->json([
                'status' => 'error',
                'message' => 'Captcha verification failed.'
            ]);
        }

        $query = DB::table('practioners');

        if ($request->search_type == 'reg_no') {
            $query->where('registration_no', $request->search_value);
        }

        if ($request->search_type == 'name') {
            $query->where('name', $request->search_value);
        }

        $practitioners = $query->get();

        if ($practitioners->count() > 0) {

            $html = '';

            foreach ($practitioners as $doctor) {

                $html = '
                <div class="flex justify-end mb-4">
                    <a href="' . route('download_physician_pdf', [
                    'search_type' => $request->search_type,
                    'search_value' => $request->search_value
                ]) . '"
                    class="inline-flex items-center gap-2 border border-red-600 text-red-600 hover:bg-red-600 hover:text-white px-4 py-2 rounded-lg transition">

                        <svg xmlns="http://www.w3.org/2000/svg" 
                        class="w-5 h-5"
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v10m0 0l-4-4m4 4l4-4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>

                        </svg>

                        Download as PDF
                    </a>
                </div>';

                $html .= '
                <div class="w-full border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition bg-white">

                    <div class="flex justify-between items-center mb-4">

                        <h3 class="text-xl font-semibold text-gray-900">' . $doctor->name . '</h3>';

                // if ($doctor->status == 'Active') {
                //     $html .= '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Active</span>';
                // } else {
                //     $html .= '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Inactive</span>';
                // }



                $html .= '
                    </div>

                    <hr class="mb-4">

                    <div class="space-y-3 text-sm text-gray-700">

                        <div>
                            <span class="font-semibold text-gray-800">Registration No :</span>
                            <span class="ml-2">' . $doctor->registration_no . '</span>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-800">Registration Date :</span>
                            <span class="ml-2">' . date('d-m-Y', strtotime($doctor->registration_date)) . '</span>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-800">Fathers Name :</span>
                            <span class="ml-2">' . $doctor->fathers_name . '</span>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-800">Qualification :</span>
                            <span class="ml-2">' . $doctor->qualification . '</span>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-800">Address :</span>
                            <span class="ml-2">' . $doctor->address . '</span>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-800">State :</span>
                            <span class="ml-2">' . $doctor->state . '</span>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-800">District :</span>
                            <span class="ml-2">' . $doctor->district . '</span>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-800">Pincode :</span>
                            <span class="ml-2">' . $doctor->pincode . '</span>
                        </div>

                    </div>

                </div>';
            }

            return response()->json([
                'status' => 'success',
                'html' => $html
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No record found.'
        ]);
    }

    public function download_physician_pdf(Request $request)
    {
        $query = DB::table('practioners');

        if ($request->search_type == 'reg_no') {
            $query->where('registration_no', $request->search_value);
        }

        if ($request->search_type == 'name') {
            $query->where('name', $request->search_value);
        }

        $practitioners = $query->get();

        $pdf = Pdf::loadView('pdf.physician', compact('practitioners'));

        return $pdf->download('physician-search-result.pdf');
    }
}
