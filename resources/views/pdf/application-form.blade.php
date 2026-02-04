<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Application Form - {{ $applicationHead->reference_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        /* .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
        } */

        .logo img {
            width: 100%;
            height: auto;
        }

        .header h1 {
            font-size: 18px;
            color: #667eea;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 14px;
            color: #333;
            font-weight: normal;
        }

        .reference-box {
            background-color: #f3f4f6;
            padding: 10px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background-color: #667eea;
            color: white;
            padding: 8px 10px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table td {
            padding: 8px;
            border: 1px solid #e5e7eb;
        }

        table td:first-child {
            font-weight: bold;
            width: 40%;
            background-color: #f9fafb;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('images/CHM.jpg') }}" alt="Logo">
        </div>
        {{-- <h1>Council of Homoeopathic Medicine, West Bengal</h1> --}}
        <h2>Doctor's & Student's Application Portal</h2>
    </div>

    {{-- <!-- Reference ID -->
    <div class="reference-box">
        <strong>Reference ID:</strong> {{ $applicationHead->reference_id }}<br>
        <strong>Submission Date:</strong> {{ $applicationHead->created_at->format('d/m/Y h:i A') }}
    </div>

    <!-- Application Reasons -->
    <div class="section">
        <div class="section-title">Application Reason(s)</div>
        <ul style="padding-left: 20px;">
            @foreach ($reasons as $reason)
                <li>{{ ucwords(str_replace('-', ' ', $reason->reason_id)) }}</li>
            @endforeach
        </ul>
    </div>

    <div class="profile_image">
        <img src="{{ public_path('images/Screenshot_192.png') }}" alt="Logo">
    </div> --}}
    <table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 10px;">
        <tr>

            <!-- LEFT SIDE (80%) -->
            <td width="85%" valign="top">

                <!-- Reference ID -->
                <div class="reference-box">
                    <strong>Reference ID:</strong> {{ $applicationHead->reference_id }}<br>
                    <strong>Submission Date:</strong>
                    {{ $applicationHead->created_at->timezone('Asia/Kolkata')->format('d/m/Y h:i A') }}
                </div>

                <!-- Application Reasons -->
                <div class="section">
                    <div class="section-title">Application Reason(s)</div>
                    <ul style="padding-left: 20px;">
                        @foreach ($reasons as $reason)
                            <li>{{ ucwords(str_replace('-', ' ', $reason->reason_id)) }}</li>
                        @endforeach
                    </ul>
                </div>

            </td>

            <!-- RIGHT SIDE (20%) -->
            <td width="15%" align="right" valign="top">
                <img src="{{ $profile_pic }}" style="max-width: 100%; height: 160px; display:block; border: 1px solid">
            </td>
        </tr>
    </table>



    <!-- Personal Information -->
    <div class="section">
        <div class="section-title">1. Personal Information</div>
        <table>
            <tr>
                <td>Full Name</td>
                <td>{{ $detail->name }}</td>
            </tr>
            <tr>
                <td>Father's Name</td>
                <td>{{ $detail->father_name }}</td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td>{{ $detail->dob ? \Carbon\Carbon::parse($detail->dob)->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <td>Blood Group</td>
                <td>{{ $detail->blood_group ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- Contact Information -->
    <div class="section">
        <div class="section-title">2. Contact Information</div>
        <table>
            <tr>
                <td>Address</td>
                <td>{{ $detail->address }}</td>
            </tr>
            <tr>
                <td>District</td>
                <td>{{ $detail->district }}</td>
            </tr>
            <tr>
                <td>Pincode</td>
                <td>{{ $detail->pincode }}</td>
            </tr>
            <tr>
                <td>Police Station</td>
                <td>{{ $detail->police_station }}</td>
            </tr>
            <tr>
                <td>Mobile Number</td>
                <td>{{ $detail->mobile }}</td>
            </tr>
            <tr>
                <td>Email Address</td>
                <td>{{ $detail->email }}</td>
            </tr>
            <tr>
                <td>Aadhaar Number</td>
                <td>{{ $detail->aadhaar }}</td>
            </tr>
        </table>
    </div>

    <!-- Academic Information -->
    <div class="section">
        <div class="section-title">3. Academic & Registration Information</div>
        <table>
            @if ($detail->reg_number)
                <tr>
                    <td>Registration Number</td>
                    <td>{{ $detail->reg_number }}</td>
                </tr>
                <tr>
                    <td>Registration Date</td>
                    <td>{{ $detail->reg_date ? \Carbon\Carbon::parse($detail->reg_date)->format('d/m/Y') : 'N/A' }}
                    </td>
                </tr>
            @endif
            @if ($detail->qualification)
                <tr>
                    <td>Qualification</td>
                    <td>{{ $detail->qualification }}</td>
                </tr>
            @endif
            @if ($detail->examination)
                <tr>
                    <td>Examination</td>
                    <td>{{ $detail->examination }}</td>
                </tr>
            @endif
            @if ($detail->held_in)
                <tr>
                    <td>Held In</td>
                    <td>{{ $detail->held_in }}</td>
                </tr>
            @endif
            <tr>
                <td>University</td>
                <td>{{ $detail->university }}</td>
            </tr>
            <tr>
                <td>College</td>
                <td>{{ $detail->college }}</td>
            </tr>
            @if ($detail->college_district)
                <tr>
                    <td>College District</td>
                    <td>{{ $detail->college_district }}</td>
                </tr>
            @endif
            @if ($detail->final_roll_no)
                <tr>
                    <td>Final Roll No</td>
                    <td>{{ $detail->final_roll_no }}</td>
                </tr>
            @endif
            @if ($detail->term)
                <tr>
                    <td>Term</td>
                    <td>{{ $detail->term }}</td>
                </tr>
            @endif
            @if ($detail->university_reg_no)
                <tr>
                    <td>University Reg No</td>
                    <td>{{ $detail->university_reg_no }}</td>
                </tr>
            @endif
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Council of Homoeopathic Medicine, West Bengal | 9/1B, Mahatma Gandhi Road (1st Floor), Kolkata - 700 009</p>
        <p>Generated on: {{ now()->timezone('Asia/Kolkata')->format('d/m/Y h:i A') }}</p>
    </div>
</body>

</html>
