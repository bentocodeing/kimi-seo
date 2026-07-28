<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin — Kimi SEO')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <script>
        (function () {
            var theme = 'light';
            try { theme = localStorage.getItem('theme') || 'light'; } catch (e) {}
            if (theme === 'dark') { document.documentElement.classList.add('dark'); }
            window.toggleTheme = function () {
                var dark = document.documentElement.classList.toggle('dark');
                try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
            };
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased">
    <header data-sticky-header class="site-header sticky top-0 z-40 border-b border-zinc-200 bg-[#fafafa]/80 backdrop-blur dark:border-ink-800 dark:bg-ink-950/80">
        <nav class="max-w-5xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between gap-4 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="font-mono font-semibold heading flex items-center gap-2">
                <x-logo class="w-5 h-5 rounded" /> Kimi SEO <span class="muted">admin</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-accent-700 dark:text-accent-300' : 'nav-link' }}">Dashboard</a>
                <a href="{{ route('admin.ads.index') }}" class="{{ request()->routeIs('admin.ads.*') ? 'text-accent-700 dark:text-accent-300' : 'nav-link' }}">Ads</a>
                <a href="{{ route('admin.inquiries.index') }}" class="{{ request()->routeIs('admin.inquiries.*') ? 'text-accent-700 dark:text-accent-300' : 'nav-link' }}">Inquiries</a>
                <a href="{{ route('home') }}" class="muted hover:text-zinc-900 dark:hover:text-white">&larr; Site</a>
                <x-theme-toggle />
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-sm-outline">Log out</button>
                </form>
            </div>
        </nav>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        @if (session('success'))
            <div class="mb-6 flash-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 flash-error">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
