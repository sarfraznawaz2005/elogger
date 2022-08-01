<?php

namespace App\Traits;

trait InteractsWithFlash
{
    protected function info($message): void
    {
        session()->flash('toast', [
            'style' => 'info',
            'message' => $message,
        ]);
    }

    protected function success($message): void
    {
        session()->flash('toast', [
            'style' => 'success',
            'message' => $message,
        ]);
    }

    protected function warning($message): void
    {
        session()->flash('toast', [
            'style' => 'warning',
            'message' => $message,
        ]);
    }

    protected function danger($message): void
    {
        session()->flash('toast', [
            'style' => 'danger',
            'message' => $message,
        ]);
    }
}
