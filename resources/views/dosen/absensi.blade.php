@extends('layouts.dosen')

@section('title', 'Presensi Mahasiswa')
@section('page_title', 'Presensi Mahasiswa')
@section('page_subtitle', 'Kelola data kehadiran mahasiswa per mata kuliah.')

@section('content')
<div class="px-6 py-8">
    <livewire:dosen-attendance-list />
</div>
@endsection

@push('scripts')
<script>
// Global Export Loader Handler
function handleExport(url) {
    Swal.fire({
        title: 'Mempersiapkan File...',
        html: `
            <div class="flex flex-col items-center gap-4 py-4">
                <div class="w-20 h-20 border-4 border-blue-100 border-t-emerald-500 rounded-full animate-spin"></div>
                <p class="text-gray-500 font-medium animate-pulse">Sedang mengolah data Excel Anda</p>
            </div>
        `,
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            // Redirect to download URL
            window.location.href = url;
            
            // Close alert after delay (assuming download starts)
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Selesai!',
                    text: 'File Excel sedang diunduh.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 3000);
        }
    });
}
</script>
@endpush

@push('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.8s ease-out forwards;
    }
    
    /* Tambahan glass card style jika belum ada di global css */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
</style>
@endpush
