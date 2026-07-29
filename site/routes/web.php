<?php

use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\AdvertiseController;
use App\Http\Controllers\DocsController;
use App\Models\Ad;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', ['ads' => Ad::activeOrdered()]);
})->name('home');

Route::get('/docs', [DocsController::class, 'index'])->name('docs.index');
Route::get('/docs/{slug}', [DocsController::class, 'show'])->name('docs.show');

Route::get('/media/{path}', [\App\Http\Controllers\MediaController::class, 'show'])
    ->where('path', '.+')
    ->name('media');

Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => route('home'), 'lastmod' => null],
        ['loc' => route('docs.index'), 'lastmod' => null],
    ];

    foreach (config('docs.pages') as $slug => $page) {
        $urls[] = [
            'loc' => route('docs.show', $slug),
            'lastmod' => is_file($page['path']) ? date('Y-m-d', filemtime($page['path'])) : null,
        ];
    }

    $urls[] = ['loc' => route('advertise'), 'lastmod' => null];

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');

Route::get('/advertise', [AdvertiseController::class, 'create'])->name('advertise');
Route::post('/advertise', [AdvertiseController::class, 'store'])->name('advertise.store');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('ads/reorder', [AdController::class, 'reorder'])->name('ads.reorder');
    Route::resource('ads', AdController::class)->except(['show']);
    Route::patch('ads/{ad}/toggle', [AdController::class, 'toggle'])->name('ads.toggle');

    Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::patch('inquiries/{inquiry}/toggle-read', [InquiryController::class, 'toggleRead'])->name('inquiries.toggle-read');
    Route::delete('inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');
});
