@extends('layouts.docs')

@section('title', $title . ' — Kimi SEO Docs')

@section('docs_content')
    <article class="prose dark:prose-invert max-w-none
        prose-headings:tracking-tight prose-a:text-accent-700 dark:prose-a:text-accent-400 prose-a:no-underline hover:prose-a:text-accent-600 dark:hover:prose-a:text-accent-300
        prose-code:text-accent-700 dark:prose-code:text-accent-300 prose-code:before:content-none prose-code:after:content-none
        prose-pre:bg-zinc-100 prose-pre:text-zinc-800 prose-pre:border prose-pre:border-zinc-200 dark:prose-pre:bg-ink-900 dark:prose-pre:text-ink-100 dark:prose-pre:border-ink-700
        prose-img:rounded-xl prose-hr:border-zinc-200 dark:prose-hr:border-ink-800 prose-strong:text-zinc-900 dark:prose-strong:text-ink-100
        prose-table:text-sm prose-th:text-left">
        {!! $content !!}
    </article>

    @php
        $slugs = array_keys($pages);
        $position = array_search($slug, $slugs, true);
        $prev = $position > 0 ? $slugs[$position - 1] : null;
        $next = $position < count($slugs) - 1 ? $slugs[$position + 1] : null;
    @endphp

    <nav class="mt-12 pt-6 border-t border-zinc-200 dark:border-ink-800 flex items-center justify-between gap-4 text-sm">
        @if ($prev)
            <a href="{{ route('docs.show', $prev) }}" class="muted-strong hover:text-accent-700 dark:hover:text-accent-300 transition-colors">
                &larr; {{ $pages[$prev]['title'] }}
            </a>
        @else
            <span></span>
        @endif
        @if ($next)
            <a href="{{ route('docs.show', $next) }}" class="muted-strong hover:text-accent-700 dark:hover:text-accent-300 transition-colors">
                {{ $pages[$next]['title'] }} &rarr;
            </a>
        @endif
    </nav>
@endsection
