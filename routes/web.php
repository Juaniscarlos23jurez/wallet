<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassbookController;
use App\Http\Controllers\SimplePassController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/passbook/index', [PassbookController::class, 'index'])->name('passbook.index');
Route::get('/passbook/download', [PassbookController::class, 'download'])->name('passbook.download');
Route::get('/generate-pass', [SimplePassController::class, 'generate']);
Route::get('/passbook', [SimplePassController::class, 'showDownloadPage'])->name('passbook.download');
Route::get('/passbook/generate', [SimplePassController::class, 'generatePass'])->name('passbook.generate');
