<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }
            .sidebar-link {
                transition: all 0.3s ease;
            }
            .sidebar-link:hover {
                background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%);
                border-left: 3px solid #3B82F6;
            }
            .sidebar-link.active {
                background: linear-gradient(90deg, rgba(59, 130, 246, 0.15) 0%, transparent 100%);
                border-left: 3px solid #3B82F6;
                color: #3B82F6;
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navigation -->
                @include('layouts.topnav')

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white shadow-sm border-b border-gray-200">
                            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Alpine.js for interactivity -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Custom Confirm Modal -->
        <div id="confirm-modal"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm"
             role="dialog" aria-modal="true" aria-labelledby="confirm-modal-title">
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 scale-95 transition-transform duration-150" id="confirm-modal-box">
                <div class="flex items-start gap-4 mb-5">
                    <div id="confirm-modal-icon"
                         class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0 bg-red-100">
                        <i id="confirm-modal-icon-i" class="fas fa-trash text-red-600 text-base"></i>
                    </div>
                    <div>
                        <h3 id="confirm-modal-title" class="font-semibold text-gray-900 text-base mb-1">Confirmation</h3>
                        <p id="confirm-modal-message" class="text-sm text-gray-500 leading-relaxed"></p>
                    </div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button id="confirm-modal-cancel"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                        Annuler
                    </button>
                    <button id="confirm-modal-ok"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors shadow-sm">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>

        <script>
        (function () {
            var pendingForm = null;
            var modal = document.getElementById('confirm-modal');
            var msgEl = document.getElementById('confirm-modal-message');
            var iconEl = document.getElementById('confirm-modal-icon');
            var iconI = document.getElementById('confirm-modal-icon-i');
            var okBtn = document.getElementById('confirm-modal-ok');
            var box = document.getElementById('confirm-modal-box');

            function openModal(form, message, danger) {
                pendingForm = form;
                msgEl.textContent = message;
                if (danger) {
                    iconEl.className = 'w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0 bg-red-100';
                    iconI.className = 'fas fa-exclamation-triangle text-red-600 text-base';
                    okBtn.className = 'px-4 py-2 text-sm font-medium text-white bg-red-700 rounded-xl hover:bg-red-800 transition-colors shadow-sm';
                } else {
                    iconEl.className = 'w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0 bg-red-100';
                    iconI.className = 'fas fa-trash text-red-500 text-base';
                    okBtn.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors shadow-sm';
                }
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(function () { box.classList.remove('scale-95'); box.classList.add('scale-100'); }, 10);
            }

            function closeModal() {
                box.classList.remove('scale-100');
                box.classList.add('scale-95');
                setTimeout(function () {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 150);
                pendingForm = null;
            }

            window.confirmDelete = function (form, message, danger) {
                openModal(form, message, danger || false);
            };

            document.getElementById('confirm-modal-cancel').addEventListener('click', closeModal);

            okBtn.addEventListener('click', function () {
                var form = pendingForm;
                closeModal();
                if (form) setTimeout(function () { form.submit(); }, 160);
            });

            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });
        })();
        </script>
    </body>
</html>
