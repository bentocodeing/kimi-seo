@props(['ad' => null])

@if ($ad)
    <a href="{{ $ad->link_url }}" target="_blank" rel="noopener sponsored"
       {{ $attributes->merge(['class' => 'block card p-4 hover:border-accent-600/60 transition-colors group']) }}>
        <span class="block text-[10px] font-mono uppercase tracking-widest muted mb-2">Sponsored</span>
        @if ($ad->image())
            <img src="{{ $ad->image() }}" alt="{{ $ad->title }}"
                 class="w-full rounded-lg border border-zinc-200 dark:border-ink-700 mb-3 object-cover" loading="lazy">
        @endif
        <span class="block text-sm font-semibold heading group-hover:text-accent-700 dark:group-hover:text-accent-300 transition-colors">{{ $ad->title }}</span>
    </a>
@else
    <a href="{{ route('advertise') }}"
       {{ $attributes->merge(['class' => 'block rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-6 text-center hover:border-accent-600/60 transition-colors group dark:border-ink-600 dark:bg-ink-900/50']) }}>
        <span class="block text-[10px] font-mono uppercase tracking-widest muted mb-2">Sponsored</span>
        <span class="block text-lg font-semibold heading group-hover:text-accent-700 dark:group-hover:text-accent-300 transition-colors">Your ad here?</span>
        <span class="block mt-1 text-sm muted">Reach developers who care about SEO.</span>
        <span class="inline-block mt-3 rounded-lg bg-accent-600 px-4 py-1.5 text-sm font-medium text-white group-hover:bg-accent-500 transition-colors">Advertise with us</span>
    </a>
@endif
