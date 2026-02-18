<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Absensi') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: radial-gradient(circle at top right, #fff5f5, #ffffff);
        }

        #reader {
            border: none !important;
        }

        #reader video {
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
            border-radius: 12px;
        }

        #reader__scan_region img {
            display: none !important;
        }

        #reader__dashboard_section_csr button {
            background: #ef4444 !important;
            color: white !important;
            padding: 10px 20px !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            border: none !important;
            transition: all 0.3s ease;
        }

        #reader__dashboard_section_csr button:hover {
            background: #dc2626 !important;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>

<body class="text-[#1b1b18] flex p-4 lg:p-8 items-center min-h-screen flex-col">

    <header class="w-full lg:max-w-5xl max-w-[350px] text-sm mb-6 flex justify-between items-center">
        <div class="font-bold text-red-600 text-lg tracking-tight">
            {{ config('app.name', 'Sistem Absensi') }}
        </div>
        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @guest
                    <a href="{{ route('login') }}"
                        class="px-5 py-2 text-gray-600 hover:text-red-600 transition font-medium">
                        Log in
                    </a>
                @endguest
            </nav>
        @endif
    </header>

    <div class="flex items-center justify-center w-full grow">
        <main
            class="flex max-w-[350px] w-full flex-col lg:max-w-5xl lg:flex-row bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">

            <div class="flex-1 p-6 lg:p-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Presensi Siswa</h1>
                        <p class="text-gray-500 mt-1">Scan QR atau input manual Nama anda.</p>
                    </div>
                    <div
                        class="bg-red-50 px-4 py-2 rounded-2xl border border-red-100 inline-flex flex-col items-center md:items-end">
                        <span id="realtime-clock" class="text-2xl font-bold text-red-600 leading-none">00:00:00</span>
                        <span id="realtime-date"
                            class="text-[10px] uppercase tracking-widest font-semibold text-red-400 mt-1 text-center">...</span>
                    </div>
                </div>

                <div class="relative bg-gray-900 rounded-2xl overflow-hidden shadow-2xl aspect-video">
                    <div id="reader" class="w-full"></div>
                </div>

                <div class="mt-10 relative z-50">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-3 px-1">Input Nama
                        Manual</label>
                    <div
                        class="flex flex-col sm:flex-row gap-0 overflow-hidden rounded-2xl border-2 border-red-500 shadow-lg shadow-red-100 bg-white">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-4 flex items-center text-red-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <input type="text" id="manual_nama"
                                class="w-full pl-12 pr-4 py-4 bg-transparent outline-none text-lg font-semibold placeholder:font-normal"
                                placeholder="Masukkan Nama Lengkap">
                        </div>
                        <button onclick="prosesAbsen(document.getElementById('manual_nama').value)"
                            class="bg-red-600 hover:bg-red-700 text-white px-10 py-4 font-extrabold transition-all active:scale-95 flex items-center justify-center gap-2 border-t sm:border-t-0 sm:border-l border-red-500 min-w-[160px]">
                            SUBMIT
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="hidden lg:flex w-[320px] bg-red-600 p-10 flex-col justify-between text-white relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <div
                        class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold leading-tight">Smart<br>Attendance</h2>
                    <div class="h-1 w-10 bg-white/40 mt-4 rounded-full"></div>
                </div>

                <div class="relative z-10">
                    <p class="text-red-100 text-sm leading-relaxed italic">
                        "Kedisiplinan adalah jembatan antara cita-cita dan pencapaiannya."
                    </p>
                    <div class="mt-6 text-[10px] font-bold tracking-widest text-red-300 uppercase">Version 2.0.4</div>
                </div>
            </div>

        </main>
    </div>

    <footer class="py-6 text-sm text-gray-400 font-medium">
        &copy; 2026 {{ config('app.name') }} &bull; Digitalizing Education
    </footer>

    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            document.getElementById('realtime-clock').textContent = `${hours}:${minutes}:${seconds}`;
            document.getElementById('realtime-date').textContent = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateClock, 1000);
        updateClock();

        function onScanSuccess(decodedText, decodedResult) {
            html5QrcodeScanner.clear();
            prosesAbsen(decodedText);
        }

        function prosesAbsen(nama) {
            if (!nama) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nama Kosong',
                    text: 'Silakan masukkan nama anda.'
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi',
                text: "Proses absen untuk nama: " + nama + "?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Absen!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch("{{ route('absen.store') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                nama: nama
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: data.message
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal terhubung ke server.'
                            }).then(() => {
                                window.location.reload();
                            });
                        });
                } else {
                    window.location.reload();
                }
            });
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 15,
                qrbox: null,
                aspectRatio: 1.777778
            }, false);
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>

</html>
