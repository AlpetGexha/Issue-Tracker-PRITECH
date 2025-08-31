<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

trait WithNotifications
{
    public function notify(string $message, string $type = 'success'): void
    {
        $this->dispatch('notify', message: $message, type: $type);
    }

    public function notifySuccess(string $message): void
    {
        $this->notify($message, 'success');
    }

    public function notifyError(string $message): void
    {
        $this->notify($message, 'error');
    }

    public function notifyWarning(string $message): void
    {
        $this->notify($message, 'warning');
    }

    public function notifyInfo(string $message): void
    {
        $this->notify($message, 'info');
    }
}
