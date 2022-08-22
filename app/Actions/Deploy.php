<?php

namespace App\Actions;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Deploy
{
    public function __invoke()
    {
        $output = '';

        if (!app()->isProduction()) {
            exit('Only for Production!');
        }

        if (!defined('STDIN')) {
            define('STDIN', fopen('php://stdin', 'rb'));
        }

        Artisan::call('down');
        $output .= Artisan::output();

        $output .= $this->deploy();

        Artisan::call('migrate --force');
        $output .= Artisan::output();

        Artisan::call('optimize:clear');
        $output .= Artisan::output();

        Artisan::call('optimize');
        $output .= Artisan::output();

        Artisan::call('up');
        $output .= Artisan::output();

        Log::info('Deployed: ' . $output);

        echo "<pre>$output</pre>";
    }

    private function deploy(): bool|string|null
    {
        shell_exec('git reset --hard');

        /* @noinspection ALL */
        $output = shell_exec('git pull origin main' . ' 2>&1');

        //$this->copyEnv();

        return $output;
    }

    // unfortunately have to do this because .env has been versioned
    /* @noinspection ALL */
    private function copyEnv(): void
    {
        $basePath = base_path();

        shell_exec("\cp -r $basePath.env-backup $basePath.env");
    }
}
