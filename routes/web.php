<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/user', App\Http\Livewire\Users\ShowUsers::class);
    Route::get('/user/add', App\Http\Livewire\Users\CreateUser::class);
    Route::get('status', [UserController::class, 'userOnlineStatus']);

    Route::get('exchanges', App\Http\Livewire\Exchanges\ShowExchanges::class)->name('exchanges.index');
    Route::get('exchange/add', App\Http\Livewire\Exchanges\CreateExchange::class)->name('exchanges.add');

    Route::view('bots', 'bots.index')->name('bots.index');
    Route::view('configs', 'configs.index')->name('configs.index');
});


require __DIR__.'/auth.php';
