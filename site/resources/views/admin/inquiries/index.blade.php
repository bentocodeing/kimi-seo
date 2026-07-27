@extends('layouts.admin')

@section('title', 'Inquiries — Kimi SEO Admin')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight heading">Inquiries</h1>

    <div class="mt-6 space-y-4">
        @forelse ($inquiries as $inquiry)
            <div class="rounded-xl border {{ $inquiry->is_read ? 'border-zinc-200 dark:border-ink-700' : 'border-accent-600/50' }} card p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold heading">
                            {{ $inquiry->name }}
                            @unless ($inquiry->is_read)
                                <span class="ml-2 rounded-full bg-accent-600/15 text-accent-700 dark:text-accent-300 px-2 py-0.5 text-xs font-medium align-middle">Unread</span>
                            @endunless
                        </p>
                        <p class="mt-0.5 text-sm muted">
                            <a href="mailto:{{ $inquiry->email }}" class="accent-text-hover">{{ $inquiry->email }}</a>
                            @if ($inquiry->company)
                                &middot; {{ $inquiry->company }}
                            @endif
                            &middot; {{ $inquiry->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('admin.inquiries.toggle-read', $inquiry) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-sm-outline">
                                {{ $inquiry->is_read ? 'Mark unread' : 'Mark read' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}" onsubmit="return confirm('Delete this inquiry?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm-danger">Delete</button>
                        </form>
                    </div>
                </div>
                <p class="mt-3 text-sm muted-strong whitespace-pre-line">{{ $inquiry->message }}</p>
            </div>
        @empty
            <div class="card p-8 text-center muted text-sm">
                No inquiries yet.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $inquiries->links() }}
    </div>
@endsection
