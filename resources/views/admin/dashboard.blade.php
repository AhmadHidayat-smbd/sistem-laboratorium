@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="mb-10 animate-fade-in">
    <h1 class="text-4xl font-black text-gray-800 mb-2 tracking-tight">
        Selamat Datang, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600">{{ Auth::user()->name }}</span>!
    </h1>
    <p class="text-gray-500 text-lg font-medium">Kelola sistem absensi Laboratorium Informatika dengan efisien hari ini.</p>
</div>

<!-- Statistics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
    @php
        $stats = [
            [
                'label' => 'Total Users',
                'value' => $totalUsers,
                'sub' => $totalAdmins . ' Admin / ' . $totalMembers . ' Asisten',
                'icon' => 'ph-fill ph-users-three',
                'color' => 'blue',
                'link' => 'admin.users'
            ],
            [
                'label' => 'Data Mahasiswa',
                'value' => $totalMahasiswa,
                'sub' => 'Mahasiswa Terdaftar',
                'icon' => 'ph-fill ph-student',
                'color' => 'indigo',
                'link' => 'admin.mahasiswa'
            ],
            [
                'label' => 'Mata Kuliah',
                'value' => $totalMatakuliah,
                'sub' => 'Aktif Semester Ini',
                'icon' => 'ph-fill ph-book-bookmark',
                'color' => 'cyan',
                'link' => 'admin.matakuliah'
            ],
            [
                'label' => 'Total Dosen',
                'value' => $totaldosen,
                'sub' => 'Dosen Terdaftar',
                'icon' => 'ph-fill ph-chalkboard-teacher',
                'color' => 'emerald',
                'link' => 'admin.mahasiswa'
            ],
        ];
    @endphp

    @foreach($stats as $stat)
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col hover:shadow-xl hover:scale-[1.02] transition-all duration-500 group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-{{ $stat['color'] }}-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
        
        <div class="flex items-center justify-between mb-8 relative z-10">
            <div class="bg-{{ $stat['color'] }}-100 p-4 rounded-2xl text-{{ $stat['color'] }}-600 group-hover:rotate-6 transition-transform">
                <i class="{{ $stat['icon'] }} text-3xl"></i>
            </div>
            <div class="text-xs font-black text-{{ $stat['color'] }}-500 bg-{{ $stat['color'] }}-50 px-3 py-1 rounded-full uppercase tracking-widest">
                Realtime
            </div>
        </div>
        
        <h3 class="text-5xl font-black text-gray-900 mb-1 tracking-tight">{{ $stat['value'] }}</h3>
        <p class="text-gray-400 font-bold text-xs uppercase tracking-[2px] mb-6">{{ $stat['label'] }}</p>
        
        <div class="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between relative z-10">
            <span class="text-xs font-bold text-gray-500">{{ $stat['sub'] }}</span>
            <a href="{{ route($stat['link']) }}" class="w-10 h-10 bg-gray-900 text-white rounded-xl flex items-center justify-center hover:bg-{{ $stat['color'] }}-600 transition-colors shadow-lg shadow-gray-200">
                <i class="ph-bold ph-arrow-right"></i>
            </a>
        </div>
    </div>
    @endforeach
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">

    <!--Ringkasan Sistem -->
    <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-48 h-48 bg-blue-50/40 rounded-full -translate-y-1/2 -translate-x-1/2 blur-3xl pointer-events-none"></div>

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-black text-gray-800 flex items-center gap-3">
                <span class="w-1.5 h-8 bg-blue-600 rounded-full"></span>
                Ringkasan Data Sistem
            </h2>
            <span class="text-xs font-bold text-blue-500 bg-blue-50 px-3 py-1 rounded-full uppercase tracking-widest">Bar Chart</span>
        </div>

        <div class="relative h-64">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <!-- pie  Users -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 relative overflow-hidden">
        <div class="absolute bottom-0 right-0 w-40 h-40 bg-indigo-50/40 rounded-full translate-y-1/2 translate-x-1/2 blur-3xl pointer-events-none"></div>

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-black text-gray-800 flex items-center gap-3">
                <span class="w-1.5 h-8 bg-indigo-600 rounded-full"></span>
                Admin dan Asisten
            </h2>
            <span class="text-xs font-bold text-indigo-500 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-widest">Pie Chart</span>
        </div>

        <div class="relative h-52 flex items-center justify-center">
            <canvas id="pieChart"></canvas>
        </div>

        <!-- Legend -->
        <div class="mt-6 space-y-2">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>
                    <span class="text-gray-600 font-semibold">Admin</span>
                </div>
                <span class="font-black text-gray-800">{{ $totalAdmins }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-cyan-400 inline-block"></span>
                    <span class="text-gray-600 font-semibold">Asisten</span>
                </div>
                <span class="font-black text-gray-800">{{ $totalMembers }}</span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s ease-out forwards;
    }
