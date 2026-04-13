<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - WeTest CBT</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: #e2e8f0;
            padding: 1.5rem;
        }
        .container {
            max-width: 520px;
            width: 100%;
            text-align: center;
        }
        .shield-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 40px rgba(239, 68, 68, 0.3);
            animation: pulse-glow 2s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 20px 40px rgba(239, 68, 68, 0.3); }
            50% { box-shadow: 0 20px 60px rgba(239, 68, 68, 0.5); }
        }
        .shield-icon svg {
            width: 48px;
            height: 48px;
            color: white;
        }
        h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #f1f5f9;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }
        .subtitle {
            font-size: 0.95rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .info-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }
        .info-card h3 {
            font-size: 0.85rem;
            font-weight: 700;
            color: #e2e8f0;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .steps {
            list-style: none;
            counter-reset: steps;
        }
        .steps li {
            counter-increment: steps;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.85rem;
            color: #cbd5e1;
            margin-bottom: 0.75rem;
            line-height: 1.5;
        }
        .steps li:last-child { margin-bottom: 0; }
        .steps li::before {
            content: counter(steps);
            min-width: 1.5rem;
            height: 1.5rem;
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 0.1rem;
        }
        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.75rem;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        }
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(16, 185, 129, 0.4);
        }
        .error-code {
            margin-top: 2rem;
            font-size: 0.75rem;
            color: #475569;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="shield-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <h1>Akses Ditolak</h1>
        <p class="subtitle">
            Ujian ini hanya dapat diakses melalui <strong>Safe Exam Browser (SEB)</strong>. 
            Browser yang Anda gunakan saat ini tidak dikenali oleh sistem keamanan ujian.
        </p>

        <div class="info-card">
            <h3>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Cara mengakses ujian:
            </h3>
            <ol class="steps">
                <li>Pastikan <strong>Safe Exam Browser</strong> sudah terinstall di komputer Anda</li>
                <li>Buka file konfigurasi SEB (<strong>.seb</strong>) yang diberikan oleh guru/admin</li>
                <li>SEB akan otomatis membuka halaman ujian dalam mode aman</li>
                <li>Login menggunakan NISN dan password Anda seperti biasa</li>
            </ol>
        </div>

        <a href="https://safeexambrowser.org/download_en.html" target="_blank" class="btn-download">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download Safe Exam Browser
        </a>

        <p class="error-code">HTTP 403 — SEB_VERIFICATION_FAILED</p>
    </div>
</body>
</html>
