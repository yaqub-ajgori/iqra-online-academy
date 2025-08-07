<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Achievement</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0mm;
            padding: 0mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial Unicode MS', Arial, sans-serif;
            background: #ffffff;
            color: #2d3748;
            width: 297mm;
            height: 210mm;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.4;
            overflow: hidden;
        }

        .certificate-container {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #ffffff;
            border: 8px solid transparent;
            background-image: 
                linear-gradient(white, white),
                linear-gradient(135deg, #047857 0%, #1e40af 25%, #d97706 50%, #1e40af 75%, #047857 100%);
            background-origin: padding-box, border-box;
            background-clip: padding-box, border-box;
            margin: 0;
            padding: 15mm;
            box-shadow: 
                0 4px 15px rgba(0, 0, 0, 0.1),
                inset 0 0 0 1px rgba(4, 120, 87, 0.2);
        }

        /* Modern Border Details - Subtle corner markers */
        .border-marker {
            position: absolute;
            width: 12mm;
            height: 1mm;
            background: rgba(4, 120, 87, 0.3);
            z-index: 1;
        }

        .border-marker.top-left {
            top: 12mm;
            left: 12mm;
            border-radius: 0.5mm;
        }

        .border-marker.top-right {
            top: 12mm;
            right: 12mm;
            border-radius: 0.5mm;
        }

        .border-marker.bottom-left {
            bottom: 12mm;
            left: 12mm;
            border-radius: 0.5mm;
        }

        .border-marker.bottom-right {
            bottom: 12mm;
            right: 12mm;
            border-radius: 0.5mm;
        }

        /* Islamic pattern overlay */
        .islamic-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.05;
            background-image: 
                radial-gradient(circle at 25% 25%, #047857 1.5px, transparent 1.5px),
                radial-gradient(circle at 75% 75%, #1e40af 1.5px, transparent 1.5px),
                linear-gradient(45deg, transparent 48%, rgba(217, 119, 6, 0.02) 49%, rgba(217, 119, 6, 0.02) 51%, transparent 52%);
            background-size: 35px 35px, 35px 35px, 70px 70px;
            background-position: 0 0, 17.5px 17.5px, 0 0;
        }

        /* Islamic geometric pattern */
        .geometric-pattern {
            position: absolute;
            top: 50%;
            left: 20mm;
            right: 20mm;
            height: 3px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(4, 120, 87, 0.3) 10%, 
                rgba(30, 64, 175, 0.4) 25%, 
                rgba(217, 119, 6, 0.6) 40%, 
                rgba(217, 119, 6, 0.8) 50%, 
                rgba(217, 119, 6, 0.6) 60%, 
                rgba(30, 64, 175, 0.4) 75%, 
                rgba(4, 120, 87, 0.3) 90%, 
                transparent 100%);
            transform: translateY(-50%);
            border-radius: 2px;
            box-shadow: 
                0 1px 3px rgba(0, 0, 0, 0.1),
                0 -1px 1px rgba(255, 255, 255, 0.5);
        }

        /* HEADER SECTION */
        .header-section {
            text-align: center;
            margin-bottom: 10mm;
        }

        .bismillah {
            font-size: 14px;
            color: #1e40af;
            margin-bottom: 4mm;
            font-weight: 900;
            letter-spacing: 1px;
            text-shadow: 
                0 1px 2px rgba(30, 64, 175, 0.4),
                0 0 10px rgba(30, 64, 175, 0.1);
            filter: drop-shadow(0 1px 1px rgba(0, 0, 0, 0.1));
        }

        .institute-name {
            font-size: 24px;
            font-weight: 900;
            color: #1e3a8a;
            margin-bottom: 3mm;
            letter-spacing: 3px;
            text-transform: uppercase;
            text-shadow: 
                0 2px 4px rgba(30, 58, 138, 0.3),
                0 0 20px rgba(30, 58, 138, 0.1);
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #1e3a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.1));
        }

        .certificate-title {
            font-size: 28px;
            font-weight: 900;
            color: #047857;
            letter-spacing: 2px;
            text-shadow: 
                0 2px 4px rgba(4, 120, 87, 0.3),
                0 0 15px rgba(4, 120, 87, 0.1);
            background: linear-gradient(135deg, #047857 0%, #059669 50%, #047857 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.1));
        }

        /* MAIN CONTENT SECTION */
        .main-content {
            text-align: center;
            margin-bottom: 10mm;
        }

        .awarded-text {
            font-size: 12px;
            color: #374151;
            margin-bottom: 4mm;
            font-style: italic;
            font-weight: 500;
        }

        .recipient-name {
            font-size: 30px;
            font-weight: 900;
            color: #1e3a8a;
            margin: 4mm 0 6mm 0;
            padding: 6mm 15mm;
            border-top: 4px solid;
            border-bottom: 4px solid;
            border-image: linear-gradient(90deg, transparent 0%, #d97706 20%, #b45309 50%, #d97706 80%, transparent 100%) 1;
            background: linear-gradient(135deg, 
                transparent 0%, 
                rgba(217, 119, 6, 0.05) 15%, 
                rgba(4, 120, 87, 0.05) 30%, 
                rgba(30, 64, 175, 0.03) 50%, 
                rgba(4, 120, 87, 0.05) 70%, 
                rgba(217, 119, 6, 0.05) 85%, 
                transparent 100%);
            display: inline-block;
            min-width: 160mm;
            text-shadow: 
                0 2px 4px rgba(30, 58, 138, 0.3),
                0 0 20px rgba(30, 58, 138, 0.08);
            border-radius: 6px;
            box-shadow: 
                0 4px 12px rgba(0, 0, 0, 0.1),
                0 1px 3px rgba(0, 0, 0, 0.1),
                inset 0 1px 2px rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #1e3a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }

        .recipient-name::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                transparent 0%, 
                rgba(217, 119, 6, 0.08) 15%, 
                rgba(4, 120, 87, 0.08) 30%, 
                rgba(30, 64, 175, 0.05) 50%, 
                rgba(4, 120, 87, 0.08) 70%, 
                rgba(217, 119, 6, 0.08) 85%, 
                transparent 100%);
            border-radius: 6px;
            z-index: -1;
        }

        .achievement-description {
            font-size: 11px;
            color: #374151;
            line-height: 1.4;
            margin: 4mm auto 6mm auto;
            max-width: 180mm;
            font-weight: 400;
        }

        .course-name {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
            border-left: 5px solid #d97706;
            padding-left: 6mm;
            display: inline-block;
            max-width: 180mm;
            text-shadow: 0 1px 2px rgba(30, 58, 138, 0.2);
        }

        .completion-date {
            font-weight: bold;
            color: #047857;
            font-size: 11px;
        }

        /* FOOTER SECTION */
        .footer-section {
            margin-top: 8mm;
        }

        /* Metadata */
        .metadata-section {
            background: linear-gradient(135deg, rgba(30, 64, 175, 0.12) 0%, rgba(4, 120, 87, 0.12) 50%, rgba(217, 119, 6, 0.08) 100%);
            border: 1px solid rgba(4, 120, 87, 0.2);
            border-radius: 3mm;
            padding: 2mm;
            margin-bottom: 5mm;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .metadata-table {
            width: 100%;
            border-collapse: collapse;
        }

        .metadata-cell {
            text-align: center;
            padding: 0.5mm 1mm;
            font-size: 8px;
        }

        .metadata-label {
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            font-size: 7px;
            display: block;
            margin-bottom: 0.5mm;
        }

        .metadata-value {
            color: #374151;
            font-size: 8px;
            font-weight: 600;
        }

        /* Signatures */
        .signatures-container {
            margin-bottom: 4mm;
        }

        .signatures-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-cell {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 5mm;
        }

        .signature-line {
            width: 50mm;
            height: 2px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(217, 119, 6, 0.6) 15%, 
                rgba(4, 120, 87, 0.8) 35%, 
                rgba(30, 64, 175, 0.6) 50%, 
                rgba(4, 120, 87, 0.8) 65%, 
                rgba(217, 119, 6, 0.6) 85%, 
                transparent 100%);
            margin: 0 auto 2mm auto;
            border-radius: 2px;
            box-shadow: 
                0 1px 2px rgba(0, 0, 0, 0.1),
                0 -0.5px 1px rgba(255, 255, 255, 0.3);
        }

        .signature-name {
            font-size: 10px;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 0.5mm;
            line-height: 1.1;
            text-shadow: 
                0 1px 2px rgba(30, 58, 138, 0.3),
                0 0 8px rgba(30, 58, 138, 0.1);
            filter: drop-shadow(0 0.5px 0.5px rgba(0, 0, 0, 0.1));
        }

        .signature-title {
            font-size: 8px;
            color: #6b7280;
            font-style: italic;
            line-height: 1.1;
            font-weight: 600;
            text-shadow: 0 0.5px 1px rgba(107, 114, 128, 0.3);
        }

        /* Verification at the very bottom */
        .verification-section {
            text-align: center;
            padding: 1.5mm;
            background: linear-gradient(135deg, rgba(4, 120, 87, 0.08) 0%, rgba(30, 64, 175, 0.08) 100%);
            border: 1px solid rgba(4, 120, 87, 0.15);
            border-radius: 2mm;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .verification-text {
            font-size: 7px;
            color: #4b5563;
            line-height: 1.1;
            font-weight: 500;
        }

        .verification-url {
            font-weight: bold;
            color: #1e3a8a;
        }

        /* Print optimization */
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .certificate-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Modern Border Markers -->
        <div class="border-marker top-left"></div>
        <div class="border-marker top-right"></div>
        <div class="border-marker bottom-left"></div>
        <div class="border-marker bottom-right"></div>
        
        <!-- Islamic pattern overlay -->
        <div class="islamic-pattern"></div>
        
        <!-- Geometric pattern -->
        <div class="geometric-pattern"></div>
            
            <!-- HEADER SECTION -->
            <div class="header-section">
                <div class="bismillah">بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْمِ</div>
                <div class="institute-name">IQRA ONLINE ACADEMY</div>
                <div class="certificate-title">Certificate of Achievement</div>
            </div>
            
            <!-- MAIN CONTENT SECTION -->
            <div class="main-content">
                <div class="awarded-text">This certificate is proudly presented to</div>
                
                <div class="recipient-name">{{ $certificate->student_name }}</div>
                
                <div class="achievement-description">
                    in recognition of successfully completing all course requirements and demonstrating 
                    exceptional commitment to Islamic education and learning. Completed on 
                    <span class="completion-date">{{ $certificate->completion_date->format('d F Y') }}</span>
                </div>
                
                <div class="course-name">{{ $certificate->course_title }}</div>
            </div>
            
            <!-- FOOTER SECTION -->
            <div class="footer-section">
                <!-- Metadata -->
                <div class="metadata-section">
                    <table class="metadata-table">
                        <tr>
                            <td class="metadata-cell">
                                <span class="metadata-label">Certificate Number</span>
                                <span class="metadata-value">{{ $certificate->certificate_number }}</span>
                            </td>
                            <td class="metadata-cell">
                                <span class="metadata-label">Issue Date</span>
                                <span class="metadata-value">{{ $certificate->issue_date->format('d F Y') }}</span>
                            </td>
                            <td class="metadata-cell">
                                <span class="metadata-label">Verification Code</span>
                                <span class="metadata-value">{{ $certificate->verification_code }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Signatures -->
                <div class="signatures-container">
                    <table class="signatures-table">
                        <tr>
                            <td class="signature-cell">
                                <div class="signature-line"></div>
                                <div class="signature-name">Md Saeedul Mostafa</div>
                                <div class="signature-title">Director & Founder</div>
                            </td>
                            <td class="signature-cell">
                                <div class="signature-line"></div>
                                <div class="signature-name">Dr. Muhammad Ibrahim Al-Hafiz</div>
                                <div class="signature-title">Lead Instructor</div>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Verification at the very bottom -->
                <div class="verification-section">
                    <div class="verification-text">
                        Verify authenticity at: <span class="verification-url">{{ config('app.url') }}/certificates/verify</span>
                    </div>
                </div>
            </div>
    </div>
</body>
</html>