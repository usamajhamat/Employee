<?php
use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');

Route::get('/test-db', function () {
    try {
        \DB::connection()->getPdo();
        return "Connected to SQL Server successfully!";
    } catch (\Exception $e) {
        return "Connection failed: " . $e->getMessage();
    }
});