@extends('layouts.app')

@section('title', 'Sign in — Kimi SEO')

@section('content')
    <div class="max-w-sm mx-auto px-4 sm:px-6 py-16 sm:py-24">
        <div class="text-center mb-8">
            <p class="font-mono text-sm accent-text mb-2">&gt;_ admin access</p>
            <h1 class="text-2xl font-bold tracking-tight heading">Sign in</h1>
        </div>

        @if ($errors->any())
            <div class="mb-6 flash-error">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="card p-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="input">
            </div>

            <div>
                <label for="password" class="label">Password</label>
                <input type="password" name="password" id="password" required autocomplete="current-password" class="input">
            </div>

            <label class="flex items-center gap-2 text-sm muted-strong">
                <input type="checkbox" name="remember" class="rounded border-zinc-300 text-accent-600 focus:ring-accent-500 dark:border-ink-600 dark:bg-ink-900">
                Remember me
            </label>

            <button type="submit" class="btn-primary w-full py-2.5">
                Sign in
            </button>
        </form>
    </div>
@endsection
