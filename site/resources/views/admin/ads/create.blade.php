@extends('layouts.admin')

@section('title', 'New ad — Kimi SEO Admin')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight heading">New ad</h1>

    <form method="POST" action="{{ route('admin.ads.store') }}" enctype="multipart/form-data" class="mt-6 max-w-2xl">
        @csrf
        @include('admin.ads._form', ['submitLabel' => 'Create ad'])
    </form>
@endsection
