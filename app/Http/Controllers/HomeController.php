<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request): Factory|View|Application
    {
        if (!session('month_hours')) {
            refreshData();
        }

        return view('dashboard');
    }
}
