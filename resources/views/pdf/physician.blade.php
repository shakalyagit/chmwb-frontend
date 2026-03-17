<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Physician Registration Details</title>
    <style>
        /* PDF Page Setup */
        @page {
            size: A4;
            margin: 0;
        }

        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 40px;
            /* Dompdf native font that matches Tailwind sans-stack */
            font-family: 'Helvetica', sans-serif;
            line-height: 1.5;
        }

        .cert-container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            /* Tailwind blue-900 equivalent */
            border: 8px double #1e3a8a;
        }

        .banner-container {
            width: 100%;
            border-bottom: 4px solid #1e3a8a;
        }

        .banner-img {
            width: 100%;
            display: block;
        }

        .content {
            padding: 30px;
        }

        .header-border {
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 15px;
            margin-bottom: 25px;
            text-align: center;
        }

        .h1-title {
            font-size: 22px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            /* tracking-tight */
            letter-spacing: -1px;
        }

        .subtitle {
            font-size: 12px;
            font-weight: normal;
            color: #4b5563;
            margin: 0;
        }

        .grid-table {
            width: 100%;
            border-collapse: collapse;
        }

        .label {
            font-size: 10px;
            font-weight: bold;
            color: #1e40af;
            /* blue-800 */
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .value {
            font-size: 17px;
            font-weight: bold;
            color: #111827;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 5px;
            margin-bottom: 18px;
        }

        .value-name {
            font-size: 20px;
            text-transform: uppercase;
        }

        .status-active {
            color: #15803d;
            font-weight: bold;
        }

        .footer-wrap {
            margin-top: 50px;
        }

        .footer-text-tiny {
            font-size: 9px;
            color: #6b7280;
            margin: 0;
        }

        .system-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #f3f4f6;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    @foreach($practitioners as $doctor)
    <div class="cert-container @if(!$loop->last) page-break @endif">

        <div class="banner-container">
            <img src="https://app.chmwb.org/assets/main-banner-99.jpeg" class="banner-img" alt="Banner">
        </div>

        <div class="content">
            <div class="header-border">
                <h1 class="h1-title">PHYSICIAN REGISTRATION DETAILS</h1>
                <p class="subtitle">
                    Work of this site in progress and for any query regarding this, please contact/visit Council of Homoeopathic Medicine, West Bengal
                </p>
            </div>

            <table class="grid-table">
                <tr>
                    <td width="50%" style="padding-right: 15px; vertical-align: top;">
                        <div class="label">Registration No</div>
                        <div class="value">{{ $doctor->registration_no ?? 'N/A' }}</div>
                    </td>
                    <td width="50%" style="vertical-align: top;">
                        <div class="label">Date of Registration</div>
                        <div class="value">{{ date('d-m-Y', strtotime($doctor->registration_date)) ?? 'N/A' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="label">Name of the Physician</div>
                        <div class="value value-name">{{ $doctor->name ?? 'N/A' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="label">Father / Husband Name</div>
                        <div class="value" style="font-weight: normal;">{{ $doctor->fathers_name ?? 'N/A' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="label">Registered Address</div>
                        <div class="value" style="font-weight: normal; font-size: 14px; line-height: 1.3;">
                            {{ $doctor->address ?? 'N/A' }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="padding-right: 15px; vertical-align: top;">
                        <div class="label">Qualification</div>
                        <div class="value">{{ $doctor->qualification ?? 'N/A' }}</div>
                    </td>
                   
                </tr>
            </table>

            <div class="footer-wrap">
                <p class="footer-text-tiny">Computer Generated Document</p>
                <p class="footer-text-tiny" style="color: #1f2937;">Date Generated: {{ date('d/m/Y H:i:s') }}</p>
            </div>

            
        </div>
    </div>
    @endforeach

</body>

</html>