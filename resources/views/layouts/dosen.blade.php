<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Dosen') - iTlabs Dosen</title>
    <link rel="icon" href="{{ asset('images/logoit.png') }}" type="image/png">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #f8fafc;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .sidebar-item-active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }
        
        .sidebar-item {
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: #3b82f6;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .sidebar-item-active::before {
            transform: scaleY(1);
        }
        
        /* Sidebar Collapse Styles */
        #sidebar {
            transition: width 0.3s ease;
        }
        
        #sidebar.collapsed {
            width: 80px;
        }
        
        #sidebar.collapsed .sidebar-text,
        #sidebar.collapsed .logo-text,
        #sidebar.collapsed .user-info,
        #sidebar.collapsed .menu-label {
            opacity: 0;
            width: 0;
            overflow: hidden;
            transition: opacity 0.2s ease;
        }
        
        #sidebar:not(.collapsed) .sidebar-text,
        #sidebar:not(.collapsed) .logo-text,
        #sidebar:not(.collapsed) .user-info,
        #sidebar:not(.collapsed) .menu-label {
            opacity: 1;
            width: auto;
            transition: opacity 0.3s ease 0.1s;
        }
        
        #sidebar.collapsed .nav-item {
            justify-content: center;
        }
        
        #sidebar.collapsed .logo-container {
            justify-content: center;
        }
        
        #sidebar.collapsed .user-profile {
            padding: 1rem;
        }
        
        #sidebar.collapsed .menu-label {
            display: none;
        }
        
        .toggle-btn {
            transition: transform 0.3s ease;
        }
        
        .toggle-btn.rotated {
            transform: rotate(180deg);
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
        
        .menu-group {
            margin-bottom: 1.5rem;
        }
        
        .menu-label {
            padding: 0 1rem;
            margin-bottom: 0.5rem;
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
    </style>
    @livewireStyles
    @stack('styles')
</head>
<body class="min-h-screen">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-xl transition-all duration-300 transform md:relative md:translate-x-0 -translate-x-full border-r border-gray-200">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center gap-3 logo-container">
                        <div class="relative flex-shrink-0">
                            <img src="{{ asset('images/logoit.png') }}" 
                                 alt="Logo iTlabs" 
                                 class="h-12 w-auto bg-white p-1.5 rounded-xl shadow-md border border-gray-100">
                        </div>
                        <div class="logo-text">
                            <h1 class="text-base font-black text-gray-900 tracking-tight">
                                iTlabs <span class="text-blue-600">Dosen</span>
                            </h1>
                            <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider">Portal Dosen</p>
                        </div>
                    </div>
                    <!-- Toggle Button (Desktop Only) -->
                    <button id="toggleCollapse" class="hidden md:flex items-center justify-center w-full h-9 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-lg mt-4 transition-colors toggle-btn">
                        <i class="ph-bold ph-caret-left text-base"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 overflow-y-auto custom-scrollbar">
                    
                    <!-- Overview -->
                    <div class="menu-group">
                        <div class="menu-label">Overview</div>
                        <a href="{{ route('dosen.dashboard') }}" wire:navigate
                           class="sidebar-item nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dosen.dashboard') ? 'sidebar-item-active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="ph-fill ph-house text-[20px] flex-shrink-0"></i>
                            <span class="font-semibold text-sm sidebar-text">Dashboard</span>
                        </a>
                    </div>

                    <!-- Presensi -->
                    <div class="menu-group">
                        <div class="menu-label">Presensi</div>
                        <a href="{{ route('dosen.absensi') }}" wire:navigate
                           class="sidebar-item nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dosen.absensi') || request()->routeIs('dosen.absensi.edit') ? 'sidebar-item-active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="ph-fill ph-users-three text-[20px] flex-shrink-0"></i>
                            <span class="font-semibold text-sm sidebar-text">Absensi Mahasiswa</span>
                        </a>

                        <a href="{{ route('dosen.absensi-saya') }}" wire:navigate
                           class="sidebar-item nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 mt-1 {{ request()->routeIs('dosen.absensi-saya') ? 'sidebar-item-active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="ph-fill ph-clipboard-text text-[20px] flex-shrink-0"></i>
                            <span class="font-semibold text-sm sidebar-text">Absensi Saya</span>
                        </a>
                    </div>

                    <!-- Penilaian -->
                    <div class="menu-group">
                        <div class="menu-label">Penilaian</div>
                        <a href="{{ route('dosen.nilai-responsi') }}" wire:navigate
                           class="sidebar-item nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dosen.nilai-responsi') ? 'sidebar-item-active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="ph-fill ph-medal text-[20px] flex-shrink-0"></i>
                            <span class="font-semibold text-sm sidebar-text">Nilai Responsi</span>
                        </a>
                    </div>

                    <!-- Akun -->
                    <div class="menu-group">
                        <div class="menu-label">Akun</div>
                        <a href="{{ route('dosen.profile') }}" wire:navigate
                           class="sidebar-item nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dosen.profile') ? 'sidebar-item-active' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="ph-fill ph-user-circle text-[20px] flex-shrink-0"></i>
                            <span class="font-semibold text-sm sidebar-text">Profil Saya</span>
                        </a>
                    </div>
                </nav>

                <!-- User Profile -->
                <div class="p-4 bg-gray-50 border-t border-gray-200 user-profile">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="relative flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-md">
                                {{ strtoupper(substr(Auth::guard('dosen')->user()->nama, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 user-info">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::guard('dosen')->user()->nama }}</p>
                            <p class="text-[10px] font-medium text-gray-500 truncate">{{ Auth::guard('dosen')->user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('dosen.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition-all duration-200">
                            <i class="ph-bold ph-sign-out text-base flex-shrink-0"></i>
                            <span class="sidebar-text">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 bg-gray-50 overflow-hidden">
            <!-- Header -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <button id="toggleSidebar" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="ph-bold ph-list text-2xl text-gray-700"></i>
                    </button>
                    <!-- Small Logo for Mobile -->
                    <img src="{{ asset('images/logoit.png') }}" alt="Logo" class="h-10 w-auto bg-white p-1 rounded-lg shadow-sm border border-gray-100 md:hidden">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            @yield('page_title', 'Dashboard')
                        </h2>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div id="clock" class="hidden sm:flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-100 text-blue-700 rounded-lg font-semibold text-sm">
                        <i class="ph-bold ph-clock text-base"></i>
                        <span>--:-- WIB</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 font-semibold rounded-r-lg animate-fade-in flex items-center gap-3">
                        <div class="bg-emerald-500 p-2 rounded-lg">
                            <i class="ph-fill ph-check-circle text-lg text-white"></i>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 font-semibold rounded-r-lg animate-fade-in flex items-center gap-3">
                        <div class="bg-rose-500 p-2 rounded-lg">
                            <i class="ph-fill ph-warning-circle text-lg text-white"></i>
                        </div>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
            
            <!-- Footer -->
            <footer class="h-14 bg-white border-t border-gray-200 flex items-center justify-center text-sm font-medium text-gray-500 px-6">
                <div class="flex items-center gap-2">
                    <span>&copy; 2026</span>
                    <span class="text-blue-600 font-bold">iTlabs</span>
                    <span>Janabadra. All rights reserved.</span>
                </div>
            </footer>
        </main>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {
            // Clock
            function updateClock() {
                const now = new Date();
                const timeStr = now.toLocaleTimeString('id-ID', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: false 
                });
                const clockElement = document.querySelector('#clock span');
                if (clockElement) {
                    clockElement.innerText = timeStr + ' WIB';
                }
            }
            if (window.clockInterval) clearInterval(window.clockInterval);
            window.clockInterval = setInterval(updateClock, 1000);
            updateClock();

            // Sidebar Toggle (Mobile)
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            if (toggleBtn && sidebar) {
                toggleBtn.onclick = () => {
                    sidebar.classList.toggle('-translate-x-full');
                };
                
                document.onclick = (e) => {
                    if (window.innerWidth < 768) {
                        if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                            sidebar.classList.add('-translate-x-full');
                        }
                    }
                };
            }

            // Sidebar Collapse/Expand (Desktop)
            const toggleCollapse = document.getElementById('toggleCollapse');
            if (toggleCollapse && sidebar) {
                const isCollapsed = localStorage.getItem('dosenSidebarCollapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    toggleCollapse.classList.add('rotated');
                }

                toggleCollapse.onclick = () => {
                    sidebar.classList.toggle('collapsed');
                    toggleCollapse.classList.toggle('rotated');
                    
                    const collapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('dosenSidebarCollapsed', collapsed);
                };
            }

            // SweetAlert for delete confirmation
            window.confirmDelete = function(formId) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-[2rem] border-0',
                        confirmButton: 'px-8 py-4 rounded-xl font-bold bg-rose-600 text-white hover:bg-rose-700 transition-all mx-2',
                        cancelButton: 'px-8 py-4 rounded-xl font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all mx-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                })
            }
        });
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
