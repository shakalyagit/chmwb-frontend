<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Pharmacist;
use App\Models\PharmacyRegistration;

class PharmacistsController extends Controller
{
    public function search_pharmacist()
    {
        return view('search-pharmacist');
    }

    public function search_pharmacist_action(Request $request)
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

        $query = Pharmacist::with(['pharmacyRegistrations', 'presentAddress', 'permanentAddress'])
            ->where('status', 'Active');

        if ($request->search_type == 'reg_no') {
            $query->whereHas('pharmacyRegistrations', function($q) use ($request) {
                $q->where('registration_number', $request->search_value);
            });
        }

        if ($request->search_type == 'name') {
            $query->where('name', $request->search_value);
        }

        $pharmacists = $query->get();

        if ($pharmacists->count() > 0) {
            $html = '';

            foreach ($pharmacists as $pharmacist) {
                $registration = $pharmacist->pharmacyRegistrations->first();
                $presentAddress = $pharmacist->presentAddress;
                $permanentAddress = $pharmacist->permanentAddress;

                $registrationNumber = $registration->registration_number ?? 'N/A';
                $registrationDate = $registration && !empty($registration->date_of_registration)
                    ? date('d-m-Y', strtotime($registration->date_of_registration))
                    : 'N/A';
                $fatherName = !empty($pharmacist->father_name) ? $pharmacist->father_name : 'N/A';
                $qualification = $registration && !empty($registration->qualification_name)
                    ? $registration->qualification_name
                    : 'N/A';
                $presentAddressText = $presentAddress ? trim(implode(', ', array_filter([
                    $presentAddress->address_line,
                    $presentAddress->police_station,
                    $presentAddress->district,
                    $presentAddress->state,
                    $presentAddress->pincode,
                ]))) : '';
                $presentAddressText = $presentAddressText ?: 'N/A';
                $permanentAddressText = $permanentAddress ? trim(implode(', ', array_filter([
                    $permanentAddress->address_line,
                    $permanentAddress->police_station,
                    $permanentAddress->district,
                    $permanentAddress->state,
                    $permanentAddress->pincode,
                ]))) : '';
                $permanentAddressText = $permanentAddressText ?: 'N/A';

                $html .= '
                <div class="flex justify-end mb-4">
                    <a href="' . route('download_pharmacist_pdf', [
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
                        <h3 class="text-xl font-semibold text-gray-900">' . $pharmacist->name . '</h3>
                    </div>

                    <hr class="mb-4">';

                $html .= '
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-gray-300">

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 w-1/3 border-r bg-gray-50">Registration No</th>
                                <td class="px-2 py-2 border-r w-5">:</td>
                                <td class="px-3 py-2">' . $registrationNumber . '</td>
                            </tr>

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 border-r bg-gray-50">Registration Date</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . $registrationDate . '</td>
                            </tr>

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 border-r bg-gray-50">Fathers Name</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . $fatherName . '</td>
                            </tr>

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 border-r bg-gray-50">Qualification</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . $qualification . '</td>
                            </tr>


                            <tr class="border-b">
                                <th class="text-left px-3 py-2 border-r bg-gray-50">Present Address</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . $presentAddressText . '</td>
                            </tr>

                            <tr class="border-b">
                                <th class="text-left px-3 py-2 border-r bg-gray-50">Permanent Address</th>
                                <td class="px-2 py-2 border-r">:</td>
                                <td class="px-3 py-2">' . $permanentAddressText . '</td>
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

    public function download_pharmacist_pdf(Request $request)
    {
        $query = Pharmacist::with(['pharmacyRegistrations', 'presentAddress', 'permanentAddress'])
            ->where('status', 'Active');

        if ($request->search_type == 'reg_no') {
            $query->whereHas('pharmacyRegistrations', function($q) use ($request) {
                $q->where('registration_number', $request->search_value);
            });
        }

        if ($request->search_type == 'name') {
            $query->where('name', $request->search_value);
        }

        $pharmacists = $query->get();

        $pdf = Pdf::loadView('pdf.pharmacist', compact('pharmacists'))->setPaper('a4')
            ->setOption([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'chroot' => public_path(),
            ]);

        return $pdf->download('pharmacist-search-result.pdf');
    }
}
