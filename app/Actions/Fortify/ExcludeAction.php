<?php

namespace App\Actions\Fortify;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ExcludeAction
{
    public function __invoke(): RedirectResponse
    {
        /* @noinspection ALL */
        $userId = session()->get('main_user_id');

        session()->forget('main_user_id');

        Auth::guard('web')->loginUsingId($userId);

        forgetUserData();

        return redirect()->to(RouteServiceProvider::HOME);
    }
}
