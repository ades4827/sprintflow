<?php

app('router')->middleware(['web'])->get('/media/{method}/{media}/{conversion?}', [config('sprintflow.medias.file_access_controller'), 'media'])
    ->where('method', 'download|serve')
    ->name('media.getter');