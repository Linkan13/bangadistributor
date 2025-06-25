<?php
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'laravelpwa.'], function()
{
    Route::get('/manifest.json', 'LaravelPWAController@manifestJson')
    ->name('manifest');
    Route::get('/offline/', 'LaravelPWAController@offline');
});
