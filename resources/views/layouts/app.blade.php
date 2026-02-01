<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Butik Rental</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Styles (Vite) -->
    @vite('resources/css/app.css')
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased">

    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-deep-charcoal border-b border-gray-800 sticky top-0 z-50 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                                <i data-lucide="crown" class="text-metallic-gold w-6 h-6"></i>
                                <h1 class="text-xl font-bold text-metallic-gold tracking-wide">LuxeBoutique</h1>
                            </a>
                        </div>
                        <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('dashboard') }}" 
                               class="{{ request()->routeIs('dashboard') ? 'border-metallic-gold text-metallic-gold' : 'border-transparent text-gray-300 hover:text-white hover:border-metallic-gold' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                                Dashboard
                            </a>
                            <a href="{{ route('rentals.index') }}" 
                               class="{{ request()->routeIs('rentals.*') || request()->routeIs('returns.*') ? 'border-metallic-gold text-metallic-gold' : 'border-transparent text-gray-300 hover:text-white hover:border-metallic-gold' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                                Transaksi Sewa
                            </a>
                            <a href="{{ route('clothes.index') }}" 
                               class="{{ request()->routeIs('clothes.*') ? 'border-metallic-gold text-metallic-gold' : 'border-transparent text-gray-300 hover:text-white hover:border-metallic-gold' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                                Inventaris Barang
                            </a>
                            <a href="{{ route('categories.index') }}" 
                               class="{{ request()->routeIs('categories.*') ? 'border-metallic-gold text-metallic-gold' : 'border-transparent text-gray-300 hover:text-white hover:border-metallic-gold' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                                Kategori
                            </a>
                            <a href="{{ route('customers.index') }}" 
                               class="{{ request()->routeIs('customers.*') ? 'border-metallic-gold text-metallic-gold' : 'border-transparent text-gray-300 hover:text-white hover:border-metallic-gold' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                                Pelanggan
                            </a>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('employees.index') }}" 
                                   class="{{ request()->routeIs('employees.*') ? 'border-metallic-gold text-metallic-gold' : 'border-transparent text-gray-300 hover:text-white hover:border-metallic-gold' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                                    Karyawan
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button id="theme-toggle" type="button" class="text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-metallic-gold p-2 rounded-lg transition-colors">
                            <i id="theme-icon" data-lucide="moon" class="w-5 h-5"></i>
                        </button>

                        <div class="ml-3 relative">
                        <div class="ml-3 relative flex items-center space-x-4">
                            <div class="flex flex-col items-end">
                                <span class="text-sm font-medium text-gray-700">{{ Auth::user()->nama }}</span>
                                <span class="text-xs text-gray-500 capitalize">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Staf' }}</span>
                            </div>
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase">
                                {{ substr(Auth::user()->nama, 0, 1) }}
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium ml-2" title="Keluar">
                                    <i data-lucide="log-out" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i data-lucide="check-circle" class="h-5 w-5 text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i data-lucide="alert-circle" class="h-5 w-5 text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <ul class="list-disc pl-5 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
        
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} Sistem Butik Rental. Hak Cipta Dilindungi.
                </p>
            </div>
        </footer>
    </div>

    <script>
        // Check for saved theme preference, otherwise use system preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');

        function updateIcon() {
            const isDark = document.documentElement.classList.contains('dark');
            themeIcon.setAttribute('data-lucide', isDark ? 'sun' : 'moon');
            lucide.createIcons();
        }

        // Initial Icon Set
        updateIcon();

        themeToggleBtn.addEventListener('click', function() {
            // Toggle dark mode
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
            updateIcon();
        });
        
        // Re-run icons for other elements
        lucide.createIcons();
    </script>
</body>
</html>
