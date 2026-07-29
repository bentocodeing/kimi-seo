@extends('layouts.app')

@section('title', 'Advertise — Kimi SEO')

@section('meta_description', 'Advertise on kimi-seo.com — one tasteful, tracking-free ad slot in front of developers and SEO practitioners who live in their terminal.')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-14">
        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight heading">Advertise on Kimi SEO</h1>
        <p class="mt-4 muted leading-relaxed">
            Kimi SEO is used by developers and SEO practitioners who live in their terminal.
            A single, tasteful ad slot is displayed on the landing page and alongside
            the documentation — no tracking scripts, no pop-ups, just your message in front of
            a technical audience.
        </p>

        <div class="mt-8 card p-6">
            <h2 class="font-semibold text-lg heading">How it works</h2>
            <ul class="mt-3 space-y-2 text-sm muted-strong list-disc list-inside">
                <li>One ad slot: landing page + docs sidebar, rotating through all active ads.</li>
                <li>Static image (uploaded or hosted) with a link of your choice.</li>
                <li>Clearly labeled as an advertisement. No third-party scripts, no cookies.</li>
                <li>Send us an inquiry below and we'll get back to you with pricing and availability.</li>
            </ul>
        </div>

        <h2 class="mt-12 font-semibold text-xl heading">Contact us</h2>

        @if ($errors->any())
            <div class="mt-4 flash-error">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('advertise.store') }}" class="mt-6 space-y-5">
            @csrf

            {{-- Honeypot: hidden from humans, bots fill it in. --}}
            <div class="hidden" aria-hidden="true">
                <label for="website">Website</label>
                <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
            </div>

            <div>
                <label for="name" class="label">Name <span class="accent-text">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="input">
            </div>

            <div>
                <label for="email" class="label">Email <span class="accent-text">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="input">
            </div>

            <div>
                <label for="company" class="label">Company <span class="muted">(optional)</span></label>
                <input type="text" name="company" id="company" value="{{ old('company') }}" class="input">
            </div>

            <div>
                <label for="message" class="label">Message <span class="accent-text">*</span></label>
                <textarea name="message" id="message" rows="5" required class="input"
                          placeholder="Tell us about your product and the campaign you have in mind.">{{ old('message') }}</textarea>
            </div>

            <button type="submit" class="btn-primary px-6 py-2.5">
                Send inquiry
            </button>
        </form>
    </div>
@endsection
