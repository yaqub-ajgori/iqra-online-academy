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
            font-family: 'Georgia', 'Times New Roman', 'DejaVu Serif', serif;
            background: #ffffff;
            color: #2c2c2c;
            width: 297mm;
            height: 210mm;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.5;
            overflow: hidden;
        }

        .certificate-container {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: 
                radial-gradient(ellipse at center, #ffffff 0%, #faf9f7 100%);
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Classic multi-layer border frame */
        .border-frame {
            position: absolute;
            top: 5mm;
            left: 5mm;
            right: 5mm;
            bottom: 5mm;
            border: 3px solid #8B7355;
            background: transparent;
        }

        .inner-border {
            position: absolute;
            top: 8mm;
            left: 8mm;
            right: 8mm;
            bottom: 8mm;
            border: 1px solid #C4A57B;
        }

        .decorative-border {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 1px solid #8B7355;
            border-style: dotted;
        }

        /* Classic corner flourishes */
        .corner-flourish {
            position: absolute;
            width: 30mm;
            height: 30mm;
            border: 2px solid #C4A57B;
        }

        .corner-flourish.top-left {
            top: 12mm;
            left: 12mm;
            border-right: none;
            border-bottom: none;
        }

        .corner-flourish.top-right {
            top: 12mm;
            right: 12mm;
            border-left: none;
            border-bottom: none;
        }

        .corner-flourish.bottom-left {
            bottom: 12mm;
            left: 12mm;
            border-right: none;
            border-top: none;
        }

        .corner-flourish.bottom-right {
            bottom: 12mm;
            right: 12mm;
            border-left: none;
            border-top: none;
        }


        /* Content wrapper */
        .content-wrapper {
            position: absolute;
            top: 15mm;
            left: 15mm;
            right: 15mm;
            bottom: 15mm;
            z-index: 5;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Header Section */
        .header-section {
            text-align: center;
            padding-top: 2mm;
        }

        .bismillah {
            font-size: 14px;
            color: #5C4033;
            margin-bottom: 3mm;
            font-weight: normal;
            letter-spacing: 2px;
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }

        .institute-wrapper {
            position: relative;
            margin-bottom: 3mm;
        }

        .institute-name {
            font-size: 26px;
            font-weight: normal;
            color: #2c2c2c;
            letter-spacing: 4px;
            text-transform: uppercase;
            font-family: 'Georgia', serif;
            position: relative;
            display: inline-block;
        }

        /* Decorative underline for institute */
        .institute-underline {
            height: 1px;
            background: linear-gradient(to right, 
                transparent 0%, 
                #C4A57B 25%, 
                #8B7355 50%, 
                #C4A57B 75%, 
                transparent 100%);
            margin: 2mm auto 3mm;
            width: 60%;
        }

        .certificate-title {
            font-size: 32px;
            font-weight: normal;
            color: #8B7355;
            letter-spacing: 2px;
            font-family: 'Georgia', serif;
            text-transform: uppercase;
            margin-bottom: 1mm;
        }

        .certificate-subtitle {
            font-size: 12px;
            color: #5C4033;
            font-style: italic;
            letter-spacing: 0.5px;
        }

        /* Ornamental divider */
        .ornamental-divider {
            text-align: center;
            margin: 3mm 0;
            color: #C4A57B;
            font-size: 16px;
            letter-spacing: 10px;
        }

        /* Main Content */
        .main-content {
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2mm 0;
        }

        .presented-to {
            font-size: 14px;
            color: #5C4033;
            margin-bottom: 3mm;
            font-style: italic;
            letter-spacing: 0.5px;
        }

        .recipient-wrapper {
            position: relative;
            margin: 5mm 0;
        }

        .recipient-name {
            font-size: 30px;
            font-weight: bold;
            color: #2c2c2c;
            font-family: 'Georgia', serif;
            letter-spacing: 1px;
            position: relative;
            display: inline-block;
            padding: 0 8mm;
        }

        .recipient-underline {
            position: absolute;
            bottom: -2mm;
            left: 20%;
            right: 20%;
            height: 2px;
            background: #8B7355;
            border-bottom: 1px solid #C4A57B;
        }

        .achievement-text {
            font-size: 12px;
            color: #2c2c2c;
            line-height: 1.6;
            margin: 3mm auto;
            max-width: 190mm;
            text-align: center;
        }

        .course-section {
            margin: 4mm 0;
        }

        .course-intro {
            font-size: 12px;
            color: #5C4033;
            font-style: italic;
            margin-bottom: 2mm;
        }

        .course-name {
            font-size: 18px;
            font-weight: bold;
            color: #8B7355;
            font-family: 'Georgia', serif;
            letter-spacing: 0.5px;
            margin: 2mm 0;
        }

        .completion-date {
            font-size: 13px;
            color: #2c2c2c;
            margin-top: 3mm;
        }

        .date-value {
            font-weight: bold;
            color: #5C4033;
            border-bottom: 1px solid #C4A57B;
            padding: 0 2mm;
        }

        /* Footer Section */
        .footer-section {
            padding-top: 0;
            padding-bottom: 2mm;
        }

        /* Signatures */
        .signatures-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin: 2mm 20mm;
        }

        .signature-block {
            text-align: center;
            width: 70mm;
            position: relative;
        }

        .signature-line {
            width: 60mm;
            height: 0.5px;
            background: #5C4033;
            margin: 0 auto 1mm auto;
            position: relative;
        }

        .signature-name {
            font-size: 12px;
            font-weight: normal;
            color: #2c2c2c;
            margin-bottom: 0.5mm;
            font-family: 'Georgia', serif;
            letter-spacing: 0.5px;
        }

        .signature-title {
            font-size: 10px;
            color: #5C4033;
            font-style: italic;
            font-family: 'Georgia', serif;
        }

        /* Certificate details */
        .certificate-details {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2mm;
            padding-top: 1mm;
            border-top: 1px solid #C4A57B;
        }

        .detail-item {
            margin: 0 5mm;
            text-align: center;
        }

        .detail-label {
            font-size: 8px;
            color: #5C4033;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5mm;
        }

        .detail-value {
            font-size: 9px;
            color: #2c2c2c;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        /* Background watermark pattern */
        .watermark-pattern {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 150mm;
            height: 150mm;
            border: 15px double rgba(196, 165, 123, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }

        .watermark-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 100px;
            color: rgba(196, 165, 123, 0.04);
            font-weight: bold;
            font-family: 'Georgia', serif;
            letter-spacing: 20px;
            pointer-events: none;
        }

        /* Verification */
        .verification-footer {
            position: absolute;
            bottom: 13mm;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 10;
        }

        .verification-text {
            font-size: 7px;
            color: #8B7355;
            font-style: italic;
        }

        /* Print optimization */
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Multi-layer border frames -->
        <div class="border-frame"></div>
        <div class="inner-border"></div>
        <div class="decorative-border"></div>
        
        <!-- Corner flourishes -->
        <div class="corner-flourish top-left"></div>
        <div class="corner-flourish top-right"></div>
        <div class="corner-flourish bottom-left"></div>
        <div class="corner-flourish bottom-right"></div>
        
        <!-- Background watermark -->
        <div class="watermark-pattern"></div>
        <div class="watermark-text">IQRA</div>
        
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Header -->
            <div class="header-section">
                <div class="bismillah">بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْمِ</div>
                <div class="institute-wrapper">
                    <div class="institute-name">Iqra Online Academy</div>
                    <div class="institute-underline"></div>
                </div>
                <div class="certificate-title">Certificate of Completion</div>
                <div class="certificate-subtitle">This certificate is awarded in recognition of outstanding achievement</div>
            </div>
            
            <div class="ornamental-divider">◆ ◆ ◆</div>
            
            <!-- Main content -->
            <div class="main-content">
                <div class="presented-to">This is to certify that</div>
                
                <div class="recipient-wrapper">
                    <div class="recipient-name">{{ $certificate->student_name }}</div>
                    <div class="recipient-underline"></div>
                </div>
                
                <div class="achievement-text">
                    has successfully completed all prescribed requirements with distinction and 
                    has demonstrated exceptional dedication to Islamic education and scholarly pursuit. 
                    This achievement stands as testimony to the recipient's commitment to knowledge and learning.
                </div>
                
                <div class="course-section">
                    <div class="course-intro">in completion of the course</div>
                    <div class="course-name">{{ $certificate->course_title }}</div>
                </div>
                
                <div class="completion-date">
                    Awarded on this <span class="date-value">{{ $certificate->completion_date->format('jS') }}</span> 
                    day of <span class="date-value">{{ $certificate->completion_date->format('F') }}</span>, 
                    <span class="date-value">{{ $certificate->completion_date->format('Y') }}</span>
                </div>
            </div>
            
            <div class="ornamental-divider">◆ ◆ ◆</div>
            
            <!-- Footer -->
            <div class="footer-section">
                <!-- Signatures -->
                <div class="signatures-container">
                    <div class="signature-block">
                        <div class="signature-line"></div>
                        <div class="signature-name">Md Saeedul Mostafa</div>
                        <div class="signature-title">Director & Founder</div>
                    </div>
                    <div class="signature-block">
                        <div class="signature-line"></div>
                        <div class="signature-name">Dr. Muhammad Ibrahim Al-Hafiz</div>
                        <div class="signature-title">Chief Academic Officer</div>
                    </div>
                </div>
                
                <!-- Certificate details -->
                <div class="certificate-details">
                    <div class="detail-item">
                        <div class="detail-label">Certificate No.</div>
                        <div class="detail-value">{{ $certificate->certificate_number }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Issue Date</div>
                        <div class="detail-value">{{ $certificate->issue_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Verification Code</div>
                        <div class="detail-value">{{ $certificate->verification_code }}</div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Verification footer at bottom -->
        <div style="position: absolute; bottom: 6mm; left: 50%; transform: translateX(-50%); text-align: center; z-index: 10; background: rgba(255,255,255,0.9); padding: 1mm 3mm; border-radius: 1mm;">
            <div style="font-size: 9px; color: #5C4033; font-weight: bold;">
                Verify this certificate at: https://iqraoa.com/certificates/verify
            </div>
        </div>
    </div>
</body>
</html>