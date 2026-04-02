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
            $query->where('status', 'Active');
        }

        if ($request->search_type == 'name') {
            $query->where('name', $request->search_value);
            $query->where('status', 'Active');
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
                        <h3 class="text-xl font-semibold text-gray-900">' . $doctor->name . '</h3>
                    </div>

                    <hr class="mb-4">

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-gray-300">

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 w-1/3 border-r bg-gray-50">Registration No</th>
                                <td class="px-2 py-2 border-r w-5">:</td>
                                <td class="px-3 py-2">' . $doctor->registration_no . '</td>
                            </tr>

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 border-r bg-gray-50">Registration Date</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . date('d-m-Y', strtotime($doctor->registration_date)) . '</td>
                            </tr>

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 border-r bg-gray-50">Fathers Name</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . (!empty($doctor->fathers_name) ? $doctor->fathers_name : 'N/A') . '</td>
                            </tr>

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 border-r bg-gray-50">Qualification</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . (!empty($doctor->qualification) ? $doctor->qualification : 'N/A') . '</td>
                            </tr>

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 border-r bg-gray-50">Address</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . (!empty($doctor->address) ? $doctor->address : 'N/A') . '</td>
                            </tr>

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 border-r bg-gray-50">State</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . (!empty($doctor->state) ? $doctor->state : 'N/A') . '</td>
                            </tr>

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 border-r bg-gray-50">District</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . (!empty($doctor->district) ? $doctor->district : 'N/A') . '</td>
                            </tr>

                            <tr>
                                <th class="text-left px-3 py-2 border-r bg-gray-50">Pincode</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . (!empty($doctor->pincode) ? $doctor->pincode : 'N/A') . '</td>
                            </tr>

                        </table>
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

        $pdf = Pdf::loadView('pdf.physician', compact('practitioners'))->setPaper('a4')
            ->setOption([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'chroot' => public_path(),
            ]);;

        return $pdf->download('physician-search-result.pdf');
    }
}
