<?php

namespace App\Actions;

use Illuminate\Support\Facades\Artisan;

class Optimize
{
    public function __invoke()
    {
        // run php artisan optimize
        Artisan::call('optimize:clear');

        $output = Artisan::output();

        echo "<pre>$output</pre>";
    }
}
