<x-app-layout>
    <script src="https://unpkg.com/lucide@latest"></script>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-8 py-2">
            <div>
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2">
                        <li class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Admin</li>
                        <li class="text-[10px] font-black uppercase tracking-[0.3em] text-red-500">/ Dashboard</li>
                    </ol>
                </nav>
                <h2 class="font-black text-4xl text-gray-900 leading-none tracking-tighter italic uppercase">
                    Management <span class="text-red-600">Hub</span>
                </h2>
            </div>

            <div class="flex items-center gap-4">
                <button onclick="openModal('modalTambahSiswa')"
                    class="group relative flex items-center px-8 py-4 bg-gray-900 rounded-[24px] font-black text-xs text-white uppercase tracking-[0.2em] hover:bg-red-600 transition-all duration-500 shadow-[0_20px_40px_-12px_rgba(0,0,0,0.3)] active:scale-95 overflow-hidden">
                    <i data-lucide="plus-circle"
                        class="w-5 h-5 mr-3 text-red-500 group-hover:text-white transition-colors"></i>
                    <span>Siswa Baru</span>
                </button>

                <form id="filterForm" action="{{ route('dashboard') }}" method="GET"
                    class="flex items-center gap-3 bg-white/80 backdrop-blur-xl p-2 rounded-[28px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white">
                    <div class="flex items-center pl-4 pr-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-gray-400 mr-3"></i>
                        <input type="month" name="bulan" id="filterBulan" value="{{ $bulanDipilih }}"
                            class="border-none focus:ring-0 text-sm font-bold text-gray-800 bg-transparent p-0 cursor-pointer uppercase"
                            required>
                    </div>
                    <button type="submit"
                        class="flex items-center px-6 py-3 bg-gray-900 hover:bg-red-600 text-white rounded-[22px] text-[10px] font-black uppercase tracking-widest transition-all shadow-lg">
                        <i data-lucide="filter" class="w-3.5 h-3.5 mr-2"></i>
                        Filter
                    </button>
                    <button type="button" onclick="exportData()"
                        class="flex items-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-[22px] text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-emerald-200">
                        <i data-lucide="download" class="w-3.5 h-3.5 mr-2"></i>
                        Export
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-[#F4F7FA]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="inline-flex p-1.5 bg-gray-200/50 backdrop-blur-md rounded-[32px] mb-12 shadow-inner">
                <button onclick="switchTab('rekap')" id="tab-rekap"
                    class="tab-btn px-10 py-4 rounded-[28px] text-[11px] font-black tracking-[0.2em] transition-all duration-500">
                    REKAP ABSENSI
                </button>
                <button onclick="switchTab('siswa')" id="tab-siswa"
                    class="tab-btn px-10 py-4 rounded-[28px] text-[11px] font-black tracking-[0.2em] transition-all duration-500">
                    DATABASE SISWA
                </button>
            </div>

            <div id="content-rekap" class="tab-content animate-fade-in">
                <div
                    class="bg-white rounded-[48px] shadow-[0_40px_80px_-20px_rgba(0,0,0,0.05)] border border-gray-100/50 overflow-hidden">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-50">
                                <th
                                    class="px-10 py-8 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">
                                    Siswa</th>
                                <th
                                    class="px-10 py-8 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">
                                    Timestamp</th>
                                <th
                                    class="px-10 py-8 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50/50">
                            @forelse($rekapAbsen as $nama => $daftarAbsen)
                                @php $total = $daftarAbsen->count(); @endphp
                                @foreach ($daftarAbsen as $index => $row)
                                    <tr class="group hover:bg-gray-50/80 transition-all duration-300">
                                        @if ($index === 0)
                                            <td class="px-10 py-8" rowspan="{{ $total }}">
                                                <div class="flex items-center gap-6">
                                                    <div class="relative">
                                                        <div
                                                            class="w-16 h-16 rounded-[24px] bg-gradient-to-br from-gray-900 to-gray-700 flex items-center justify-center shadow-2xl">
                                                            <span
                                                                class="text-white font-black text-xl italic">{{ substr($nama, 0, 1) }}</span>
                                                        </div>
                                                        <div
                                                            class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 border-4 border-white rounded-full">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="font-black text-gray-900 text-lg tracking-tighter">
                                                            {{ $nama }}</p>
                                                        <p
                                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">
                                                            Verified Student</p>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                        <td class="px-10 py-8">
                                            <div class="flex items-center gap-3">
                                                <span
                                                    class="text-sm font-bold text-gray-900 tracking-tight">{{ $row->tanggal }}</span>
                                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                                <span
                                                    class="text-sm font-black text-red-600 font-mono">{{ $row->waktu }}</span>
                                            </div>
                                        </td>
                                        @if ($index === 0)
                                            <td class="px-10 py-8 text-center" rowspan="{{ $total }}">
                                                <div
                                                    class="inline-flex items-center px-6 py-2 bg-emerald-50 rounded-full">
                                                    <span
                                                        class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse mr-3"></span>
                                                    <span
                                                        class="text-[11px] font-black text-emerald-700 uppercase tracking-tighter">
                                                        {{ $total }} Presensi Terdata
                                                    </span>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="py-40 text-center font-black text-gray-300 uppercase tracking-widest italic text-2xl">
                                        No Data Recorded</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="content-siswa" class="tab-content hidden animate-fade-in">
                <div
                    class="bg-white rounded-[48px] shadow-[0_40px_80px_-20px_rgba(0,0,0,0.05)] border border-gray-100/50 overflow-hidden text-center">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-0 divide-x divide-y divide-gray-50">
                        @foreach (\App\Models\Siswa::all() as $s)
                            <div class="group p-10 hover:bg-gray-50 transition-all duration-500 relative">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 rounded-[32px] bg-red-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                                        <i data-lucide="user" class="w-8 h-8 text-red-600"></i>
                                    </div>
                                    <h4 class="font-black text-gray-900 text-xl tracking-tighter mb-1 uppercase italic">
                                        {{ $s->nama }}</h4>
                                    <code
                                        class="text-xs font-black text-gray-400 tracking-[0.2em] mb-8">{{ $s->nisn }}</code>
                                    <div class="flex gap-3 mt-auto">
                                        <button
                                            onclick="editSiswa({{ $s->id }}, '{{ $s->nama }}', '{{ $s->nisn }}')"
                                            class="p-4 bg-white shadow-xl rounded-2xl text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </button>
                                        <button onclick="confirmDelete({{ $s->id }})"
                                            class="p-4 bg-white shadow-xl rounded-2xl text-red-600 hover:bg-red-600 hover:text-white transition-all">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $s->id }}"
                                        action="{{ route('siswa.destroy', $s->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalSiswa" class="fixed inset-0 z-[100] hidden">
        <div class="flex items-center justify-center min-h-screen px-4 py-20">
            <div class="fixed inset-0 bg-gray-900/90 backdrop-blur-xl" onclick="closeModal('modalSiswa')"></div>
            <div
                class="relative bg-white rounded-[60px] shadow-2xl max-w-xl w-full p-16 overflow-hidden transform transition-all">
                <header class="flex justify-between items-center mb-12">
                    <h3 id="modalTitle" class="text-4xl font-black text-gray-900 tracking-tighter italic uppercase">
                        Input <span class="text-red-600">Siswa</span></h3>
                    <button onclick="closeModal('modalSiswa')"
                        class="p-4 bg-gray-100 rounded-full hover:bg-red-100 transition-colors">
                        <i data-lucide="x" class="w-6 h-6 text-gray-400 hover:text-red-600"></i>
                    </button>
                </header>
                <form id="siswaForm" action="{{ route('siswa.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <div id="methodField"></div>
                    <div class="space-y-6">
                        <div class="group">
                            <label
                                class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-3 ml-2">Display
                                Name</label>
                            <input type="text" name="nama" id="formNama" required
                                placeholder="Enter full name"
                                class="w-full px-8 py-6 rounded-[30px] border-2 border-gray-100 bg-gray-50 focus:bg-white focus:border-red-500 focus:ring-0 transition-all font-bold text-gray-900 text-lg">
                        </div>
                        <div class="group">
                            <label
                                class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-3 ml-2">Identity
                                Number (NISN)</label>
                            <input type="number" name="nisn" id="formNisn" required placeholder="001234..."
                                class="w-full px-8 py-6 rounded-[30px] border-2 border-gray-100 bg-gray-50 focus:bg-white focus:border-red-500 focus:ring-0 transition-all font-black text-gray-900 font-mono text-xl tracking-[0.2em]">
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full mt-10 py-8 bg-gray-900 hover:bg-red-600 text-white rounded-[35px] font-black shadow-2xl transition-all active:scale-95 uppercase tracking-[0.3em] text-xs">Push
                        to Database</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .active-tab {
            background: white !important;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            transform: scale(1.05);
            color: #111827;
        }

        .inactive-tab {
            color: #9ca3af;
        }

        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        input[type="month"]::-webkit-calendar-picker-indicator {
            filter: invert(0.5);
        }
    </style>

    <script>
        lucide.createIcons();

        function switchTab(tab) {
            document.getElementById('content-rekap').classList.toggle('hidden', tab !== 'rekap');
            document.getElementById('content-siswa').classList.toggle('hidden', tab !== 'siswa');
            const rekapBtn = document.getElementById('tab-rekap');
            const siswaBtn = document.getElementById('tab-siswa');
            if (tab === 'rekap') {
                rekapBtn.classList.add('active-tab');
                rekapBtn.classList.remove('inactive-tab');
                siswaBtn.classList.remove('active-tab');
                siswaBtn.classList.add('inactive-tab');
            } else {
                siswaBtn.classList.add('active-tab');
                siswaBtn.classList.remove('inactive-tab');
                rekapBtn.classList.remove('active-tab');
                rekapBtn.classList.add('inactive-tab');
            }
        }

        switchTab('rekap');

        function exportData() {
            const bulan = document.getElementById('filterBulan').value;
            window.location.href = `{{ route('absen.export') }}?bulan=${bulan}`;
        }

        function openModal(type) {
            document.getElementById('modalSiswa').classList.remove('hidden');
            if (type === 'modalTambahSiswa') {
                document.getElementById('modalTitle').innerHTML = 'Input <span class="text-red-600">Siswa</span>';
                document.getElementById('siswaForm').action = "{{ route('siswa.store') }}";
                document.getElementById('methodField').innerHTML = "";
                document.getElementById('formNama').value = "";
                document.getElementById('formNisn').value = "";
            }
        }

        function editSiswa(id, nama, nisn) {
            document.getElementById('modalSiswa').classList.remove('hidden');
            document.getElementById('modalTitle').innerHTML = 'Update <span class="text-blue-600">Siswa</span>';
            document.getElementById('siswaForm').action = `/siswa/${id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('formNama').value = nama;
            document.getElementById('formNisn').value = nisn;
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function confirmDelete(id) {
            if (confirm('TERMINATE RECORD? This action cannot be undone!')) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        }
    </script>
</x-app-layout>
