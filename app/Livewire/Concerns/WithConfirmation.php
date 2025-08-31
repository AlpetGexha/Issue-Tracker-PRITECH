<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

trait WithConfirmation
{
    public function confirmAction(string $message, string $method, array $parameters = []): void
    {
        $this->dispatch('confirm-action', [
            'message' => $message,
            'method' => $method,
            'parameters' => $parameters,
        ]);
    }

    public function confirmDelete(string $itemName, string $method, array $parameters = []): void
    {
        $this->confirmAction(
            "Are you sure you want to delete '{$itemName}'? This action cannot be undone.",
            $method,
            $parameters
        );
    }
}
