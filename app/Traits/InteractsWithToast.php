<?php

namespace App\Traits;

trait InteractsWithToast
{
    protected function info($message): void
    {
        $this->dispatchBrowserEvent('toast-message', [
            'style' => 'info',
            'message' => $message,
        ]);
    }

    protected function success($message): void
    {
        $this->dispatchBrowserEvent('toast-message', [
            'style' => 'success',
            'message' => $message,
        ]);
    }

    protected function warning($message): void
    {
        $this->dispatchBrowserEvent('toast-message', [
            'style' => 'warning',
            'message' => $message,
        ]);
    }

    protected function danger($message): void
    {
        $this->dispatchBrowserEvent('toast-message', [
            'style' => 'danger',
            'message' => $message,
        ]);
    }
}
