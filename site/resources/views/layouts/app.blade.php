<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kimi SEO — SEO analysis suite for Kimi Code CLI')</title>
    <meta name="description" content="@yield('meta_description', 'Kimi SEO is a free, open-source SEO analysis plugin for Kimi Code CLI: 25 skills, 18 subagents, 53 scripts.')">
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
<body class="min-h-screen font-sans antialiased flex flex-col">
    <header data-sticky-header class="site-header sticky top-0 z-40 border-b border-zinc-200 bg-[#fafafa]/80 backdrop-blur dark:border-ink-800 dark:bg-ink-950/80">
        <nav class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-mono text-sm sm:text-base font-semibold tracking-tight heading">
                <x-logo />
                <span class="logo-typing">Kimi&nbsp;SEO</span>
            </a>
            <div class="flex items-center gap-4 sm:gap-6 text-sm">
                <a href="{{ route('docs.index') }}" class="nav-link">Docs</a>
                <a href="{{ route('advertise') }}" class="nav-link">Advertise</a>
                <a href="{{ config('kimiseo.github_url') }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-1.5 nav-link">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844a9.59 9.59 0 0 1 2.504.337c1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.02 10.02 0 0 0 22 12.017C22 6.484 17.522 2 12 2Z" clip-rule="evenodd"/></svg>
                    <span class="hidden sm:inline">GitHub</span>
                </a>
                <x-theme-toggle />
            </div>
        </nav>
    </header>

    <main class="flex-1 w-full">
        @if (session('success'))
            <div class="max-w-6xl mx-auto px-4 sm:px-6 mt-6">
                <div class="flash-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-zinc-200 dark:border-ink-800 mt-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 grid gap-8 sm:grid-cols-3 text-sm">
            <div>
                <p class="font-mono font-semibold mb-2 heading flex items-center gap-2"><x-logo class="w-5 h-5 rounded" /> Kimi SEO</p>
                <p class="muted leading-relaxed">
                    Free, open-source SEO analysis suite for
                    <a href="{{ config('kimiseo.kimi_code_url') }}" target="_blank" rel="noopener" class="muted-strong hover:text-accent-700 dark:hover:text-accent-300 underline underline-offset-2">Kimi Code CLI</a>.
                    Released under the MIT license.
                </p>
            </div>
            <div>
                <p class="font-semibold mb-2 muted-strong">Project</p>
                <ul class="space-y-1.5 muted">
                    <li><a href="{{ config('kimiseo.github_url') }}" target="_blank" rel="noopener" class="hover:text-accent-700 dark:hover:text-accent-300">GitHub repository</a></li>
                    <li><a href="{{ route('docs.index') }}" class="hover:text-accent-700 dark:hover:text-accent-300">Documentation</a></li>
                    <li><a href="{{ route('advertise') }}" class="hover:text-accent-700 dark:hover:text-accent-300">Advertise</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold mb-2 muted-strong">Credits</p>
                <ul class="space-y-1.5 muted">
                    <li>Community fork by <a href="{{ config('kimiseo.github_url') }}" target="_blank" rel="noopener" class="hover:text-accent-700 dark:hover:text-accent-300">bentocodeing</a></li>
                    <li>Upstream <a href="{{ config('kimiseo.upstream_url') }}" target="_blank" rel="noopener" class="hover:text-accent-700 dark:hover:text-accent-300">claude-seo</a> by AgriciDaniel</li>
                    <li>Not affiliated with Moonshot AI</li>
                </ul>
            </div>
        </div>
    </footer>
    <x-back-to-top />
</body>
</html>
