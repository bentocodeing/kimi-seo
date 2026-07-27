@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pt-16 sm:pt-24 pb-16 text-center">
        <p class="font-mono text-xs sm:text-sm accent-text mb-4">$ kimi /plugins install — seo suite loaded</p>
        <h1 class="text-4xl sm:text-6xl font-bold tracking-tight heading">Kimi SEO</h1>
        <p class="mt-4 text-lg sm:text-xl muted max-w-2xl mx-auto leading-relaxed">
            A full SEO analysis suite for <span class="heading">Kimi Code CLI</span> —
            audits, technical checks, content quality, schema, backlinks and more,
            right from your terminal.
        </p>

        <div class="mt-8 max-w-xl mx-auto">
            <div class="rounded-xl border border-zinc-200 dark:border-ink-700 bg-ink-900 overflow-hidden text-left shadow-sm dark:shadow-none">
                <div class="flex items-center gap-1.5 px-4 py-2.5 border-b border-ink-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-ink-600"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-ink-600"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-ink-600"></span>
                    <span class="ml-2 text-[11px] font-mono text-ink-400">kimi</span>
                </div>
                <div class="px-4 py-4 font-mono text-xs sm:text-sm overflow-x-auto">
                    <span class="text-accent-400">❯</span>
                    <span class="text-ink-100">/plugins install https://github.com/bentocodeing/kimi-seo</span>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('docs.index') }}" class="w-full sm:w-auto btn-primary">
                Read the docs
            </a>
            <a href="{{ config('kimiseo.github_url') }}" target="_blank" rel="noopener" class="w-full sm:w-auto btn-outline">
                View on GitHub
            </a>
        </div>
    </section>

    {{-- Stats --}}
    <section class="border-y border-zinc-200 bg-zinc-100/60 dark:border-ink-800 dark:bg-ink-900/40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 grid grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-3xl sm:text-4xl font-bold font-mono accent-text">25</p>
                <p class="mt-1 text-xs sm:text-sm muted">SEO skills</p>
            </div>
            <div>
                <p class="text-3xl sm:text-4xl font-bold font-mono accent-text">18</p>
                <p class="mt-1 text-xs sm:text-sm muted">subagents</p>
            </div>
            <div>
                <p class="text-3xl sm:text-4xl font-bold font-mono accent-text">53</p>
                <p class="mt-1 text-xs sm:text-sm muted">Python scripts</p>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-center heading">Everything an SEO audit needs</h2>
        <p class="mt-3 text-center muted max-w-2xl mx-auto">
            One plugin, a full toolbox. Each command delegates to specialized skills and subagents.
        </p>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @php
                $features = [
                    ['/seo audit', 'Full website audit with parallel subagent delegation across every SEO category.'],
                    ['/seo page', 'Deep single-page analysis: tags, headings, content, links, performance.'],
                    ['/seo technical', 'Technical SEO audit across 9 categories, from crawlability to Core Web Vitals.'],
                    ['/seo content', 'E-E-A-T and content quality analysis with actionable recommendations.'],
                    ['/seo schema', 'Schema.org detection, validation and JSON-LD generation.'],
                    ['/seo geo', 'Optimize for AI Overviews and generative search engines.'],
                    ['/seo backlinks', 'Backlink profile analysis with free API integrations.'],
                    ['/seo cluster', 'SERP-based semantic clustering and content architecture.'],
                    ['/seo drift', 'Capture baselines and monitor SEO drift over time.'],
                ];
            @endphp
            @foreach ($features as [$command, $description])
                <div class="card p-5 hover:border-accent-600/50 transition-colors">
                    <p class="font-mono text-sm accent-text">{{ $command }}</p>
                    <p class="mt-2 text-sm muted-strong leading-relaxed">{{ $description }}</p>
                </div>
            @endforeach
        </div>

        <p class="mt-8 text-center text-sm muted">
            Plus sitemaps, images, local SEO, maps, hreflang, e-commerce, programmatic SEO and
            <a href="{{ route('docs.show', 'commands') }}" class="accent-text-hover underline underline-offset-2">much more</a>.
        </p>
    </section>

    {{-- Attribution --}}
    <section class="border-y border-zinc-200 bg-zinc-100/60 dark:border-ink-800 dark:bg-ink-900/40">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-16 text-center">
            <p class="font-mono text-xs uppercase tracking-widest accent-text mb-4">Standing on the shoulders of giants</p>
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight heading">A community fork of claude-seo</h2>
            <p class="mt-4 muted-strong leading-relaxed">
                Kimi SEO is a free, open-source community fork of
                <a href="{{ config('kimiseo.upstream_url') }}" target="_blank" rel="noopener" class="accent-text-hover underline underline-offset-2">claude-seo</a>
                by <span class="heading font-medium">AgriciDaniel</span>, adapted to run on Kimi Code CLI.
                It is kept in sync with upstream releases, and all credit for the original work goes to the
                upstream author and contributors.
            </p>
            <p class="mt-3 text-sm muted">
                Kimi SEO is a community project and is not affiliated with Moonshot AI.
            </p>
        </div>
    </section>

    {{-- Support / donate --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-center heading">Support the project</h2>
        <p class="mt-3 text-center muted max-w-2xl mx-auto">
            Both the upstream project and this fork are free and open source. If they help you, consider giving back.
        </p>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 max-w-3xl mx-auto">
            <div class="card p-6 flex flex-col">
                <h3 class="text-lg font-semibold heading">Support upstream</h3>
                <p class="mt-2 text-sm muted leading-relaxed flex-1">
                    Star and contribute to claude-seo, or join AgriciDaniel's AI marketing community.
                </p>
                <div class="mt-4 flex flex-col gap-2">
                    <a href="{{ config('kimiseo.upstream_url') }}" target="_blank" rel="noopener"
                       class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-center text-zinc-800 hover:border-accent-600/60 hover:text-accent-700 transition-colors dark:border-ink-600 dark:text-ink-100 dark:hover:text-accent-300">
                        claude-seo on GitHub
                    </a>
                    <a href="{{ config('kimiseo.upstream_community_url') }}" target="_blank" rel="noopener"
                       class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-center text-zinc-800 hover:border-accent-600/60 hover:text-accent-700 transition-colors dark:border-ink-600 dark:text-ink-100 dark:hover:text-accent-300">
                        AI Marketing Hub community
                    </a>
                </div>
            </div>
            <div class="card p-6 flex flex-col">
                <h3 class="text-lg font-semibold heading">Support Kimi SEO</h3>
                <p class="mt-2 text-sm muted leading-relaxed flex-1">
                    Help keep this fork maintained and in sync with upstream.
                </p>
                <a href="{{ config('kimiseo.donate_url') }}" target="_blank" rel="noopener"
                   class="mt-4 rounded-lg bg-accent-600 px-4 py-2 text-sm font-semibold text-center text-white hover:bg-accent-500 transition-colors">
                    Donate / Sponsor
                </a>
            </div>
        </div>
    </section>

    {{-- Ad slot --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pb-20">
        <div class="max-w-sm mx-auto">
            <x-ad-slot :ad="$ad" />
        </div>
    </section>
@endsection
