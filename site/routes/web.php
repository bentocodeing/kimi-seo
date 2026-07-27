<?php

use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\AdvertiseController;
use App\Http\Controllers\DocsController;
use App\Models\Ad;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', ['ad' => Ad::current()]);
})->name('home');

Route::get('/docs', [DocsController::class, 'index'])->name('docs.index');
Route::get('/docs/{slug}', [DocsController::class, 'show'])->name('docs.show');

Route::get('/advertise', [AdvertiseController::class, 'create'])->name('advertise');
Route::post('/advertise', [AdvertiseController::class, 'store'])->name('advertise.store');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('ads', AdController::class)->except(['show']);
    Route::patch('ads/{ad}/toggle', [AdController::class, 'toggle'])->name('ads.toggle');

    Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::patch('inquiries/{inquiry}/toggle-read', [InquiryController::class, 'toggleRead'])->name('inquiries.toggle-read');
    Route::delete('inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');
});
