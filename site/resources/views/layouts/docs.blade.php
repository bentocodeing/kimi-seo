@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 lg:grid lg:grid-cols-[240px_minmax(0,1fr)] xl:grid-cols-[240px_minmax(0,1fr)_220px] lg:gap-10">
        {{-- Mobile: collapsible nav --}}
        <details class="lg:hidden mb-6 card">
            <summary class="px-4 py-3 text-sm font-semibold cursor-pointer select-none heading">Documentation menu</summary>
            <nav class="px-4 pb-4">
                <ul class="space-y-1 text-sm">
                    <li>
                        <a href="{{ route('docs.index') }}" class="block rounded px-2 py-1.5 {{ request()->routeIs('docs.index') ? 'text-accent-700 bg-zinc-100 dark:text-accent-300 dark:bg-ink-800' : 'muted-strong hover:text-zinc-900 dark:hover:text-white' }}">Overview</a>
                    </li>
                    @foreach ($pages as $pageSlug => $page)
                        <li>
                            <a href="{{ route('docs.show', $pageSlug) }}"
                               class="block rounded px-2 py-1.5 {{ isset($slug) && $slug === $pageSlug ? 'text-accent-700 bg-zinc-100 dark:text-accent-300 dark:bg-ink-800' : 'muted-strong hover:text-zinc-900 dark:hover:text-white' }}">
                                {{ $page['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </details>

        {{-- Desktop: sidebar --}}
        <aside class="hidden lg:block">
            <div class="sticky top-8">
                <p class="font-mono text-xs uppercase tracking-widest muted mb-3">Documentation</p>
                <nav>
                    <ul class="space-y-1 text-sm border-l border-zinc-200 dark:border-ink-800">
                        <li>
                            <a href="{{ route('docs.index') }}"
                               class="block -ml-px border-l-2 pl-3 py-1.5 {{ request()->routeIs('docs.index') ? 'border-accent-600 text-accent-700 dark:border-accent-500 dark:text-accent-300' : 'border-transparent muted-strong hover:text-zinc-900 dark:hover:text-white' }}">
                                Overview
                            </a>
                        </li>
                        @foreach ($pages as $pageSlug => $page)
                            <li>
                                <a href="{{ route('docs.show', $pageSlug) }}"
                                   class="block -ml-px border-l-2 pl-3 py-1.5 {{ isset($slug) && $slug === $pageSlug ? 'border-accent-600 text-accent-700 dark:border-accent-500 dark:text-accent-300' : 'border-transparent muted-strong hover:text-zinc-900 dark:hover:text-white' }}">
                                    {{ $page['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                <div class="mt-8">
                    <x-ad-slot :ads="$ads ?? collect()" :visible="1" />
                </div>
            </div>
        </aside>

        <div class="min-w-0">
            @yield('docs_content')

            {{-- Mobile: ad below content --}}
            <div class="lg:hidden mt-10 max-w-sm">
                <x-ad-slot :ads="$ads ?? collect()" :visible="1" />
            </div>
        </div>

        {{-- Right rail: on-page table of contents (xl and up only) --}}
        @if (! empty($toc))
            <aside class="hidden xl:block">
                <nav class="sticky top-24" aria-label="On this page">
                    <p class="font-mono text-xs uppercase tracking-widest muted mb-3">On this page</p>
                    <ul class="space-y-1.5 text-sm border-l border-zinc-200 dark:border-ink-800">
                        @foreach ($toc as $heading)
                            <li>
                                <a href="#{{ $heading['id'] }}"
                                   class="block -ml-px border-l-2 border-transparent py-1 muted-strong hover:text-zinc-900 dark:hover:text-white hover:border-accent-500 transition-colors {{ $heading['level'] === 3 ? 'pl-7' : 'pl-3' }}">
                                    {{ $heading['text'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </aside>
        @endif
    </div>
@endsection
