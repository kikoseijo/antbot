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


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', App\Http\Livewire\Dashboard::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', App\Http\Livewire\Users\ShowUsers::class)->name('users.index');
    Route::get('/user/add', App\Http\Livewire\Users\CreateUser::class)->name('users.add');
    Route::get('/user/{user}/edit', App\Http\Livewire\Users\EditUser::class)->name('users.edit');
    Route::get('/user/{user}/auth-logs', App\Http\Livewire\Users\AuthLogs::class)->name('users.auth-logs');
    Route::get('status', [UserController::class, 'userOnlineStatus']);

    Route::get('exchanges', App\Http\Livewire\Exchanges\ShowExchanges::class)->name('exchanges.index');
    Route::get('exchange/add', App\Http\Livewire\Exchanges\CreateExchange::class)->name('exchanges.add');
    Route::get('exchange/{exchange}/edit', App\Http\Livewire\Exchanges\EditExchange::class)->name('exchanges.edit');
    Route::get('exchange/{exchange}/positions', App\Http\Livewire\Exchanges\ShowPositions::class)->name('exchanges.positions');

    Route::get('grids', App\Http\Livewire\Configs\ShowConfigs::class)->name('configs.index');
    Route::get('grid/add', App\Http\Livewire\Configs\CreateConfig::class)->name('configs.add');
    Route::get('grid/{grid}/edit', App\Http\Livewire\Configs\EditConfig::class)->name('configs.edit');
    Route::get('grid/{grid}/visual-editor', App\Http\Livewire\Configs\GridEdit::class)->name('configs.visual-edit');

    Route::get('bots', App\Http\Livewire\Bots\ShowBots::class)->name('bots.index');
    Route::get('bot/add', App\Http\Livewire\Bots\CreateBot::class)->name('bots.add');
    Route::get('bot/{bot}/edit', App\Http\Livewire\Bots\EditBot::class)->name('bots.edit');
    Route::get('bot/{bot}/logs', App\Http\Livewire\Bots\BotLogsViewer::class)->name('bots.logs');

    Route::get('market', App\Http\Livewire\Market\MarketData::class)->name('market.index');
    Route::get('positions2', App\Http\Livewire\Market\Positions::class)->name('market.positions');


});


require __DIR__.'/auth.php';
