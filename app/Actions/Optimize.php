<?php

namespace App\Actions;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Optimize
{
    public function __invoke()
    {
        // run php artisan optimize
        Artisan::call('optimize:clear');

        $output = Artisan::output();

        if (app()->isProduction()) {
            Artisan::call('optimize');

            $output .= Artisan::output();
        }

        Log::info('Optimize: ' . $output);

        echo "<pre>$output</pre>";
    }
}
