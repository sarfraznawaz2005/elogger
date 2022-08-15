<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class IncludeAction
{
    public function __invoke(User $user): RedirectResponse
    {
        session()->put('main_user_id', user()->id);

        Auth::guard('web')->loginUsingId($user->id);

        forgetUserData();

        return redirect()->to(RouteServiceProvider::HOME);
    }
}
