@extends('layouts.admin')

@section('title', 'Ads — Kimi SEO Admin')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-2xl font-bold tracking-tight heading">Ads</h1>
        <div class="flex items-center gap-4">
            <span data-reorder-status class="text-sm muted" aria-live="polite"></span>
            <a href="{{ route('admin.ads.create') }}" class="btn-primary px-4 py-2">New ad</a>
        </div>
    </div>

    <p class="mt-2 text-sm muted">Drag rows by their handle to reorder. The public slot rotates through all active ads in this order.</p>

    <div class="mt-6 overflow-x-auto rounded-xl border border-zinc-200 dark:border-ink-700">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 text-left muted dark:bg-ink-900">
                <tr>
                    <th class="pl-3 pr-1 py-3 font-medium w-8"><span class="sr-only">Reorder</span></th>
                    <th class="px-4 py-3 font-medium">Ad</th>
                    <th class="px-4 py-3 font-medium">Link</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-ink-800"
                   data-reorder-list data-reorder-url="{{ route('admin.ads.reorder') }}">
                @forelse ($ads as $ad)
                    <tr class="bg-white dark:bg-ink-950" data-ad-id="{{ $ad->id }}">
                        <td class="pl-3 pr-1 py-3">
                            <span class="drag-handle inline-flex items-center justify-center w-6 h-6 rounded text-zinc-400 hover:text-zinc-600 dark:text-ink-400 dark:hover:text-ink-100"
                                  draggable="true" title="Drag to reorder" aria-label="Drag to reorder">
                                <svg class="w-4 h-4 pointer-events-none" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="9" cy="6" r="1.5"/><circle cx="15" cy="6" r="1.5"/>
                                    <circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/>
                                    <circle cx="9" cy="18" r="1.5"/><circle cx="15" cy="18" r="1.5"/>
                                </svg>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($ad->image())
                                    <img src="{{ $ad->image() }}" alt="" class="w-14 h-10 rounded object-cover border border-zinc-200 dark:border-ink-700">
                                @endif
                                <span class="font-medium heading">{{ $ad->title }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 max-w-48 truncate">
                            <a href="{{ $ad->link_url }}" target="_blank" rel="noopener" class="accent-text-hover">{{ $ad->link_url }}</a>
                        </td>
                        <td class="px-4 py-3">
                            @if ($ad->is_active)
                                <span class="rounded-full bg-accent-600/15 text-accent-700 dark:text-accent-300 px-2.5 py-0.5 text-xs font-medium">Active</span>
                            @else
                                <span class="rounded-full bg-zinc-200 text-zinc-600 dark:bg-ink-700 dark:text-ink-300 px-2.5 py-0.5 text-xs font-medium">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.ads.edit', $ad) }}" class="btn-sm-outline">Edit</a>
                                <form method="POST" action="{{ route('admin.ads.toggle', $ad) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-sm-outline">
                                        {{ $ad->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.ads.destroy', $ad) }}" onsubmit="return confirm('Delete this ad?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white dark:bg-ink-950">
                        <td colspan="5" class="px-4 py-8 text-center muted">No ads yet. The site shows a "Your ad here?" placeholder.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
