<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ProjectController extends Controller
{
    public function __invoke($id): Factory|View|Application
    {
        session(['project_id' => $id]);

        return view('projects');
    }
}
