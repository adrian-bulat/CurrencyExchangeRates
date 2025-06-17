<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\TabulatorController;
use App\View\Components\Datepicker;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::get('/date', [Datepicker::class, 'render']);
Route::get('/tabular', [TabulatorController::class, 'showTable']);

Route::get('/data-table', [ExchangeRateController::class, 'dataTable']);
Route::get('/api/exchange-rates/filter', [ExchangeRateController::class, 'filter']);

