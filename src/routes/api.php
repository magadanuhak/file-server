<?php

use LaravelMerax\FileServer\App\Http\Controllers\File\Download;
use LaravelMerax\FileServer\App\Http\Controllers\File\Index;
use LaravelMerax\FileServer\App\Http\Controllers\File\Link;
use LaravelMerax\FileServer\App\Http\Controllers\File\Share;
use LaravelMerax\FileServer\App\Http\Controllers\File\Show;
use LaravelMerax\FileServer\App\Http\Controllers\Upload\Destroy;
use LaravelMerax\FileServer\App\Http\Controllers\Upload\Store;
use LaravelMerax\FileServer\App\Http\Controllers\File\Destroy AS DestroyFile;

Route::prefix('api/v1/uploads')
    ->as('v1.uploads.')
    ->middleware(['api', 'auth:api'])
    ->group(static function () {
        Route::post('store', Store::class)->name('store');
        Route::delete('{upload}', Destroy::class)->name('destroy');
    });

Route::prefix('api/v1/files')
    ->as('v1.files.')
    ->middleware(['api', 'auth:api'])
    ->group(static function () {
        Route::get('', Index::class)->name('index');
        Route::get('link/{file}', Link::class)->name('link');
        Route::get('download/{file}', Download::class)->name('download');
        Route::delete('{file}', DestroyFile::class)->name('destroy');
        Route::get('show/{file}', Show::class)->name('show');
    });

Route::get('v1/files/share/{file}', Share::class)->name('v1.files.share');
