<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certificate of Achievement - {{ $certificate->certificate_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600&family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: 'Cormorant Garamond', serif;
            background: #ffffff;
            color: #2c3e50;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            width: 297mm;
            height: 210mm;
        }

        .certificate {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #ffffff;
            overflow: hidden;
        }

        /* Premium Border Design */
        .border-outer {
            position: absolute;
            inset: 10mm;
            border: 2px solid #2563eb;
            background: #fefefe;
        }

        .border-inner {
            position: absolute;
            inset: 15mm;
            border: 1px solid #2563eb;
        }

        /* Corner Ornaments */
        .corner-ornament {
            position: absolute;
            width: 30mm;
            height: 30mm;
            border: 2px solid #2563eb;
        }

        .corner-ornament.top-left {
            top: 5mm;
            left: 5mm;
            border-right: none;
            border-bottom: none;
        }

        .corner-ornament.top-right {
            top: 5mm;
            right: 5mm;
            border-left: none;
            border-bottom: none;
        }

        .corner-ornament.bottom-left {
            bottom: 5mm;
            left: 5mm;
            border-right: none;
            border-top: none;
        }

        .corner-ornament.bottom-right {
            bottom: 5mm;
            right: 5mm;
            border-left: none;
            border-top: none;
        }


        /* Content Container */
        .content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 25mm 45mm 35mm;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 8mm;
        }

        .institute-name {
            font-family: 'Cinzel', serif;
            font-size: 24pt;
            font-weight: 600;
            color: #1e40af;
            letter-spacing: 3px;
            margin-bottom: 4mm;
        }

        .certificate-title {
            font-family: 'Cinzel', serif;
            font-size: 32pt;
            font-weight: 500;
            color: #059669;
            letter-spacing: 4px;
            margin-bottom: 2mm;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .certificate-subtitle {
            font-family: 'Montserrat', sans-serif;
            font-size: 11pt;
            font-weight: 300;
            color: #64748b;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 1mm 0 0 0;
        }

        /* Main Content */
        .main-content {
            text-align: center;
            margin-bottom: 10mm;
        }

        .presented-to {
            font-family: 'Cormorant Garamond', serif;
            font-size: 16pt;
            font-weight: 400;
            color: #475569;
            font-style: italic;
            margin: 0 0 1mm 0;
        }

        .recipient-name {
            font-family: 'Cinzel', serif;
            font-size: 28pt;
            font-weight: 600;
            color: #1e40af;
            margin: 3mm 0 6mm 0;
            padding-bottom: 3mm;
            border-bottom: 2px solid #059669;
            display: inline-block;
            min-width: 150mm;
        }

        .achievement-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 13pt;
            color: #475569;
            line-height: 1.5;
            max-width: 180mm;
            margin: 6mm auto 8mm;
        }

        .course-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 16pt;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 4mm;
        }

        .course-details {
            font-family: 'Cormorant Garamond', serif;
            font-size: 12pt;
            color: #64748b;
            line-height: 1.5;
            max-width: 180mm;
            margin: 0 auto;
        }

        /* Footer Section */
        .footer {
            position: absolute;
            bottom: 28mm;
            left: 50mm;
            right: 50mm;
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
        }

        .signature-block {
            text-align: center;
            min-width: 80mm;
        }

        .signature-line {
            width: 60mm;
            height: 0.5pt;
            background: #94a3b8;
            margin: 0 auto 2mm;
        }

        .signature-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 10pt;
            font-weight: 500;
            color: #1e40af;
            margin-bottom: 1mm;
        }

        .signature-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 9pt;
            color: #64748b;
        }

        .certificate-seal {
            width: 40mm;
            height: 40mm;
            border-radius: 50%;
            border: 3px double #d4af37;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cinzel', serif;
            font-size: 14pt;
            font-weight: 600;
            color: #d4af37;
            background: rgba(212, 175, 55, 0.05);
        }

        /* Certificate Metadata */
        .metadata {
            position: absolute;
            bottom: 18mm;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 8pt;
            color: #94a3b8;
            display: flex;
            justify-content: center;
            gap: 8mm;
            align-items: center;
        }

        .metadata-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-width: 40mm;
        }

        .metadata-label {
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1mm;
        }

        .metadata-value {
            font-weight: 600;
            color: #64748b;
        }

        /* Verification Link */
        .verification-link {
            position: absolute;
            bottom: 12mm;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 7pt;
            color: #94a3b8;
            font-style: italic;
            opacity: 0.8;
        }

        /* Print Styles */
        @media print {
            body, .certificate {
                width: 100%;
                height: 100vh;
            }
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            .certificate {
                width: 100%;
                height: auto;
                min-height: 100vh;
            }
            
            .content {
                padding: 20mm;
            }
            
            .certificate-title {
                font-size: 24pt;
            }
            
            .recipient-name {
                font-size: 22pt;
                min-width: auto;
            }
            
            .footer {
                position: relative;
                flex-direction: column;
                gap: 10mm;
                margin-top: 20mm;
            }
        }
    </style>
</head>
<body>
    <div class="certificate">
        <!-- Decorative Elements -->
        <div class="corner-ornament top-left"></div>
        <div class="corner-ornament top-right"></div>
        <div class="corner-ornament bottom-left"></div>
        <div class="corner-ornament bottom-right"></div>
        
        <div class="border-outer"></div>
        <div class="border-inner"></div>
        
        
        <!-- Certificate Content -->
        <div class="content">
            <!-- Header -->
            <div class="header">
                <h1 class="institute-name">IQRA ONLINE ACADEMY</h1>
                <h2 class="certificate-title">Certificate of Achievement</h2>
                <p class="certificate-subtitle">This certifies that</p>
            </div>
            
            <!-- Main Content -->
            <div class="main-content">
                <p class="presented-to">is hereby awarded to</p>
                <h3 class="recipient-name">{{ $certificate->student_name }}</h3>
                
                <p class="achievement-text">
                    for successfully completing the course requirements and demonstrating 
                    exceptional dedication to Islamic education on 
                    <strong>{{ $certificate->completion_date->format('d F Y') }}</strong>
                </p>
                
                <h4 class="course-name">{{ $certificate->course_title }}</h4>
                
                @if($certificate->course_description)
                    <p class="course-details">{{ Str::limit($certificate->course_description, 150) }}</p>
                @endif
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="signature-block">
                <div class="signature-line"></div>
                <p class="signature-name">Md Saeedul Mostafa</p>
                <p class="signature-title">Director</p>
            </div>
            
            <div class="signature-block">
                <div class="signature-line"></div>
                <p class="signature-name">{{ $certificate->instructors[0] ?? 'Course Instructor' }}</p>
                <p class="signature-title">Lead Instructor</p>
            </div>
        </div>
        
        <!-- Metadata -->
        <div class="metadata">
            <div class="metadata-item">
                <div class="metadata-label">Certificate No</div>
                <div class="metadata-value">{{ $certificate->certificate_number }}</div>
            </div>
            <div class="metadata-item">
                <div class="metadata-label">Issue Date</div>
                <div class="metadata-value">{{ $certificate->issue_date->format('d F Y') }}</div>
            </div>
            <div class="metadata-item">
                <div class="metadata-label">Verification</div>
                <div class="metadata-value">{{ $certificate->verification_code }}</div>
            </div>
        </div>
        
        <!-- Verification Link -->
        <div class="verification-link">
            Verify this certificate at: {{ config('app.url') }}/certificates/verify
        </div>
    </div>
</body>
</html>