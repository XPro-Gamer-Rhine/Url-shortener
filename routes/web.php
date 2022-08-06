<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlShortenerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('urlShortener');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['throttle:ip_address'])->group(function () {
    Route::get('/{any}',[UrlShortenerController::class, 'handleRedirectUrl']);
});
// Route::get('/{any}',[UrlShortenerController::class, 'handleRedirectUrl'])->middleware('throttle:3,1');;
Route::post('/url/shorten', [UrlShortenerController::class, 'store']);