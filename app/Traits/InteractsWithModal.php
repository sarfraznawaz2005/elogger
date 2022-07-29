<?php

namespace App\Traits;

trait InteractsWithModal
{
    public bool $isModalOpen = false;

    public function openModal(): void
    {
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
    }
}
