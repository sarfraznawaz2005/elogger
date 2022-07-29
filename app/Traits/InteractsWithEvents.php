<?php

namespace App\Traits;

trait InteractsWithEvents
{
    public function onEvent(): void
    {
        // nothing to do, component will automatically reload after catching event.
    }
}
