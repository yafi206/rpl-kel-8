<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Layanan Pelaporan Fasilitas Rusak')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #020d14;
            --panel: #3aa9cf;
            --text: #f3fbff;
            --muted: rgba(255,255,255,0.72);
            --shadow: 0 18px 34px rgba(0, 0, 0, 0.28);
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', Arial, sans-serif;
        }

        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        input, select, textarea { width: 100%; }
        .container { width: min(1180px, calc(100% - 32px)); margin: 0 auto; }
        .page { padding: 28px 0 50px; }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 40px 0;
        }

        .auth-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(260px, 340px));
            gap: 22px;
        }

        .auth-card {
            background: linear-gradient(180deg, rgba(82, 184, 221, 0.98), rgba(77, 163, 200, 0.98));
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 18px 18px 20px;
            min-height: 340px;
            position: relative;
            overflow: hidden;
        }

        .auth-card--success {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 360px;
        }

        .soft-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 0 16px;
            border-radius: 12px;
            background: rgba(10, 44, 56, 0.28);
            color: var(--text);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .auth-form {
            display: grid;
            gap: 12px;
            margin-top: 14px;
        }

        .field-wrap {
            display: grid;
            gap: 8px;
        }

        .field-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: rgba(255,255,255,0.8);
        }

        .text-input {
            min-height: 38px;
            border: 0;
            border-radius: 10px;
            background: rgba(255,255,255,0.14);
            padding: 10px 12px;
            color: var(--text);
            outline: none;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.05);
        }

        .text-input::placeholder { color: rgba(255,255,255,0.72); }

        .primary-btn {
            border: 0;
            border-radius: 12px;
            background: linear-gradient(180deg, rgba(12, 34, 44, 0.94), rgba(12, 27, 36, 0.98));
            color: var(--text);
            min-height: 46px;
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 8px;
        }

        .auth-link {
            display: block;
            margin-top: 14px;
            text-align: center;
            font-size: 0.72rem;
            color: rgba(255,255,255,0.8);
        }

        .success-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(92, 227, 138, 0.96);
            display: grid;
            place-items: center;
            font-size: 3.2rem;
            font-weight: 800;
            color: var(--text);
            box-shadow: inset 0 0 0 10px rgba(255,255,255,0.06);
            margin-bottom: 18px;
        }

        .success-title {
            font-size: 1.6rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .success-meta {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.75);
        }

        .dashboard-shell {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 28px 0 60px;
        }

        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            color: var(--text);
        }

        .menu-toggle {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255,255,255,0.08);
            display: grid;
            place-items: center;
            border: 1px solid rgba(255,255,255,0.12);
        }

        .menu-toggle span {
            display: block;
            width: 18px;
            height: 2px;
            background: var(--text);
            border-radius: 999px;
            position: relative;
        }

        .menu-toggle span::before,
        .menu-toggle span::after {
            content: "";
            position: absolute;
            left: 0;
            width: 18px;
            height: 2px;
            background: var(--text);
            border-radius: 999px;
        }

        .menu-toggle span::before { top: -6px; }
        .menu-toggle span::after { top: 6px; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 18px;
        }

        .stat-box {
            background: linear-gradient(180deg, rgba(84, 185, 220, 0.96), rgba(75, 163, 200, 0.96));
            border-radius: 18px;
            padding: 18px 18px 12px;
            box-shadow: var(--shadow);
        }

        .stat-box .label {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.8);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .stat-box .value {
            margin-top: 12px;
            font-size: clamp(2rem, 3vw, 2.5rem);
            font-weight: 900;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .glass-card {
            background: linear-gradient(180deg, rgba(84, 184, 220, 0.98), rgba(74, 161, 198, 0.98));
            border-radius: 22px;
            box-shadow: var(--shadow);
            padding: 18px 18px 20px;
            min-height: 260px;
        }

        .glass-card.card-wide {
            grid-column: span 2;
        }

        .card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .card-head h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 800;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
        }

        .info-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 10px 11px;
            border-radius: 10px;
            background: rgba(255,255,255,0.08);
            font-size: 0.8rem;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 620px;
        }

        th, td {
            padding: 10px 8px;
            border-bottom: 1px solid rgba(255,255,255,0.16);
            text-align: left;
            font-size: 0.78rem;
        }

        th {
            color: rgba(255,255,255,0.75);
            font-size: 0.68rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            background: rgba(255,255,255,0.18);
        }

        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 10px;
            font-size: 0.82rem;
        }

        .status-row strong {
            font-size: 1.2rem;
        }

        .muted { color: rgba(255,255,255,0.78); }

        @media (max-width: 960px) {
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .dashboard-grid { grid-template-columns: 1fr; }
            .glass-card.card-wide { grid-column: auto; }
        }

        @media (max-width: 640px) {
            .auth-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
