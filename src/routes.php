<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->group(function () {
        Route::get('media/{method}/{media}/as/{filename}/{conversion?}', [config('sprintflow.medias.file_access_controller'), 'media'])
            ->whereIn('method', ['download'])
            ->name('media.downloader');

        Route::get('media/{method}/{media}/{conversion?}', [config('sprintflow.medias.file_access_controller'), 'media'])
            ->whereIn('method', ['download', 'serve'])
            ->name('media.getter');
    });