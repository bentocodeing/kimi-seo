@extends('layouts.admin')

@section('title', 'Edit ad — Kimi SEO Admin')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight heading">Edit ad</h1>

    <form method="POST" action="{{ route('admin.ads.update', $ad) }}" enctype="multipart/form-data" class="mt-6 max-w-2xl">
        @csrf @method('PUT')
        @include('admin.ads._form', ['submitLabel' => 'Update ad'])
    </form>
@endsection
