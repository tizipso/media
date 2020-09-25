<?php

use Dcat\Admin\Extension\Media\Http\Controllers;

Route::prefix('media')->group(function () {
    Route::get('', Controllers\MediaController::class.'@index')->name('media-index');
    Route::get('/download', Controllers\MediaController::class.'@download')->name('media-download');
    Route::delete('/delete', Controllers\MediaController::class.'@delete')->name('media-delete');
    Route::put('/move', Controllers\MediaController::class.'@move')->name('media-move');
    Route::post('/upload', Controllers\MediaController::class.'@upload')->name('media-upload');
    Route::post('/folder', Controllers\MediaController::class.'@newFolder')->name('media-new-folder');
});