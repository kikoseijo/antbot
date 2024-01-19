<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Cache;

class UserController extends Controller
{
    /**
     * Show user online status.
     * Middleware desactivado: ActivityByUser
     */
    public function userOnlineStatus()
    {
        $users = User::all();
        foreach ($users as $user) {
            if (Cache::has('user-is-online-' . $user->id))
                echo $user->name . " is online. Last seen: " . Carbon::parse($user->last_seen)->diffForHumans() . " <br>";
            else
                echo $user->name . " is offline. Last seen: " . Carbon::parse($user->last_seen)->diffForHumans() . " <br>";
        }
    }

    public function swapExchange(Request $request, $id)
    {
        $exch_ids = $request->user()->exchanges->pluck('id');
        if (in_array($id, $exch_ids->all())) {
            $request->user()->update(['exchange_id' => $id]);
        }

        return redirect()->back();
    }
}
