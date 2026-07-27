@extends('layouts.docs')

@section('title', 'Documentation — Kimi SEO')

@section('docs_content')
    <h1 class="text-3xl font-bold tracking-tight heading">Documentation</h1>
    <p class="mt-3 muted leading-relaxed">
        Everything you need to install, configure and master Kimi SEO — rendered straight from the
        repository's markdown files, so the docs you read here are always the docs that ship with the plugin.
    </p>

    <div class="mt-8 grid gap-3 sm:grid-cols-2">
        @foreach ($pages as $pageSlug => $page)
            <a href="{{ route('docs.show', $pageSlug) }}"
               class="card px-5 py-4 hover:border-accent-600/60 transition-colors group">
                <span class="font-semibold heading group-hover:text-accent-700 dark:group-hover:text-accent-300 transition-colors">{{ $page['title'] }}</span>
                <span class="block mt-1 font-mono text-xs muted">/docs/{{ $pageSlug }}</span>
            </a>
        @endforeach
    </div>

    <div class="mt-10 card p-6">
        <p class="font-mono text-xs uppercase tracking-widest accent-text mb-2">Attribution</p>
        <p class="text-sm muted-strong leading-relaxed">
            Kimi SEO is a free, open-source community fork of
            <a href="{{ config('kimiseo.upstream_url') }}" target="_blank" rel="noopener" class="accent-text-hover underline underline-offset-2">claude-seo</a>
            by AgriciDaniel, adapted for Kimi Code CLI and kept in sync with upstream releases.
            It is not affiliated with Moonshot AI.
        </p>
    </div>
@endsection
