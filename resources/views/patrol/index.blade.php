<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Patroli Keamanan Security</title>

    {{-- Muat Tailwind & JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSS Kustom Asli (Dipertahankan agar tampilan 100% sama) --}}
    <style>
        :root {
            --blue-600: #2563eb;
            --blue-500: #3b82f6;
            --blue-700: #1e40af
        }

        body {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #1f2937;
            font-size: 14px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .signature-canvas {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            cursor: crosshair;
            background: #f9fafb;
            touch-action: none;
            width: 100%;
            height: 200px;
            max-width: 100%;
            max-height: 360px;
            display: block;
        }

        .btn-primary {
            background: var(--blue-500);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
        }

        .btn-primary:hover {
            background: var(--blue-600);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.18);
        }

        .btn-primary:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 13px;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-info {
            background: #06b6d4;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-info:hover {
            background: #0891b2;
        }

        .tab-button {
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.12);
            border: none;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-right: 8px;
            color: white;
        }

        .tab-button.active {
            background: white;
            color: var(--blue-500);
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s;
            z-index: 1000;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast.error {
            background: #ef4444;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .modal-overlay.show {
            opacity: 1;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            max-width: 800px;
            width: 100%;
            max-height: 90%;
            overflow-y: auto;
            transform: scale(0.9);
            transition: transform 0.3s;
        }

        .modal-overlay.show .modal-content {
            transform: scale(1);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table thead {
            background: #f3f4f6;
        }

        .data-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 13px;
            border-bottom: 2px solid #e5e7eb;
        }

        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #1f2937;
        }

        .data-table tbody tr:hover {
            background: #f9fafb;
        }

        .filter-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-label {
            font-weight: 600;
            color: #6b7280;
            min-width: 200px;
            font-size: 14px;
        }

        .detail-value {
            color: #1f2937;
            flex: 1;
            font-size: 14px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
        }

        .pagination button {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .pagination button:hover:not(:disabled) {
            background: #f3f4f6;
        }

        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination button.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        @media (max-width: 768px) {
            .data-table {
                font-size: 12px;
            }

            .data-table th,
            .data-table td {
                padding: 8px;
            }
        }

        /* Auth menu styles */
        .auth-menu {
            position: relative;
        }

        .auth-wrapper {
            position: relative;
        }

        .auth-btn {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .auth-btn:focus {
            outline: 2px solid rgba(59, 130, 246, 0.3);
        }

        .auth-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 48px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            min-width: 200px;
            z-index: 40;
        }

        .auth-dropdown.show {
            display: block;
        }

        .guest-login {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
        }

        @media (max-width: 640px) {
            .auth-menu {
                position: static;
                margin-top: 8px;
            }

            .auth-dropdown {
                position: static;
                box-shadow: none;
                margin-top: 8px;
            }
        }
    </style>
</head>

<body>
    <div
        style="width: 100%; min-height: 100vh; background: linear-gradient(135deg, var(--blue-700) 0%, var(--blue-500) 100%); padding: 28px 20px;">  <div style="max-width: 1400px; margin: 0 auto; position:relative;">
            <header style="text-align: center; margin-bottom: 30px; padding-top:8px;">
                <h1 id="mainTitle" style="color: white; font-size: 34px; margin-bottom: 6px; font-weight: 800;">Form
                    Patroli Keamanan</h1>
                <p id="companyName" style="color: rgba(255,255,255,0.95); font-size: 16px;">PT CBA Chemical Industry
                    Pabrik</p>
            </header>

            <div
                style="margin-bottom: 20px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <button class="tab-button active" id="tabForm" onclick="switchTab('form')">📝 Form Input</button>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <button class="tab-button" id="tabHistory" onclick="switchTab('history')">📋 Riwayat Data</button>
                    @endif
                </div>

                <div style="display:flex; align-items:center; gap:8px;">
                    <div class="auth-menu">
                        @auth
                            <div class="auth-wrapper">
                                <button id="authBtn" class="auth-btn" aria-haspopup="true" aria-expanded="false"
                                    onclick="toggleAuthMenu(event)">
                                    <div style="text-align:right; margin-right:8px; line-height:1;">
                                        <div style="font-weight:700">{{ auth()->user()->name }}</div>
                                        <div style="font-size:12px;opacity:0.9">{{ auth()->user()->role }}</div>
                                    </div>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 9l6 6 6-6" stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <div id="authDropdown" class="auth-dropdown" role="menu" aria-labelledby="authBtn">
                                    <div style="padding:8px 12px; color:#111; font-weight:600;">{{ auth()->user()->name }}
                                    </div>
                                    <div style="padding:0 12px 8px 12px; font-size:13px; color:#6b7280">
                                        {{ auth()->user()->role }}</div>
                                    <div
                                        style="border-top:1px solid #eef2ff; margin-top:6px; padding-top:8px; display:flex; gap:8px; justify-content:flex-end;">
                                        <form method="POST" action="/logout" style="margin:0">
                                            @csrf
                                            <button type="submit" class="btn-secondary"
                                                style="background:#f3f4f6;color:#111;padding:8px 12px;border-radius:6px;">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="/login" class="guest-login">Login Admin</a>
                        @endauth
                    </div>
                </div>
            </div>

            <div id="formSection">
                <form id="patrolForm" onsubmit="handleSubmit(event)">
                    <div class="form-section">
                        <h2
                            style="font-size: 20px; font-weight: 700; margin-bottom: 20px; color: #1f2937; border-bottom: 3px solid #3b82f6; padding-bottom: 10px;">
                            👥 Data Shift & Anggota Tim</h2>
                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 20px;">
                            <div><label for="nama1" class="form-label">Nama Anggota 1 *</label> <input type="text"
                                    id="nama1" name="nama_anggota_1" class="form-input" required
                                    placeholder="Masukkan nama anggota 1"></div>
                            <div><label for="nama2" class="form-label">Nama Anggota 2 *</label> <input type="text"
                                    id="nama2" name="nama_anggota_2" class="form-input" required
                                    placeholder="Masukkan nama anggota 2"></div>
                            <div><label for="nama3" class="form-label">Nama Anggota 3 *</label> <input type="text"
                                    id="nama3" name="nama_anggota_3" class="form-input" required
                                    placeholder="Masukkan nama anggota 3"></div>
                        </div>
                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
                            <div>
                                <label for="hari" class="form-label">Hari *</label>
                                <select id="hari" name="hari" class="form-select" required>
                                    <option value="">Pilih Hari</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                    <option value="Minggu">Minggu</option>
                                </select>
                            </div>
                            <div><label for="tanggal" class="form-label">Tanggal *</label> <input type="date"
                                    id="tanggal" name="tanggal" class="form-input" required></div>
                            <div><label for="jamDinas" class="form-label">Jam Dinas *</label> <input type="time"
                                    id="jamDinas" name="jam_dinas" class="form-input" required></div>
                            <div>
                                <label for="shift" class="form-label">Shift *</label>
                                <select id="shift" name="shift" class="form-select" required>
                                    <option value="">Pilih Shift</option>
                                    <option value="Shift 1">Shift 1 (07:00-19:00)</option>
                                    <option value="Shift 2">Shift 2 (19:00-07:00)</option>
                                </select>
                            </div>
                        </div>
                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                            <div>
                                <label for="jabatan" class="form-label">Jabatan *</label>
                                <select id="jabatan" name="jabatan" class="form-select" required>
                                    <option value="">Pilih Jabatan</option>
                                    <option value="Kepala Security">Chief Security</option>
                                    <option value="Koordinator">Koordinator Lapangan</option>
                                    <option value="Anggota">Anggota</option>
                                </select>
                            </div>
                            <div>
                                <label for="area" class="form-label">Area Patroli *</label>
                                <div style="display:flex; gap:8px; align-items:center;">
                                    <select id="area" name="area" class="form-select" required style="flex:1;">
                                        <option value="">Pilih Area</option>
                                        <option value="Area Pabrik">Area Pabrik</option>
                                        <!-- <option value="Area Produksi">Area Produksi</option>
                                        <option value="Area Gudang">Area Gudang</option>
                                        <option value="Area Kantor">Area Kantor</option>
                                        <option value="Area Parkir">Area Parkir</option>
                                        <option value="Area Perimeter">Area Perimeter</option>
                                        <option value="Pintu Gerbang">Pintu Gerbang</option> -->
                                        <option value="__other_area__">Lainnya...</option>
                                    </select>

                                    <div id="areaCustomWrap" style="display:none; gap:8px; align-items:center;">
                                        <input type="text" id="areaCustom" class="form-input" placeholder="Ketik area lain" style="min-width:180px;" />
                                    </div>

                                    <button type="button" class="btn-secondary" onclick="addAreaOption()" title="Tambah area">＋</button>
                                </div>
                                <small style="color:#6b7280; display:block; margin-top:6px;">Pilih "Lainnya..." untuk menambah baru.</small>
                            </div>
                            <div>
                                <label for="absensiSelect" class="form-label">Keterangan Absensi</label>
                                <div style="display:flex; gap:8px; align-items:center;">
                                    <select id="absensiSelect" name="keterangan_absensi" class="form-select" required style="flex:1;">
                                        <option value="">Pilih Status</option>
                                        <option value="Hadir Lengkap">Hadir Lengkap</option>
                                        <option value="Hadir 2 Orang">Hadir 2 Orang</option>
                                        <option value="Hadir 1 Orang">Hadir 1 Orang</option>
                                        <option value="Ada yang Sakit">Ada yang Sakit</option>
                                        <option value="Ada yang Izin">Ada yang Izin</option>
                                        <option value="__other__">Lainnya...</option>
                                    </select>

                                    <div id="absensiCustomWrap" style="display:none; gap:8px; align-items:center;">
                                        <input type="text" id="absensiCustom" class="form-input" placeholder="Ketik keterangan lain" style="min-width:180px;" />
                                    </div>

                                    <button type="button" class="btn-secondary" onclick="addAbsensiOption()" title="Tambah opsi">＋</button>
                                </div>
                                <small style="color:#6b7280; display:block; margin-top:6px;">Pilih "Lainnya..." untuk menambah baru.</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2
                            style="font-size: 20px; font-weight: 700; margin-bottom: 20px; color: #1f2937; border-bottom: 3px solid #10b981; padding-bottom: 10px;">
                            🚶 Detail Patroli & Pelaporan</h2>

                        <div id="patrolEntriesContainer">
                            <div class="patrol-entry"
                                style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid #10b981;">
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <h4 style="font-weight: 600; color: #1f2937; font-size: 16px;">📍 Patroli #1</h4>
                                </div>
                                <div
                                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
                                    <div><label class="form-label">Jam Patroli Mulai *</label> <input type="time"
                                            class="form-input patrol-start" required></div>
                                    <div><label class="form-label">Jam Patroli Selesai *</label> <input type="time"
                                            class="form-input patrol-end" required></div>
                                </div>
                                <div style="margin-bottom: 16px;"><label class="form-label">Uraian Keterangan Patroli
                                        *</label> <textarea class="form-textarea patrol-uraian" required
                                        placeholder="Tuliskan laporan hasil patroli..."></textarea></div>
                                <div
                                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                                    <div><label class="form-label">ID Card Visitor</label> <input type="text"
                                            class="form-input patrol-visitor" placeholder="Jumlah/Nama"></div>
                                    <div><label class="form-label">ID Card Ekspedisi</label> <input type="text"
                                            class="form-input patrol-ekspedisi" placeholder="Nama ekspedisi"></div>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom: 24px; text-align: center;">
                            <button type="button" class="btn-secondary" onclick="addPatrolEntry()"
                                style="padding: 10px 24px; font-size: 15px;"> ➕ Tambah Waktu Patroli </button>
                        </div>

                        <div
                            style="background: #fffbeb; padding: 20px; border-radius: 8px; border-left: 4px solid #f59e0b;">
                            <h4 style="font-weight: 600; color: #1f2937; font-size: 16px; margin-bottom: 16px;">✍️ Tanda
                                Tangan Digital (E-Sign) *</h4>
                            <div style="margin-bottom: 12px;"><label for="eSignName" class="form-label">Nama
                                    Penandatangan</label> <input type="text" id="eSignName" name="esign_name"
                                    class="form-input" required placeholder="Masukkan nama penandatangan"></div>
                            <div style="position: relative;">
                                <label class="form-label">Tanda Tangan</label>
                                <canvas id="signatureCanvas" class="signature-canvas" width="600" height="200"></canvas>
                                <div style="margin-top: 8px; display: flex; gap: 8px; flex-wrap: wrap;">
                                    <button type="button" class="btn-secondary" onclick="clearSignature()">🗑️ Hapus
                                        Tanda Tangan</button>
                                    <span style="font-size: 12px; color: #6b7280; display: flex; align-items: center;">
                                        💡 Gunakan mouse atau sentuh layar </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 24px;">
                        <button type="submit" class="btn-primary" id="submitBtn"
                            style="padding: 14px 32px; font-size: 18px;"> <span id="btnText">💾 Simpan Data
                                Patroli</span> </button>
                    </div>
                </form>
            </div>

            <div id="historySection" style="display: none;">
                <div class="filter-section">
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px; color: #1f2937;">🔍 Filter &
                        Pencarian</h3>
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px;">
                        <div><label class="form-label" style="font-size: 12px;">Pencarian</label> <input type="text"
                                id="searchInput" class="form-input" placeholder="Cari nama, area..."
                                oninput="filterAndRenderHistory()"></div>
                        <div><label class="form-label" style="font-size: 12px;">Tanggal Mulai</label> <input type="date"
                                id="dateFrom" class="form-input" onchange="filterAndRenderHistory()"></div>
                        <div><label class="form-label" style="font-size: 12px;">Tanggal Akhir</label> <input type="date"
                                id="dateTo" class="form-input" onchange="filterAndRenderHistory()"></div>
                        <div>
                            <label class="form-label" style="font-size: 12px;">Area</label>
                            <select id="filterArea" class="form-select" onchange="filterAndRenderHistory()">
                                <option value="">Area Pabrik</option>
                                <!-- <option value="Area Produksi">Area Produksi</option>
                                <option value="Area Gudang">Area Gudang</option>
                                <option value="Area Kantor">Area Kantor</option>
                                <option value="Area Parkir">Area Parkir</option>
                                <option value="Area Perimeter">Area Perimeter</option>
                                <option value="Pintu Gerbang">Pintu Gerbang</option> -->
                            </select>
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 12px;">Shift</label>
                            <select id="filterShift" class="form-select" onchange="filterAndRenderHistory()">
                                <option value="">Semua Shift</option>
                                <option value="Shift 1 (Pagi)">Shift 1 (07:00-19:00)</option>
                                <option value="Shift 2 (Siang)">Shift 2 (19:00-07:00)</option>
                            </select>
                        </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap:8px; align-items: flex-end;">
                            <button type="button" class="btn-secondary" onclick="resetFilters()" style="width: 100%;">🔄
                                Reset Filter</button>
                            <button type="button" class="btn-info" onclick="exportExcel()" style="width: 100%;">⬇️ Export
                                Excel</button>
                        </div>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid #e5e7eb;">
                        <span id="totalRecords" style="font-weight: 600; color: #6b7280;">Total: 0 data</span>
                        <div style="display: flex; gap: 8px; align-items: center;"><label class="form-label"
                                style="font-size: 12px; margin: 0;">Tampilkan:</label> <select id="perPage"
                                class="form-select" style="width: 80px; padding: 6px;" onchange="changePerPage()">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select></div>
                    </div>
                </div>

                <div
                    style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Nama Pelapor</th>
                                    <th>Anggota Tim</th>
                                    <th>Tanggal</th>
                                    <th>Shift</th>
                                    <th>Area</th>
                                    <th>Waktu Patroli</th>
                                    <th>Status</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody"></tbody>
                        </table>
                    </div>
                    <div id="emptyState" style="text-align: center; padding: 60px 20px; display: none;">
                        <div style="font-size: 64px; margin-bottom: 16px;">📭</div>
                        <h3 style="font-size: 20px; color: #6b7280;">Belum Ada Data</h3>
                    </div>
                    <div id="paginationContainer" class="pagination" style="padding: 20px;"></div>
                </div>
            </div>

            <div id="modalDetail" class="modal-overlay" onclick="closeModal(event)">
                <div class="modal-content" onclick="event.stopPropagation()">
                    <div style="padding: 24px; border-bottom: 1px solid #e5e7eb;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h2 style="font-size: 24px; font-weight: 700; color: #1f2937;">📋 Detail Laporan Patroli
                            </h2>
                            <button onclick="closeModal()"
                                style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;">×</button>
                        </div>
                    </div>
                    <div id="modalBody" style="padding: 24px;"></div>
                    <div
                        style="padding: 20px 24px; border-top: 1px solid #e5e7eb; display: flex; gap: 12px; justify-content: flex-end;">
                        <button onclick="closeModal()" class="btn-secondary">Tutup</button> <button id="modalDeleteBtn"
                            class="btn-danger">🗑️ Hapus Data</button>
                    </div>
                </div>
            </div>

            <div id="toast" class="toast"></div>

            <!-- Footer -->
            <footer
                style="text-align:center;color:rgba(255,255,255,0.9);font-size:13px;padding:14px 0;margin-top:18px;">
                © {{ date('Y') }} IT Pabrik CBA — Semua hak dilindungi.
            </footer>
        </div>
    </div>

    <script>
        let allPatrolData = [];
        let filteredData = [];
        let currentTab = 'form';
        let currentPage = 1;
        let perPage = 10;
        let isDrawing = false;
        let signatureData = '';
        let patrolEntryCount = 1;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Server-side flag indicating whether the current user is an admin
        const isAdmin = @json(auth()->check() && auth()->user()->role === 'admin');

        // --- Signature Setup (Responsive + High-DPI) ---
        let canvas = document.getElementById('signatureCanvas');
        let ctx = null;
        let listenersAdded = false;

        const getPos = (e) => {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return { x: clientX - rect.left, y: clientY - rect.top };
        };

        function setupCanvas(keepImage = true) {
            // preserve current drawing (if any)
            const prevData = keepImage ? signatureData : '';

            canvas = document.getElementById('signatureCanvas');
            const ratio = window.devicePixelRatio || 1;

            // Set internal resolution to match displayed size * DPR for crisp strokes
            canvas.width = Math.floor(canvas.clientWidth * ratio);
            canvas.height = Math.floor(canvas.clientHeight * ratio);

            // Get context and scale so drawing coordinates match CSS pixels
            ctx = canvas.getContext('2d');
            ctx.setTransform(1, 0, 0, 1, 0, 0); // reset any transforms
            ctx.scale(ratio, ratio);
            ctx.strokeStyle = '#000'; ctx.lineWidth = 2; ctx.lineCap = 'round';

            // If we had an existing signature image, redraw it to preserve
            if (prevData) {
                const img = new Image();
                img.onload = () => {
                    // draw at 1:1 CSS pixel scale (context already scaled by ratio)
                    ctx.clearRect(0, 0, canvas.clientWidth, canvas.clientHeight);
                    ctx.drawImage(img, 0, 0, canvas.clientWidth, canvas.clientHeight);
                };
                img.src = prevData;
            } else {
                ctx.clearRect(0, 0, canvas.clientWidth, canvas.clientHeight);
            }

            if (!listenersAdded) {
                const startDraw = (e) => { isDrawing = true; ctx.beginPath(); const pos = getPos(e); ctx.moveTo(pos.x, pos.y); e.preventDefault(); };
                const moveDraw = (e) => { if (!isDrawing) return; const pos = getPos(e); ctx.lineTo(pos.x, pos.y); ctx.stroke(); e.preventDefault(); };
                const endDraw = () => { if (isDrawing) { isDrawing = false; signatureData = canvas.toDataURL(); } };

                canvas.addEventListener('mousedown', startDraw);
                canvas.addEventListener('mousemove', moveDraw);
                canvas.addEventListener('mouseup', endDraw);
                canvas.addEventListener('mouseout', endDraw);
                canvas.addEventListener('touchstart', startDraw, { passive: false });
                canvas.addEventListener('touchmove', moveDraw, { passive: false });
                canvas.addEventListener('touchend', endDraw, { passive: false });

                listenersAdded = true;
            }
        }

        function clearSignature() { if (!ctx) return; ctx.clearRect(0, 0, canvas.clientWidth, canvas.clientHeight); signatureData = ''; }

        // Debounce helper
        function debounce(fn, wait) {
            let t;
            return function (...args) { clearTimeout(t); t = setTimeout(() => fn.apply(this, args), wait); };
        }

        // Resize handler: re-setup canvas and preserve current signature image
        window.addEventListener('resize', debounce(() => { setupCanvas(true); }, 180));

        // Initialize at load
        setupCanvas(false);

        // --- Patrol Entries Logic ---
        function addPatrolEntry() {
            patrolEntryCount++;
            const container = document.getElementById('patrolEntriesContainer');
            const newEntry = document.createElement('div');
            newEntry.className = 'patrol-entry';
            newEntry.style.cssText = 'background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid #10b981; position: relative;';
            newEntry.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <h4 style="font-weight: 600; color: #1f2937; font-size: 16px;">📍 Patroli #${patrolEntryCount}</h4>
                    <button type="button" onclick="removePatrolEntry(this)" class="btn-danger" style="font-size: 12px; padding: 4px 10px;">✕ Hapus</button>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
                    <div><label class="form-label">Jam Patroli Mulai *</label> <input type="time" class="form-input patrol-start" required></div>
                    <div><label class="form-label">Jam Patroli Selesai *</label> <input type="time" class="form-input patrol-end" required></div>
                </div>
                <div style="margin-bottom: 16px;"><label class="form-label">Uraian Keterangan Patroli *</label> <textarea class="form-textarea patrol-uraian" required placeholder="Uraian..."></textarea></div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                    <div><label class="form-label">Visitor</label> <input type="text" class="form-input patrol-visitor" placeholder="Visitor"></div>
                    <div><label class="form-label">Ekspedisi</label> <input type="text" class="form-input patrol-ekspedisi" placeholder="Ekspedisi"></div>
                </div>`;
            container.appendChild(newEntry);
        }

        function removePatrolEntry(btn) {
            btn.closest('.patrol-entry').remove();
            // Re-numbering logic could go here
        }

        // Allow adding custom options to the absensi datalist
        function addAbsensiOption() {
            const select = document.getElementById('absensiSelect');
            const customWrap = document.getElementById('absensiCustomWrap');
            const customInput = document.getElementById('absensiCustom');
            if (!select) return;

            // If user selected 'Lainnya...' and filled custom input, add it as new option
            if (customWrap && customWrap.style.display !== 'none') {
                const val = (customInput.value || '').trim();
                if (!val) { showToast('Masukkan nilai untuk ditambahkan', true); customInput.focus(); return; }

                // check if exists (case-insensitive)
                const exists = Array.from(select.options).some(o => o.value.toLowerCase() === val.toLowerCase());
                if (exists) { showToast('Opsi sudah ada', true); select.value = val; customWrap.style.display = 'none'; customInput.value = ''; return; }

                const opt = document.createElement('option');
                opt.value = val;
                opt.textContent = val;
                // insert before the '__other__' option if present
                const otherIndex = Array.from(select.options).findIndex(o => o.value === '__other__');
                if (otherIndex >= 0) {
                    select.add(opt, select.options[otherIndex]);
                } else {
                    select.add(opt);
                }
                select.value = val;
                customInput.value = '';
                customWrap.style.display = 'none';
                showToast('Opsi ditambahkan');
                return;
            }

            // If custom input not visible, focus select to pick or choose 'Lainnya...'
            select.focus();
        }

        // Show/hide custom input when user selects '__other__'
        (function attachAbsensiHandler(){
            const select = document.getElementById('absensiSelect');
            const customWrap = document.getElementById('absensiCustomWrap');
            const customInput = document.getElementById('absensiCustom');
            if (!select) return;
            select.addEventListener('change', () => {
                if (select.value === '__other__') {
                    if (customWrap) customWrap.style.display = 'flex';
                    if (customInput) customInput.focus();
                } else {
                    if (customWrap) customWrap.style.display = 'none';
                }
            });
        })();

        // Area: show/hide custom input when user selects '__other_area__'
        (function attachAreaHandler(){
            const select = document.getElementById('area');
            const customWrap = document.getElementById('areaCustomWrap');
            const customInput = document.getElementById('areaCustom');
            if (!select) return;
            select.addEventListener('change', () => {
                if (select.value === '__other_area__') {
                    if (customWrap) customWrap.style.display = 'flex';
                    if (customInput) customInput.focus();
                } else {
                    if (customWrap) customWrap.style.display = 'none';
                }
            });
        })();

        function addAreaOption() {
            const select = document.getElementById('area');
            const customWrap = document.getElementById('areaCustomWrap');
            const customInput = document.getElementById('areaCustom');
            if (!select) return;

            if (customWrap && customWrap.style.display !== 'none') {
                const val = (customInput.value || '').trim();
                if (!val) { showToast('Masukkan nama area untuk ditambahkan', true); customInput.focus(); return; }

                const exists = Array.from(select.options).some(o => o.value.toLowerCase() === val.toLowerCase());
                if (exists) { showToast('Area sudah ada', true); select.value = val; customWrap.style.display = 'none'; customInput.value = ''; return; }

                const opt = document.createElement('option');
                opt.value = val;
                opt.textContent = val;
                const otherIndex = Array.from(select.options).findIndex(o => o.value === '__other_area__');
                if (otherIndex >= 0) { select.add(opt, select.options[otherIndex]); } else { select.add(opt); }
                select.value = val;
                customInput.value = '';
                customWrap.style.display = 'none';
                showToast('Area ditambahkan');
                return;
            }

            select.focus();
        }

        // --- Data Handling (Replacement for SDK) ---
        async function fetchAllData() {
            try {
                const response = await fetch('/api/patrols');

                // If the endpoint returns non-OK (e.g. 403 for non-admin), handle gracefully
                if (!response.ok) {
                    if (response.status === 403) {
                        // User is not authorized to view history (normal for public users)
                        allPatrolData = [];
                        showToast('Akses riwayat terbatas — login sebagai admin untuk melihat', true);
                        return;
                    }
                    throw new Error('HTTP ' + response.status);
                }

                allPatrolData = await response.json();
                filterAndRenderHistory();
            } catch (err) {
                showToast('Gagal memuat data', true);
            }
        }

        async function handleSubmit(event) {
            event.preventDefault();
            if (!signatureData) { showToast('Tanda tangan wajib diisi!', true); return; }

            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            submitBtn.disabled = true;
            btnText.innerHTML = '<span class="loading-spinner"></span> Menyimpan...';

            // Collect dynamic entries
            const patrolEntries = [];
            document.querySelectorAll('.patrol-entry').forEach((entry, index) => {
                patrolEntries.push({
                    no: index + 1,
                    jam_mulai: entry.querySelector('.patrol-start').value,
                    jam_selesai: entry.querySelector('.patrol-end').value,
                    uraian: entry.querySelector('.patrol-uraian').value,
                    visitor: entry.querySelector('.patrol-visitor').value || '-',
                    ekspedisi: entry.querySelector('.patrol-ekspedisi').value || '-'
                });
            });

            const formData = new FormData(document.getElementById('patrolForm'));
            formData.append('patrol_data', JSON.stringify(patrolEntries));
            formData.append('e_sign', document.getElementById('eSignName').value + '|||' + signatureData);

            try {
                const res = await fetch('/api/patrols', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: formData // FormData handles multipart/form-data logic
                });

                const result = await res.json();

                if (result.status === 'success') {
                    showToast('✅ Data berhasil disimpan!');
                    // NOTE: keep form values intact (do not reset inputs) as requested
                    // If you prefer to clear the signature canvas, call `clearSignature()` here.

                    // Refresh data (only for admin users)
                    if (isAdmin) {
                        fetchAllData();
                    }
                } else {
                    throw new Error(result.message);
                }
            } catch (err) {
                showToast('❌ Gagal: ' + err.message, true);
            } finally {
                submitBtn.disabled = false;
                btnText.innerHTML = '💾 Simpan Data Patroli';
            }
        }

        // --- UI Interactions ---
        function switchTab(tab) {
            currentTab = tab;
            if (tab === 'form') {
                document.getElementById('formSection').style.display = 'block';
                document.getElementById('historySection').style.display = 'none';
                document.getElementById('tabForm').classList.add('active');
                document.getElementById('tabHistory').classList.remove('active');
            } else {
                document.getElementById('formSection').style.display = 'none';
                document.getElementById('historySection').style.display = 'block';
                document.getElementById('tabForm').classList.remove('active');
                document.getElementById('tabHistory').classList.add('active');
                if (isAdmin) {
                    fetchAllData();
                } else {
                    showToast('Akses riwayat terbatas — login sebagai admin untuk melihat', true);
                }
            }
        }

        function showToast(msg, isError = false) {
            const t = document.getElementById('toast'); 
            t.textContent = msg; t.className = 'toast show' + (isError ? ' error' : '');
            setTimeout(() => t.classList.remove('show'), 3000);
        }

        // --- Auth menu toggle ---
        function toggleAuthMenu(e) {
            e.stopPropagation();
            const btn = document.getElementById('authBtn');
            const dd = document.getElementById('authDropdown');
            if (!btn || !dd) return;
            const isOpen = dd.classList.contains('show');
            if (isOpen) {
                dd.classList.remove('show');
                btn.setAttribute('aria-expanded', 'false');
            } else {
                dd.classList.add('show');
                btn.setAttribute('aria-expanded', 'true');
            }
        }

        // Close auth dropdown when clicking outside or pressing Escape
        document.addEventListener('click', function (e) {
            const dd = document.getElementById('authDropdown');
            const btn = document.getElementById('authBtn');
            if (!dd || !btn) return;
            if (!dd.classList.contains('show')) return;
            if (e.target === btn || btn.contains(e.target)) return;
            if (!dd.contains(e.target)) {
                dd.classList.remove('show');
                btn.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const dd = document.getElementById('authDropdown');
                const btn = document.getElementById('authBtn');
                if (dd && dd.classList.contains('show')) {
                    dd.classList.remove('show');
                    btn.setAttribute('aria-expanded', 'false');
                }
            }
        });

        function filterAndRenderHistory() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            const area = document.getElementById('filterArea').value;
            const shift = document.getElementById('filterShift').value;

            filteredData = allPatrolData.filter(r => {
                const anggotaStr = [r.nama_anggota_1, r.nama_anggota_2, r.nama_anggota_3].filter(Boolean).join(' ').toLowerCase();
                const matchSearch = !search || anggotaStr.includes(search) || r.area.toLowerCase().includes(search) || (r.esign_name || '').toLowerCase().includes(search);
                const matchDate = (!dateFrom || r.tanggal >= dateFrom) && (!dateTo || r.tanggal <= dateTo);
                const matchArea = !area || r.area === area;
                const matchShift = !shift || r.shift === shift;
                return matchSearch && matchDate && matchArea && matchShift;
            });

            currentPage = 1;
            renderTable();
        }

        function renderTable() {
            const tbody = document.getElementById('tableBody');
            const empty = document.getElementById('emptyState');
            document.getElementById('totalRecords').textContent = `Total: ${filteredData.length} data`;

            if (filteredData.length === 0) { tbody.innerHTML = ''; empty.style.display = 'block'; return; }
            empty.style.display = 'none';

            const start = (currentPage - 1) * perPage;
            const paginated = filteredData.slice(start, start + perPage);

            tbody.innerHTML = paginated.map((r, i) => {
                const patrols = r.patrol_details; // Already JSON from Laravel
                let summary = '-';
                if (patrols && patrols.length > 0) {
                    // Show all patrol times (one per detail). Join with ' / ' so multiple times appear separated by slashes.
                    summary = patrols.map(p => `${p.jam_mulai || ''} - ${p.jam_selesai || ''}`).join(' / ');
                }
                const badge = r.keterangan_absensi === 'Hadir Lengkap' ? 'badge-success' : 'badge-warning';

                const formattedDate = formatTanggal(r.tanggal);
                return `
                    <tr>
                        <td>${start + i + 1}</td>
                        <td>${r.esign_name || '-'}</td>
                        <td>${[r.nama_anggota_1, r.nama_anggota_2, r.nama_anggota_3].filter(Boolean).join(' / ')}</td>
                        <td>${formattedDate}</td>
                        <td><span class="badge badge-info">${r.shift}</span></td>
                        <td>${r.area}</td>
                        <td>${summary}</td>
                        <td><span class="badge ${badge}">${r.keterangan_absensi}</span></td>
                        <td><button onclick="viewDetail(${r.id})" class="btn-info" style="padding: 6px;">👁️ Detail</button></td>
                    </tr>`;
            }).join('');

            renderPagination();
        }

        // Format date string to Indonesian weekday + dd month yyyy
        function formatTanggal(dateStr) {
            if (!dateStr) return '';
            try {
                // Ensure consistent parsing by forcing time
                const dt = new Date(dateStr + 'T00:00:00');
                // Options: weekday, day, month, year
                const opt = { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' };
                // Use Indonesian locale
                return dt.toLocaleDateString('id-ID', opt);
            } catch (e) {
                return dateStr;
            }
        }

        function renderPagination() {
            const total = Math.ceil(filteredData.length / perPage);
            const container = document.getElementById('paginationContainer');
            if (total <= 1) { container.innerHTML = ''; return; }

            let html = `<button onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>Prev</button>`;
            for (let i = 1; i <= total; i++) {
                html += `<button onclick="goToPage(${i})" class="${i === currentPage ? 'active' : ''}">${i}</button>`;
            }
            html += `<button onclick="goToPage(${currentPage + 1})" ${currentPage === total ? 'disabled' : ''}>Next</button>`;
            container.innerHTML = html;
        }

        function goToPage(p) { currentPage = p; renderTable(); }
        function changePerPage() { perPage = parseInt(document.getElementById('perPage').value); currentPage = 1; renderTable(); }
        function resetFilters() {
            // Clear all filter fields if present
            const ids = ['searchInput', 'dateFrom', 'dateTo', 'filterArea', 'filterShift'];
            ids.forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                if (el.tagName === 'SELECT' || el.type === 'select-one') {
                    el.selectedIndex = 0;
                } else {
                    el.value = '';
                }
            });

            // Reset per-page to default
            const per = document.getElementById('perPage');
            if (per) { per.value = '10'; perPage = 10; }
            currentPage = 1;

            filterAndRenderHistory();
        }

        function exportExcel() {
            // Produces an Excel-compatible HTML table where all cells are center-aligned.
            const dataToExport = (currentTab === 'history') ? filteredData : allPatrolData;
            if (!dataToExport || dataToExport.length === 0) { showToast('Tidak ada data untuk diekspor', true); return; }

            const headers = ['No', 'Pembuat Laporan', 'Anggota 1', 'Anggota 2', 'Anggota 3', 'Tanggal', 'Patroli', 'Shift', 'Waktu Patroli', 'Area', 'Status', 'Patroli Detail'];

            let html = `<!doctype html><html><head><meta charset="utf-8"></head><body>`;
            html += `<table border="1" style="border-collapse:collapse; width:100%;">`;
            // header row (centered)
            html += '<thead><tr>' + headers.map(h => `<th style="padding:6px; text-align:center; background:#f3f4f6;">${h}</th>`).join('') + '</tr></thead>';
            html += '<tbody>';

            let counter = 1;
            dataToExport.forEach((r) => {
                const tanggal = formatTanggal(r.tanggal || '');
                const shift = r.shift || '';
                const area = r.area || '';
                const anggota1 = r.nama_anggota_1 || '';
                const anggota2 = r.nama_anggota_2 || '';
                const anggota3 = r.nama_anggota_3 || '';
                const status = r.keterangan_absensi || '';
                const penandatangan = r.esign_name || '';
                const patrols = r.patrol_details || [];

                if (patrols && patrols.length > 0) {
                    patrols.forEach(p => {
                        const patroliNo = p.no || '';
                        const waktu = `${p.jam_mulai || ''} - ${p.jam_selesai || ''}`;
                        const uraian = (p.uraian || '').replace(/\r?\n/g, ' ');
                        // Build centered row following requested order
                        const cells = [counter, penandatangan, anggota1, anggota2, anggota3, tanggal, patroliNo, shift, waktu, area, status, uraian];
                        html += '<tr>' + cells.map(c => `<td style="padding:6px; text-align:center;">${c === null || c === undefined ? '' : String(c)}</td>`).join('') + '</tr>';
                        counter++;
                    });
                } else {
                    const cells = [counter, penandatangan, anggota1, anggota2, anggota3, tanggal, '', shift, '-', area, status, '-'];
                    html += '<tr>' + cells.map(c => `<td style="padding:6px; text-align:center;">${c === null || c === undefined ? '' : String(c)}</td>`).join('') + '</tr>';
                    counter++;
                }
            });

            html += '</tbody></table></body></html>';

            const blob = new Blob([html], { type: 'application/vnd.ms-excel;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            const ts = new Date().toISOString().slice(0, 19).replace(/[:T]/g, '-');
            a.download = `patrol-export-${ts}.xls`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
            showToast('Export siap. File dibuka di Excel dengan teks center.');
        }

        function viewDetail(id) {
            const r = allPatrolData.find(x => x.id === id);
            if (!r) return;

            const patrols = r.patrol_details || [];
            const patrolHtml = patrols.map(p => `
                <div style="background: #f9fafb; padding: 12px; margin-bottom: 8px; border-left: 4px solid #10b981;">
                    <b>Patroli #${p.no} (${p.jam_mulai} - ${p.jam_selesai})</b><br>
                    ${p.uraian}<br>
                    <small>Vis: ${p.visitor} | Eksp: ${p.ekspedisi}</small>
                </div>
            `).join('');

            document.getElementById('modalBody').innerHTML = `
                <h3>${r.area} - ${r.shift}</h3>
                <p><b>Tim:</b> ${r.nama_anggota_1}, ${r.nama_anggota_2}, ${r.nama_anggota_3}</p>
                <div style="margin: 10px 0;">${patrolHtml}</div>
                <hr>
                <p><b>TTD:</b> ${r.esign_name}</p>
                ${r.esign_image_url ? `<img src="${r.esign_image_url}" style="max-width: 200px; border: 1px solid #ddd;">` : ''}
            `;

            document.getElementById('modalDetail').classList.add('show');
            document.getElementById('modalDetail').style.display = 'flex';

            // Delete handler
            document.getElementById('modalDeleteBtn').onclick = async () => {
                if (!confirm('Yakin hapus?')) return;
                await fetch(`/api/patrols/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
                closeModal();
                fetchAllData();
                showToast('Data dihapus');
            };
        }

        function closeModal(e) {
            if (e && e.target.classList.contains('modal-content')) return;
            document.getElementById('modalDetail').classList.remove('show');
            setTimeout(() => document.getElementById('modalDetail').style.display = 'none', 300);
        }

        // Init
        window.onload = function () {
            // Optional: load data immediately if desired
        };
    </script>
</body>

</html>