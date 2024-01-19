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

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/commands', App\Http\Livewire\Admin\AdminCommands::class)->name('admin.commands');
    Route::get('/admin/settings', App\Http\Livewire\Admin\Settings::class)->name('admin.settings');
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
    Route::get('swap-exchange/{id}', [UserController::class, 'swapExchange']);

    Route::get('exchanges', App\Http\Livewire\Exchanges\ShowExchanges::class)->name('exchanges.index');
    Route::get('exchange/add', App\Http\Livewire\Exchanges\CreateExchange::class)->name('exchanges.add');
    Route::get('exchange/{exchange}/edit', App\Http\Livewire\Exchanges\EditExchange::class)->name('exchanges.edit');
    Route::get('exchange/trade-records/{symbol?}', App\Http\Livewire\Exchanges\TradeRecords::class)->name('exchanges.trade-records');

    Route::get('trades/stats/pnl', App\Http\Livewire\Exchanges\ShowTrades::class)->name('trades.pnl');
    Route::get('trades/{symbol?}', App\Http\Livewire\Exchanges\TradeRecords::class)->name('trades.index');
    Route::get('positions', App\Http\Livewire\Exchanges\ShowPositions::class)->name('positions.index');

    Route::get('grids', App\Http\Livewire\Configs\ShowConfigs::class)->name('configs.index');
    Route::get('grid/add', App\Http\Livewire\Configs\CreateConfig::class)->name('configs.add');
    Route::get('grid/{grid}/edit', App\Http\Livewire\Configs\EditConfig::class)->name('configs.edit');
    Route::get('grid/{grid}/visual-editor', App\Http\Livewire\Configs\GridEdit::class)->name('configs.visual-edit');

    Route::get('routines', App\Http\Livewire\Routines\ShowRoutines::class)->name('routines.index');
    Route::get('routine/add', App\Http\Livewire\Routines\CreateRoutine::class)->name('routines.add');
    Route::get('routine/{routine}/edit', App\Http\Livewire\Routines\EditRoutine::class)->name('routines.edit');

    Route::get('bots/{exchange?}', App\Http\Livewire\Bots\ShowBots::class)->name('bots.index');
    Route::get('bot/add', App\Http\Livewire\Bots\CreateBot::class)->name('bots.add');
    Route::get('bot/{bot}/edit', App\Http\Livewire\Bots\EditBot::class)->name('bots.edit');
    Route::get('bot/{bot}/logs/{name?}', App\Http\Livewire\Bots\BotLogsViewer::class)->name('bots.logs');

    Route::get('symbols', App\Http\Livewire\Symbols\ShowSymbols::class)->name('symbols.index');
    Route::get('symbols/add', App\Http\Livewire\Symbols\CreateSymbol::class)->name('symbols.add');

    Route::get('market', App\Http\Livewire\Market\MarketData::class)->name('market.index');
    Route::get('positions2', App\Http\Livewire\Market\Positions::class)->name('market.positions');
    Route::get('trading-view/{position}/{interval}', App\Http\Livewire\Market\TradingView::class)->name('market.trading-view');

    Route::get('what2trade', App\Http\Livewire\Symbols\WhatToTrade::class)->name('symbols.what-to-trade');

    Route::impersonate();
});


require __DIR__.'/auth.php';
