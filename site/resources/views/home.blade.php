@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pt-24 sm:pt-36 pb-20 sm:pb-28 text-center">
        <p class="anim-rise font-mono text-xs sm:text-sm accent-text mb-4">$ kimi /plugins install — seo suite loaded</p>
        <h1 class="anim-rise anim-d1 text-4xl sm:text-6xl font-bold tracking-tight heading">Kimi SEO</h1>
        <p class="anim-rise anim-d2 mt-4 text-lg sm:text-xl muted max-w-2xl mx-auto leading-relaxed">
            A full SEO analysis suite for <span class="heading">Kimi Code CLI</span> —
            audits, technical checks, content quality, schema, backlinks and more,
            right from your terminal.
        </p>

        <div class="anim-rise anim-d3 mt-10 max-w-xl mx-auto">
            <div class="rounded-xl border border-zinc-200 dark:border-ink-700 bg-ink-900 overflow-hidden text-left shadow-sm dark:shadow-none">
                <div class="flex items-center gap-1.5 px-4 py-2.5 border-b border-ink-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-ink-600"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-ink-600"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-ink-600"></span>
                    <span class="ml-2 text-[11px] font-mono text-ink-400">kimi</span>
                    <button type="button" data-copy-command aria-label="Copy install command" title="Copy install command"
                            class="copy-btn relative ml-auto inline-flex items-center justify-center w-6 h-6 rounded text-ink-400 hover:text-ink-100 hover:bg-ink-700 transition-colors">
                        <svg class="icon-copy w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25"/>
                        </svg>
                        <svg class="icon-check w-3.5 h-3.5 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                        <span class="copy-tooltip absolute -top-7 right-0 rounded bg-ink-700 px-2 py-0.5 text-[10px] font-mono text-ink-100 whitespace-nowrap">Copied!</span>
                    </button>
                </div>
                <div class="px-4 py-4 font-mono text-xs sm:text-sm overflow-x-auto">
                    <span class="text-accent-400">❯</span>
                    <span class="terminal-typing text-ink-100">/plugins install https://github.com/bentocodeing/kimi-seo</span>
                </div>
            </div>
        </div>

        <div class="anim-rise anim-d4 mt-10 flex flex-col sm:flex-row items-center justify-center gap-3">
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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 grid grid-cols-3 gap-4 text-center" data-reveal>
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
        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-center heading" data-reveal>Everything an SEO audit needs</h2>
        <p class="mt-3 text-center muted max-w-2xl mx-auto" data-reveal>
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
                <div class="card p-5 hover:border-accent-600/50 transition-colors" data-reveal data-reveal-delay="{{ $loop->index % 3 + 1 }}">
                    <p class="font-mono text-sm accent-text">{{ $command }}</p>
                    <p class="mt-2 text-sm muted-strong leading-relaxed">{{ $description }}</p>
                </div>
            @endforeach
        </div>

        <p class="mt-8 text-center text-sm muted" data-reveal>
            Plus sitemaps, images, local SEO, maps, hreflang, e-commerce, programmatic SEO and
            <a href="{{ route('docs.show', 'commands') }}" class="accent-text-hover underline underline-offset-2">much more</a>.
        </p>
    </section>

    {{-- Architecture diagram --}}
    <section class="border-y border-zinc-200 bg-zinc-100/60 dark:border-ink-800 dark:bg-ink-900/40 scroll-mt-16" id="how-it-works">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-center heading" data-reveal>How Kimi SEO works</h2>
            <p class="mt-3 text-center muted max-w-2xl mx-auto" data-reveal>
                One command in your terminal fans out to specialized skills — and comes back as a single, actionable report.
            </p>

            <div class="mt-10 max-w-3xl mx-auto" data-reveal>
                <!-- Data-flow diagram: command -> orchestrator -> skills -> report.
                     Each connector is two paths: a faint base line that draws itself
                     on scroll (.diagram-draw, pathLength=100) and an accent dash line
                     with a continuous flow animation (.diagram-flow) on top. -->
                <svg viewBox="0 0 760 320" class="w-full h-auto" role="img" aria-label="Diagram: a /seo audit command flows through the Kimi SEO orchestrator to specialized skills and converges into an actionable report">
                    <!-- Connector: input -> orchestrator -->
                    <path d="M 160 160 L 240 160" pathLength="100" class="diagram-draw fill-none stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <path d="M 160 160 L 240 160" class="diagram-flow fill-none stroke-accent-500" stroke-width="1.5"/>

                    <!-- Connectors: orchestrator -> skill nodes (5 fan-out curves) -->
                    <path d="M 410 160 C 445 160, 445 40, 480 40" pathLength="100" class="diagram-draw fill-none stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <path d="M 410 160 C 445 160, 445 40, 480 40" class="diagram-flow fill-none stroke-accent-500" stroke-width="1.5"/>
                    <path d="M 410 160 C 445 160, 445 100, 480 100" pathLength="100" class="diagram-draw fill-none stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <path d="M 410 160 C 445 160, 445 100, 480 100" class="diagram-flow fill-none stroke-accent-500" stroke-width="1.5"/>
                    <path d="M 410 160 L 480 160" pathLength="100" class="diagram-draw fill-none stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <path d="M 410 160 L 480 160" class="diagram-flow fill-none stroke-accent-500" stroke-width="1.5"/>
                    <path d="M 410 160 C 445 160, 445 220, 480 220" pathLength="100" class="diagram-draw fill-none stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <path d="M 410 160 C 445 160, 445 220, 480 220" class="diagram-flow fill-none stroke-accent-500" stroke-width="1.5"/>
                    <path d="M 410 160 C 445 160, 445 280, 480 280" pathLength="100" class="diagram-draw fill-none stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <path d="M 410 160 C 445 160, 445 280, 480 280" class="diagram-flow fill-none stroke-accent-500" stroke-width="1.5"/>

                    <!-- Connectors: skill nodes -> report (5 converging curves) -->
                    <path d="M 620 40 C 635 40, 635 160, 650 160" pathLength="100" class="diagram-draw fill-none stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <path d="M 620 40 C 635 40, 635 160, 650 160" class="diagram-flow fill-none stroke-accent-500" stroke-width="1.5"/>
                    <path d="M 620 100 C 635 100, 635 160, 650 160" pathLength="100" class="diagram-draw fill-none stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <path d="M 620 100 C 635 100, 635 160, 650 160" class="diagram-flow fill-none stroke-accent-500" stroke-width="1.5"/>
                    <path d="M 620 160 L 650 160" pathLength="100" class="diagram-draw fill-none stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <path d="M 620 160 L 650 160" class="diagram-flow fill-none stroke-accent-500" stroke-width="1.5"/>
                    <path d="M 620 220 C 635 220, 635 160, 650 160" pathLength="100" class="diagram-draw fill-none stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <path d="M 620 220 C 635 220, 635 160, 650 160" class="diagram-flow fill-none stroke-accent-500" stroke-width="1.5"/>
                    <path d="M 620 280 C 635 280, 635 160, 650 160" pathLength="100" class="diagram-draw fill-none stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <path d="M 620 280 C 635 280, 635 160, 650 160" class="diagram-flow fill-none stroke-accent-500" stroke-width="1.5"/>

                    <!-- Pulse halos behind the nodes -->
                    <circle cx="325" cy="160" r="34" class="diagram-node-halo fill-accent-500"/>
                    <circle cx="550" cy="40" r="22" class="diagram-node-halo diagram-halo-d1 fill-accent-500"/>
                    <circle cx="550" cy="100" r="22" class="diagram-node-halo diagram-halo-d2 fill-accent-500"/>
                    <circle cx="550" cy="160" r="22" class="diagram-node-halo diagram-halo-d3 fill-accent-500"/>
                    <circle cx="550" cy="220" r="22" class="diagram-node-halo diagram-halo-d4 fill-accent-500"/>
                    <circle cx="550" cy="280" r="22" class="diagram-node-halo diagram-halo-d5 fill-accent-500"/>
                    <circle cx="700" cy="160" r="30" class="diagram-node-halo diagram-halo-d6 fill-accent-500"/>

                    <!-- Input node -->
                    <rect x="10" y="138" width="150" height="44" rx="8" class="fill-white dark:fill-ink-900 stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <text x="85" y="156" text-anchor="middle" class="fill-zinc-500 dark:fill-ink-400" font-size="10">You</text>
                    <text x="85" y="172" text-anchor="middle" class="font-mono fill-zinc-800 dark:fill-ink-100" font-size="12">/seo audit &lt;url&gt;</text>

                    <!-- Orchestrator node -->
                    <rect x="240" y="132" width="170" height="56" rx="10" class="fill-white dark:fill-ink-900 stroke-accent-500" stroke-width="1.5"/>
                    <text x="325" y="155" text-anchor="middle" class="fill-zinc-800 dark:fill-ink-100" font-size="13" font-weight="600">Kimi SEO</text>
                    <text x="325" y="173" text-anchor="middle" class="fill-zinc-500 dark:fill-ink-400" font-size="11">orchestrator</text>

                    <!-- Skill nodes -->
                    <rect x="480" y="23" width="140" height="34" rx="8" class="fill-white dark:fill-ink-900 stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <text x="550" y="44" text-anchor="middle" class="font-mono fill-zinc-800 dark:fill-ink-100" font-size="11">/seo audit</text>
                    <rect x="480" y="83" width="140" height="34" rx="8" class="fill-white dark:fill-ink-900 stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <text x="550" y="104" text-anchor="middle" class="font-mono fill-zinc-800 dark:fill-ink-100" font-size="11">/seo technical</text>
                    <rect x="480" y="143" width="140" height="34" rx="8" class="fill-white dark:fill-ink-900 stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <text x="550" y="164" text-anchor="middle" class="font-mono fill-zinc-800 dark:fill-ink-100" font-size="11">/seo content</text>
                    <rect x="480" y="203" width="140" height="34" rx="8" class="fill-white dark:fill-ink-900 stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <text x="550" y="224" text-anchor="middle" class="font-mono fill-zinc-800 dark:fill-ink-100" font-size="11">/seo schema</text>
                    <rect x="480" y="263" width="140" height="34" rx="8" class="fill-white dark:fill-ink-900 stroke-zinc-300 dark:stroke-ink-600" stroke-width="1.5"/>
                    <text x="550" y="284" text-anchor="middle" class="font-mono fill-zinc-800 dark:fill-ink-100" font-size="11">/seo geo</text>

                    <!-- Report node -->
                    <rect x="650" y="138" width="100" height="44" rx="8" class="fill-white dark:fill-ink-900 stroke-accent-500" stroke-width="1.5"/>
                    <text x="700" y="156" text-anchor="middle" class="fill-zinc-800 dark:fill-ink-100" font-size="12" font-weight="600">Actionable</text>
                    <text x="700" y="172" text-anchor="middle" class="fill-zinc-800 dark:fill-ink-100" font-size="12" font-weight="600">report</text>
                </svg>
            </div>
        </div>
    </section>

    {{-- Demo --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20" id="demo">
        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-center heading" data-reveal>See it in action</h2>
        <p class="mt-3 text-center muted max-w-2xl mx-auto" data-reveal>
            Real Kimi SEO sessions inside Kimi Code CLI — from a single command to a full audit report.
        </p>

        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            <figure data-reveal data-reveal-delay="1">
                <img src="/media/assets/demo-command.svg"
                     alt="Animated terminal demo: running a /seo command in Kimi Code CLI with Kimi SEO loaded"
                     class="w-full h-auto rounded-xl border border-zinc-200 dark:border-ink-700 shadow-sm dark:shadow-none" loading="lazy">
                <figcaption class="mt-2 text-center text-xs muted">Running a command</figcaption>
            </figure>
            <figure data-reveal data-reveal-delay="2">
                <img src="/media/assets/demo-audit.svg"
                     alt="Animated terminal demo: a /seo audit run fanning out to subagents and producing a prioritized report"
                     class="w-full h-auto rounded-xl border border-zinc-200 dark:border-ink-700 shadow-sm dark:shadow-none" loading="lazy">
                <figcaption class="mt-2 text-center text-xs muted">A full audit run</figcaption>
            </figure>
        </div>
    </section>

    {{-- Attribution --}}
    <section class="border-b border-zinc-200 dark:border-ink-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-16 text-center" data-reveal>
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
        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-center heading" data-reveal>Support the project</h2>
        <p class="mt-3 text-center muted max-w-2xl mx-auto" data-reveal>
            Both the upstream project and this fork are free and open source. If they help you, consider giving back.
        </p>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 max-w-3xl mx-auto">
            <div class="card p-6 flex flex-col" data-reveal data-reveal-delay="1">
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
            <div class="card p-6 flex flex-col" data-reveal data-reveal-delay="2">
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
        <div class="max-w-sm mx-auto" data-reveal>
            <x-ad-slot :ad="$ad" />
        </div>
    </section>
@endsection
