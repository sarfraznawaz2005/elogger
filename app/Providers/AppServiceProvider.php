<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::preventLazyLoading(!app()->isProduction());

        DB::disableQueryLog();

        //$this->dumpQueries();

        // load helpers
        foreach (glob(__DIR__ . '/../Helpers/*.php') as $filename) {
            require_once($filename);
        }
    }

    /** @noinspection ALL */
    private function dumpQueries(): void
    {
        DB::enableQueryLog();

        DB::listen(static function ($query) {
            $bindings = collect($query->bindings)->map(function ($param) {
                if (is_numeric($param)) {
                    return $param;
                }

                return "'$param'";
            });

            dump(Str::replaceArray('?', $bindings->toArray(), $query->sql));
        });
    }
}
