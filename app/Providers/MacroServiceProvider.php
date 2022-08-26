<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class MacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // getSQL
        $this->setSQLMacro();
    }

    private function setSQLMacro(): void
    {
        Builder::macro('getSQL', function () {
            /* @var Builder $this */
            $bindings = array_map(
                static fn($parameter) => is_string($parameter) ? "'$parameter'" : $parameter,
                $this->getBindings()
            );

            return Str::replaceArray(
                '?',
                $bindings,
                $this->toSql()
            );
        });

        EloquentBuilder::macro('getSQL', function () {
            /** @noinspection ALL */
            dd($this->toBase()->getSQL());
        });
    }
}
