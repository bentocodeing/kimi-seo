@extends('layouts.admin')

@section('title', 'Dashboard — Kimi SEO Admin')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight heading">Dashboard</h1>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <a href="{{ route('admin.ads.index') }}" class="card p-6 hover:border-accent-600/60 transition-colors">
            <p class="text-3xl font-bold font-mono accent-text">{{ $activeAds }}<span class="muted text-lg">/{{ $totalAds }}</span></p>
            <p class="mt-1 text-sm muted">Active ads (total)</p>
        </a>
        <a href="{{ route('admin.inquiries.index') }}" class="card p-6 hover:border-accent-600/60 transition-colors">
            <p class="text-3xl font-bold font-mono accent-text">{{ $unreadInquiries }}<span class="muted text-lg">/{{ $totalInquiries }}</span></p>
            <p class="mt-1 text-sm muted">Unread inquiries (total)</p>
        </a>
    </div>

    <div class="mt-8 flex gap-3">
        <a href="{{ route('admin.ads.create') }}" class="btn-primary px-4 py-2">New ad</a>
        <a href="{{ route('admin.ads.index') }}" class="btn-outline px-4 py-2">Manage ads</a>
        <a href="{{ route('admin.inquiries.index') }}" class="btn-outline px-4 py-2">View inquiries</a>
    </div>
@endsection
