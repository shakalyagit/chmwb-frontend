<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-align: center;
            padding: 30px 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background-color: #ffffff;
            border-radius: 50%;
            padding: 10px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .content {
            padding: 30px 25px;
        }

        .success-badge {
            background-color: #10b981;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .reference-id {
            background-color: #f3f4f6;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .reference-id strong {
            color: #667eea;
            font-size: 18px;
        }

        .message-box {
            background-color: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }

        .info-section {
            background-color: #eff6ff;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }

        .info-section h3 {
            color: #1e40af;
            margin-top: 0;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
        }

        .address {
            background-color: #f9fafb;
            border-left: 3px solid #3b82f6;
            padding: 15px;
            margin: 15px 0;
            font-weight: 500;
        }

        .footer {
            background-color: #1f2937;
            color: #d1d5db;
            text-align: center;
            padding: 20px;
            font-size: 12px;
        }

        .footer a {
            color: #60a5fa;
            text-decoration: none;
        }

        ul {
            padding-left: 20px;
        }

        ul li {
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header" style="text-align: center;">
            <div class="logo" style="text-align: center">
                <img src="{{ asset('images/Screenshot_192.png') }}" style="border-radius: 50%; text-align:center"
                    alt="Logo">
            </div>
            <h1>Council of Homoeopathic Medicine</h1>
            <p style="margin: 5px 0 0 0;">West Bengal</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div style="text-align: center;">
                <span class="success-badge">✓ Application Submitted Successfully</span>
            </div>

            <p style="font-size: 16px; color: #1f2937;">
                Dear <strong>{{ $detail->name ?? 'Applicant' }}</strong>,
            </p>

            <div class="reference-id">
                <strong>Reference ID: {{ $applicationHead->reference_id }}</strong>
            </div>

            <div class="message-box">
                <p style="margin: 0; line-height: 1.8;">
                    <strong>Welcome to Council of Homoeopathic Medicine, West Bengal.</strong><br><br>
                    Your application has been successfully submitted & is subject to verification.<br><br>

                </p>
            </div>

            {{-- <div class="address">
                <strong>📍 Address:</strong><br>
                The Registrar,<br>
                Council of Homoeopathic Medicine, West Bengal,<br>
                9/1B, Mahatma Gandhi Road (1st Floor),<br>
                Kolkata - 700 009.
            </div> --}}

            <div class="info-section">
                <h3>Application Details</h3>
                <p><strong>Email:</strong> {{ $detail->email ?? 'N/A' }}</p>
                <p><strong>Mobile:</strong> {{ $detail->mobile ?? 'N/A' }}</p>
                <p><strong>Application Type:</strong></p>
                <ul>
                    @foreach ($reasons as $reason)
                        <li>{{ ucwords(str_replace('-', ' ', $reason->reason_id)) }}</li>
                    @endforeach
                </ul>
            </div>

            <p style="background-color: #ecfdf5; border-left: 3px solid #10b981; padding: 12px; margin: 20px 0;">
                <strong>📎 Note:</strong> Please find your application form attached as a PDF document.
            </p>

            <p style="margin-top: 25px; color: #4b5563;">
                If you have any questions, please feel free to contact us.
            </p>

            <p style="margin-top: 20px;">
                Best Regards,<br>
                <strong>Council of Homoeopathic Medicine, West Bengal</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0;">
                © {{ date('Y') }} Council of Homoeopathic Medicine, West Bengal. All rights reserved.
            </p>
            <p style="margin: 10px 0 0 0;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>

</html>