</style>
@endpush

@push('scripts')
{{-- Chart.js hanya di-load sekali; guard agar tidak double-load saat wire:navigate --}}
@once
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
@endonce

<script>
(function () {
    // Data dari Blade — di-embed saat render server, aman untuk SPA navigation
    const chartData = {
        totalUsers:     {{ $totalUsers }},
        totalMahasiswa: {{ $totalMahasiswa }},
        totalMatakuliah:{{ $totalMatakuliah }},
        totaldosen:     {{ $totaldosen }},
        totalAdmins:    {{ $totalAdmins }},
        totalMembers:   {{ $totalMembers }},
    };

    function destroyChart(id) {
        const existing = Chart.getChart(id);
        if (existing) existing.destroy();
    }

    function initCharts() {
        // Pastikan canvas ada di DOM (hanya jalan di halaman dashboard)
        if (!document.getElementById('barChart')) return;

        // ── Destroy instance lama sebelum re-init ──────────────
        destroyChart('barChart');
        destroyChart('pieChart');

        // ── Bar Chart ──────────────────────────────────────────
        new Chart(document.getElementById('barChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Total Users', 'Mahasiswa', 'Mata Kuliah', 'Dosen'],
                datasets: [{
                    label: 'Jumlah',
                    data: [
                        chartData.totalUsers,
                        chartData.totalMahasiswa,
                        chartData.totalMatakuliah,
                        chartData.totaldosen,
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.85)',
                        'rgba(99, 102, 241, 0.85)',
                        'rgba(6, 182, 212, 0.85)',
                        'rgba(16, 185, 129, 0.85)',
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(99, 102, 241, 1)',
                        'rgba(6, 182, 212, 1)',
                        'rgba(16, 185, 129, 1)',
                    ],
                    borderWidth: 2,
                    borderRadius: 12,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#94a3b8',
                        bodyColor: '#f1f5f9',
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: ctx => `  ${ctx.parsed.y} data`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { weight: '700', size: 12 }, color: '#94a3b8' }
                    },
                    y: {
                        grid: { color: 'rgba(148, 163, 184, 0.1)' },
                        border: { display: false, dash: [4, 4] },
                        ticks: { font: { weight: '600', size: 11 }, color: '#cbd5e1', precision: 0 },
                        beginAtZero: true
                    }
                }
            }
        });

        // ── Pie / Doughnut Chart ───────────────────────────────
        new Chart(document.getElementById('pieChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Admin', 'Asisten'],
                datasets: [{
                    data: [chartData.totalAdmins, chartData.totalMembers],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.9)',
                        'rgba(6, 182, 212, 0.85)',
                    ],
                    borderColor: ['#ffffff', '#ffffff'],
                    borderWidth: 3,
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#94a3b8',
                        bodyColor: '#f1f5f9',
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: ctx => `  ${ctx.label}: ${ctx.parsed} orang`
                        }
                    }
                }
            }
        });
    }

    // ── Event listeners ────────────────────────────────────────
    // 'livewire:navigated' → dipanggil setiap kali wire:navigate selesai swap DOM
    document.addEventListener('livewire:navigated', initCharts);

    // Fallback: load pertama kali (sebelum Livewire aktif / tanpa SPA)
    document.addEventListener('DOMContentLoaded', initCharts);
})();
</script>
@endpush