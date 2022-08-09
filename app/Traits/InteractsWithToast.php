<?php

namespace App\Traits;

trait InteractsWithToast
{
    protected function success($message): void
    {
        $this->dispatchBrowserEvent('toast-message', [
            'style' => 'success',
            'message' => $message,
        ]);
    }

    protected function danger($message): void
    {
        $this->dispatchBrowserEvent('toast-message', [
            'style' => 'error',
            'message' => $message,
        ]);
    }
}
