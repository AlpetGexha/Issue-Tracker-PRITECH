<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

trait WithModal
{
    public array $modals = [];

    public function openModal(string $modal): void
    {
        $this->modals[$modal] = true;
    }

    public function closeModal(string $modal): void
    {
        $this->modals[$modal] = false;
    }

    public function toggleModal(string $modal): void
    {
        $this->modals[$modal] = ! ($this->modals[$modal] ?? false);
    }

    public function isModalOpen(string $modal): bool
    {
        return $this->modals[$modal] ?? false;
    }

    public function closeAllModals(): void
    {
        $this->modals = array_fill_keys(array_keys($this->modals), false);
    }
}
