@extends('layouts.admin')

@section('title', 'Kelola User')
@section('page_title', 'Kelola Asisten')

@section('content')
<!-- Header Section -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10 animate-fade-in">
    <div class="flex-1">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight mb-2 uppercase">Kelola Asisten</h2>
        <p class="text-gray-500 font-medium">Manajemen hak akses dan akun pengguna sistem iTlabs.</p>
    </div>
    
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.create') }}" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-blue-100 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <i class="ph-bold ph-plus-circle text-xl"></i>
            <span>Tambah Asisten Baru</span>
        </a>
    </div>
</div>

<!-- Users Table Card -->
<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden animate-fade-in" style="animation-delay: 0.1s">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">No</th>
                    <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Informasi Asisten</th>
                    <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Email</th>
                    <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    <td class="px-8 py-6">
                        <span class="text-sm font-bold text-gray-400">{{ $loop->iteration }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <div>
                            <h4 class="font-black text-gray-800 tracking-tight">{{ $user->name }}</h4>
                            <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full uppercase tracking-widest">Asisten Aktif</span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-sm font-bold text-gray-500">{{ $user->email }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all duration-300 shadow-sm">
                                <i class="ph-bold ph-note-pencil text-lg"></i>
                            </a>
                            
                            <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-form-{{ $user->id }}')" 
                                        class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all duration-300 shadow-sm">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if($users->isEmpty())
                <tr>
                    <td colspan="4" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center justify-center opacity-20">
                            <i class="ph-bold ph-users-three text-6xl mb-4"></i>
                            <p class="text-xl font-black uppercase tracking-widest">Belum ada user</p>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
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