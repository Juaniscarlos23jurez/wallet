<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassbookController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/passbook/index', [PassbookController::class, 'index'])->name('passbook.index');
Route::get('/passbook/download', [PassbookController::class, 'download'])->name('passbook.download');
