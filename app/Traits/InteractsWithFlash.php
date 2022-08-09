<?php

namespace App\Traits;

trait InteractsWithFlash
{
    protected function success($message): void
    {
        session()->flash('toast', [
            'style' => 'success',
            'message' => $message,
        ]);
    }

    protected function danger($message): void
    {
        session()->flash('toast', [
            'style' => 'error',
            'message' => $message,
        ]);
    }
}
