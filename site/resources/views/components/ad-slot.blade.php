@props(['ads' => collect(), 'visible' => 1])

@php
    $ads = $ads instanceof \Illuminate\Support\Collection ? $ads : collect($ads);
@endphp

{{--
    Public ad slot: carousel of active ads (:visible slides at a time on
    desktop, always 1 on mobile) with a progress bar, and the static
    "Your ad here?" CTA card always below it. Without JS everything
    renders stacked — the carousel hiding is JS-additive only.
--}}
<div {{ $attributes->merge(['class' => '']) }}>
    @if ($ads->isNotEmpty())
        <div data-ad-carousel data-visible="{{ (int) $visible }}">
            <span data-ad-sr-timer role="timer" class="sr-only"></span>
            <div class="ad-track grid gap-4 {{ $visible > 1 ? 'sm:grid-cols-2' : '' }}">
                @foreach ($ads as $ad)
                    <a href="{{ $ad->link_url }}" target="_blank" rel="noopener sponsored"
                       class="ad-slide block card p-4 hover:border-accent-600/60 transition-colors group">
                        <span class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-mono uppercase tracking-widest muted">Advertisement</span>
                            <span data-ad-timer class="text-[10px] font-mono muted tabular-nums" aria-hidden="true">5s</span>
                        </span>
                        @if ($ad->image())
                            <img src="{{ $ad->image() }}" alt="{{ $ad->title }}"
                                 class="w-full aspect-video rounded-lg border border-zinc-200 dark:border-ink-700 mb-3 object-cover" loading="lazy">
                        @endif
                        <span class="block text-sm font-semibold heading group-hover:text-accent-700 dark:group-hover:text-accent-300 transition-colors">{{ $ad->title }}<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block w-3.5 h-3.5 ml-1 align-[-0.125em] muted group-hover:text-accent-700 dark:group-hover:text-accent-300 transition-colors" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg></span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <a href="{{ route('advertise') }}"
       class="mt-4 block rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-6 text-center hover:border-accent-600/60 transition-colors group dark:border-ink-600 dark:bg-ink-900/50">
        <span class="block text-[10px] font-mono uppercase tracking-widest muted mb-2">Advertisement</span>
        <span class="block text-lg font-semibold heading group-hover:text-accent-700 dark:group-hover:text-accent-300 transition-colors">Your ad here?</span>
        <span class="block mt-1 text-sm muted">Reach developers who care about SEO.</span>
        <span class="inline-block mt-3 rounded-lg bg-accent-600 px-4 py-1.5 text-sm font-medium text-white group-hover:bg-accent-500 transition-colors">Advertise with us</span>
    </a>
</div>
