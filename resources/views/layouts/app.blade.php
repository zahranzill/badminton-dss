<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PB Garles DSS</title>
    <meta name="description" content="Decision Support System untuk Evaluasi Pertandingan Bulutangkis Sektor Ganda">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 antialiased">
    <div class="min-h-screen bg-slate-50 relative">
        {{-- Sidebar Overlay (mobile) --}}
        <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Main Content --}}
        <div class="lg:pl-[260px] flex flex-col min-h-screen">
            {{-- Navbar --}}
            @include('layouts.partials.navbar')

            {{-- Page Content --}}
            <main class="p-4 lg:p-6 page-content">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div id="toast-success" class="toast bg-emerald-500 text-white flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div id="toast-error" class="toast bg-rose-500 text-white flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Global Validation Error Summary --}}
                @if($errors->any())
                    <div class="validation-errors">
                        <h4>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            Terdapat {{ $errors->count() }} kesalahan pada formulir:
                        </h4>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" style="backdrop-filter: blur(2px);">
        <div class="bg-white rounded-xl shadow-2xl p-6 mx-4 max-w-md w-full modal-enter">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-800">Konfirmasi Hapus</h3>
            </div>
            <p class="text-slate-600 mb-6" id="delete-modal-message">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-end gap-3">
                <button onclick="closeDeleteModal()" class="btn btn-outline">Batal</button>
                <form id="delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Scroll to Top Button --}}
    <button id="scroll-top" class="scroll-top-btn no-print" onclick="window.scrollTo({top:0,behavior:'smooth'})" title="Kembali ke atas">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
        </svg>
    </button>

    <script>
        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        // Toast auto-dismiss
        document.querySelectorAll('[id^="toast-"]').forEach(toast => {
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.4s ease forwards';
                setTimeout(() => toast.remove(), 400);
            }, 4000);
        });

        // Delete modal
        function confirmDelete(url, message) {
            const modal = document.getElementById('delete-modal');
            const form = document.getElementById('delete-form');
            const msg = document.getElementById('delete-modal-message');
            form.action = url;
            if (message) msg.textContent = message;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
                // Close finalize modal if exists
                const finalizeModal = document.getElementById('finalize-modal');
                if (finalizeModal) {
                    finalizeModal.classList.add('hidden');
                    finalizeModal.classList.remove('flex');
                }
            }
        });

        // Close modals on backdrop click
        document.getElementById('delete-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        // Scroll to Top Button
        window.addEventListener('scroll', function() {
            const btn = document.getElementById('scroll-top');
            if (window.scrollY > 300) {
                btn.classList.add('visible');
            } else {
                btn.classList.remove('visible');
            }
        });

        // Form submit loading state (prevent double submit)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.classList.contains('loading')) {
                    submitBtn.classList.add('loading');
                    // Re-enable after 5s in case of validation errors
                    setTimeout(() => submitBtn.classList.remove('loading'), 5000);
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
