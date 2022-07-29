<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class EntryController extends Controller
{
    public function __invoke(): Factory|View|Application
    {
        if (!session('month_hours')) {
            refreshData();
        }

        return view('entry');
    }
}
