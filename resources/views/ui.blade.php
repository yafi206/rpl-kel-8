<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single UI Composite</title>
    <style>
        :root {
            --bg: #020b10;
            --panel: #58b0d3;
            --panel-2: #4ca7cf;
            --panel-3: #2f6d8c;
            --dark: #091e2a;
            --field: rgba(255,255,255,0.14);
            --field-strong: rgba(255,255,255,0.2);
            --white: #eefbff;
            --muted: rgba(255,255,255,0.72);
            --green: #67d67f;
            --green-deep: #42b868;
            --shadow: 0 18px 36px rgba(0, 0, 0, 0.26);
        }

        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            min-height: 100%;
            background: var(--bg);
            color: var(--white);
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            padding: 24px;
        }

        .layout {
            max-width: 1180px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, minmax(220px, 1fr));
            gap: 22px;
        }

        .card {
            position: relative;
            min-height: 285px;
            padding: 18px 18px 20px;
            border-radius: 22px;
            background: linear-gradient(180deg, var(--panel), var(--panel-2));
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card.tall { min-height: 360px; }
        .card.compact { min-height: 260px; }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 30px;
            padding: 0 14px;
            border-radius: 12px;
            background: rgba(9, 38, 52, 0.28);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--white);
        }

        .menu {
            width: 28px;
            height: 20px;
            display: grid;
            align-content: center;
            gap: 4px;
        }

        .menu span {
            display: block;
            height: 3px;
            border-radius: 999px;
            background: rgba(255,255,255,0.96);
        }

        .field {
            display: grid;
            gap: 8px;
            margin-bottom: 12px;
        }

        .label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: var(--muted);
            text-transform: uppercase;
        }

        .input {
            width: 100%;
            min-height: 34px;
            border: 0;
            border-radius: 10px;
            padding: 9px 12px;
            background: rgba(255,255,255,0.16);
            color: var(--white);
            outline: none;
        }

        .input::placeholder { color: rgba(255,255,255,0.7); }

        .primary {
            width: 100%;
            min-height: 42px;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(180deg, rgba(16,36,48,0.92), rgba(12,22,30,0.98));
            color: var(--white);
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            cursor: pointer;
        }

        .link {
            display: block;
            margin-top: 12px;
            text-align: center;
            font-size: 0.72rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }

        .success {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 100%;
            gap: 14px;
        }

        .check {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--green);
            display: grid;
            place-items: center;
            font-size: 3rem;
            font-weight: 900;
            color: var(--white);
            box-shadow: inset 0 0 0 10px rgba(255,255,255,0.05);
        }

        .success h3 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .small {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.75);
        }

        .stack {
            display: grid;
            gap: 12px;
            margin-top: 6px;
        }

        .rows {
            display: grid;
            gap: 10px;
            margin-top: 12px;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            min-height: 34px;
            padding: 0 10px 0 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.1);
            font-size: 0.76rem;
        }

        .row .arrow {
            font-size: 1.2rem;
            font-weight: 700;
            color: rgba(255,255,255,0.9);
        }

        .stat-box {
            display: grid;
            gap: 6px;
            padding: 12px 10px;
            border-radius: 12px;
            background: rgba(255,255,255,0.08);
        }

        .stat-box strong {
            font-size: 1.6rem;
            font-weight: 900;
        }

        .status-bar {
            width: 100%;
            height: 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.2);
            overflow: hidden;
            position: relative;
        }

        .status-bar::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 55%;
            background: rgba(11, 38, 52, 0.8);
        }

        .upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 18px;
            height: 100%;
            min-height: 200px;
            border-radius: 16px;
            background: rgba(11, 40, 64, 0.2);
            border: 2px solid rgba(255,255,255,0.12);
        }

        .upload .arrow {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: rgba(255,255,255,0.08);
            font-size: 2.6rem;
            font-weight: 900;
        }

        .list-grid {
            display: grid;
            gap: 10px;
            margin-top: 14px;
        }

        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.08);
            font-size: 0.8rem;
        }

        .list-item strong { font-size: 1rem; }

        .muted { color: var(--muted); }

        @media (max-width: 980px) {
            .layout { grid-template-columns: repeat(2, minmax(220px, 1fr)); }
        }

        @media (max-width: 640px) {
            body { padding: 16px; }
            .layout { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <div class="card">
            <div class="topbar">
                <span class="pill">Register</span>
            </div>
            <div class="stack">
                <div class="field">
                    <label class="label">Nama</label>
                    <input class="input" type="text" placeholder="Nama">
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <input class="input" type="password" placeholder="Password">
                </div>
                <button class="primary">Sign Up</button>
            </div>
            <a class="link" href="#">Kembali ke Login Page</a>
        </div>

        <div class="card">
            <div class="success">
                <div class="check">✓</div>
                <h3>Successful</h3>
                <div class="small">Kembali ke Login Page</div>
            </div>
        </div>

        <div class="card compact">
            <div class="topbar">
                <span class="pill">Login</span>
                <div class="menu"><span></span><span></span><span></span></div>
            </div>
            <div class="stack">
                <div class="field">
                    <label class="label">Nama</label>
                    <input class="input" type="text" placeholder="Nama">
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <input class="input" type="password" placeholder="Password">
                </div>
                <button class="primary">Login</button>
            </div>
            <a class="link" href="#">Register</a>
        </div>

        <div class="card compact">
            <div class="topbar">
                <span class="pill">Laporan</span>
                <div class="menu"><span></span><span></span><span></span></div>
            </div>
            <div class="rows">
                <div class="row"><span>Nama Barang</span><span class="arrow">›</span></div>
                <div class="row"><span>Nama Barang</span><span class="arrow">›</span></div>
                <div class="row"><span>Nama Barang</span><span class="arrow">›</span></div>
                <div class="row"><span>Nama Barang</span><span class="arrow">›</span></div>
                <div class="row"><span>Nama Barang</span><span class="arrow">›</span></div>
            </div>
        </div>

        <div class="card tall">
            <div class="topbar">
                <span class="pill">Laporan</span>
                <div class="menu"><span></span><span></span><span></span></div>
            </div>

            <div class="field">
                <label class="label">Nama Barang</label>
                <input class="input" type="text" placeholder="Nama Barang">
            </div>
            <div class="field">
                <label class="label">Tanggal Laporan</label>
                <input class="input" type="text" placeholder="Tanggal Laporan">
            </div>
            <div class="field">
                <label class="label">Deskripsi Kerusakan</label>
                <input class="input" type="text" placeholder="Deskripsi Kerusakan">
            </div>
            <div class="field">
                <label class="label">Lokasi Barang</label>
                <input class="input" type="text" placeholder="Lokasi Barang">
            </div>
            <div class="field">
                <label class="label">Status Penanganan</label>
                <div class="status-bar"></div>
            </div>
        </div>

        <div class="card compact">
            <div class="topbar">
                <span class="pill">Laporan</span>
                <div class="menu"><span></span><span></span><span></span></div>
            </div>
            <div class="upload">
                <div class="arrow">↑</div>
                <button class="primary" style="width:70%; letter-spacing:0.08em;">Kirim</button>
            </div>
        </div>

        <div class="card compact">
            <div class="topbar">
                <span class="pill">Dashboard</span>
                <div class="menu"><span></span><span></span><span></span></div>
            </div>
            <div class="list-grid">
                <div class="list-item"><span>Peran</span><strong>Admin</strong></div>
                <div class="list-item"><span>Email</span><strong>user@campus</strong></div>
                <div class="list-item"><span>Dept</span><strong>Teknik</strong></div>
                <div class="list-item"><span>Telepon</span><strong>0812</strong></div>
            </div>
        </div>

        <div class="card compact">
            <div class="topbar">
                <span class="pill">Notifikasi</span>
                <div class="menu"><span></span><span></span><span></span></div>
            </div>
            <div class="stat-box"><span class="muted">Laporan</span><strong>28</strong></div>
            <div class="stat-box" style="margin-top: 10px;"><span class="muted">Menunggu</span><strong>10</strong></div>
            <div class="stat-box" style="margin-top: 10px;"><span class="muted">Selesai</span><strong>18</strong></div>
        </div>

        <div class="card compact">
            <div class="topbar">
                <span class="pill">Success</span>
            </div>
            <div class="success" style="padding-top: 8px;">
                <div class="check" style="width:82px; height:82px; font-size:2.2rem;">✓</div>
                <h3 style="font-size:1.3rem; letter-spacing:0.04em;">Home</h3>
                <div class="small">Silakan klik lanjut</div>
                <button class="primary" style="width:72%; margin-top: 6px; letter-spacing:0.08em;">Next</button>
            </div>
        </div>
    </div>
</body>
</html>
