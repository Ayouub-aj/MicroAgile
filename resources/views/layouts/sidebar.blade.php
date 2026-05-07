<aside class="w-64 bg-white shadow-lg border-r border-gray-200 hidden md:block" x-data="{ open: true }">
    <div class="h-full flex flex-col">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <span class="text-xl font-bold text-white">MicroAgile</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home w-5 mr-3"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Projects -->
            <a href="{{ route('projects.index') }}"
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('projects.index') || request()->routeIs('projects.show') || request()->routeIs('projects.create') || request()->routeIs('projects.edit') ? 'active' : '' }}">
                <i class="fas fa-folder w-5 mr-3"></i>
                <span class="font-medium">Projets</span>
            </a>

            <!-- Archives -->
            <a href="{{ route('projects.archives') }}"
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('projects.archives') ? 'active' : '' }}">
                <i class="fas fa-archive w-5 mr-3"></i>
                <span class="font-medium">Archives</span>
            </a>

            <div class="my-3 border-t border-gray-200"></div>

            <!-- Quick Actions -->
            <div class="px-4 py-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions rapides</p>
            </div>

            <a href="{{ route('projects.create') }}"
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg">
                <i class="fas fa-plus-circle w-5 mr-3 text-blue-500"></i>
                <span class="font-medium">Nouveau projet</span>
            </a>
        </nav>

        <!-- User Profile -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar -->
<div x-data="{ sidebarOpen: false }" class="md:hidden">
    <!-- Mobile menu button -->
    <button @click="sidebarOpen = true" class="fixed bottom-4 right-4 z-50 bg-blue-600 text-white p-4 rounded-full shadow-lg">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay -->
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40"
         style="display: none;">
    </div>

    <!-- Mobile Sidebar -->
    <aside x-show="sidebarOpen"
           @click.away="sidebarOpen = false"
           x-transition:enter="transition ease-in-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in-out duration-300 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-50"
           style="display: none;">
        <div class="h-full flex flex-col">
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    <span class="text-xl font-bold text-white">MicroAgile</span>
                </a>
                <button @click="sidebarOpen = false" class="text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home w-5 mr-3"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('projects.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('projects.index') || request()->routeIs('projects.show') || request()->routeIs('projects.create') || request()->routeIs('projects.edit') ? 'active' : '' }}">
                    <i class="fas fa-folder w-5 mr-3"></i>
                    <span class="font-medium">Projets</span>
                </a>

                <a href="{{ route('projects.archives') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('projects.archives') ? 'active' : '' }}">
                    <i class="fas fa-archive w-5 mr-3"></i>
                    <span class="font-medium">Archives</span>
                </a>

                <div class="my-3 border-t border-gray-200"></div>

                <div class="px-4 py-2">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions rapides</p>
                </div>

                <a href="{{ route('projects.create') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg">
                    <i class="fas fa-plus-circle w-5 mr-3 text-blue-500"></i>
                    <span class="font-medium">Nouveau projet</span>
                </a>
            </nav>
        </div>
    </aside>
</div>
