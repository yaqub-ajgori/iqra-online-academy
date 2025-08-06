<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certificate of Achievement - {{ $certificate->certificate_number }}</title>
    <style>
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
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #ffffff;
            color: #2c3e50;
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
            font-size: 14px;
        }

        .certificate {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #ffffff;
            border: 3px solid #2563eb;
            padding: 15mm;
        }

        /* Decorative Border */
        .inner-border {
            width: 100%;
            height: 100%;
            border: 1px solid #2563eb;
            padding: 15mm;
            position: relative;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 15mm;
        }

        .institute-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
            letter-spacing: 2px;
        }

        .certificate-title {
            font-size: 36px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 5mm;
            letter-spacing: 1px;
        }

        .certificate-subtitle {
            font-size: 14px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Main Content */
        .main-content {
            text-align: center;
            margin-bottom: 20mm;
        }

        .presented-to {
            font-size: 16px;
            color: #475569;
            font-style: italic;
            margin-bottom: 5mm;
        }

        .recipient-name {
            font-size: 32px;
            font-weight: bold;
            color: #1e40af;
            margin: 5mm 0 10mm 0;
            padding-bottom: 5mm;
            border-bottom: 2px solid #059669;
            display: inline-block;
            min-width: 200mm;
        }

        .achievement-text {
            font-size: 16px;
            color: #475569;
            line-height: 1.6;
            margin: 8mm auto;
            max-width: 220mm;
        }

        .course-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5mm;
        }

        .course-details {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
            max-width: 220mm;
            margin: 0 auto;
        }

        /* Footer Section */
        .footer {
            position: absolute;
            bottom: 30mm;
            left: 30mm;
            right: 30mm;
        }

        .signatures {
            display: table;
            width: 100%;
            margin-bottom: 15mm;
        }

        .signature-block {
            display: table-cell;
            text-align: center;
            width: 50%;
            vertical-align: bottom;
        }

        .signature-line {
            width: 80mm;
            height: 1px;
            background: #94a3b8;
            margin: 0 auto 3mm;
        }

        .signature-name {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 2mm;
        }

        .signature-title {
            font-size: 11px;
            color: #64748b;
        }

        /* Metadata */
        .metadata {
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }

        .metadata-row {
            margin-bottom: 3mm;
        }

        .metadata-item {
            display: inline-block;
            margin: 0 8mm;
        }

        .metadata-label {
            font-weight: bold;
            text-transform: uppercase;
        }

        .metadata-value {
            color: #64748b;
        }

        .verification-link {
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            margin-top: 5mm;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="inner-border">
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
                    <p class="course-details">{{ Str::limit($certificate->course_description, 120) }}</p>
                @endif
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <div class="signatures">
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
                    <div class="metadata-row">
                        <div class="metadata-item">
                            <span class="metadata-label">Certificate No:</span>
                            <span class="metadata-value">{{ $certificate->certificate_number }}</span>
                        </div>
                        <div class="metadata-item">
                            <span class="metadata-label">Issue Date:</span>
                            <span class="metadata-value">{{ $certificate->issue_date->format('d F Y') }}</span>
                        </div>
                        <div class="metadata-item">
                            <span class="metadata-label">Verification:</span>
                            <span class="metadata-value">{{ $certificate->verification_code }}</span>
                        </div>
                    </div>
                    
                    <div class="verification-link">
                        Verify this certificate at: {{ config('app.url') }}/certificates/verify
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>