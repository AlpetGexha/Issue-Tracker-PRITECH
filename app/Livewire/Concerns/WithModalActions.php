<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

trait WithModalActions
{
    use WithConfirmation, WithModal, WithNotifications;

    public function openCreateModal(): void
    {
        $this->resetCreateForm();
        $this->openModal($this->getModalNames()['create']);
    }

    public function closeCreateModal(): void
    {
        $this->closeModal($this->getModalNames()['create']);
        $this->resetCreateForm();
    }

    public function openEditModal($item = null): void
    {
        if ($item && method_exists($this, 'setEditForm')) {
            $this->setEditForm($item);
        }
        $this->openModal($this->getModalNames()['edit']);
    }

    public function closeEditModal(): void
    {
        $this->closeModal($this->getModalNames()['edit']);
        if (method_exists($this, 'resetEditForm')) {
            $this->resetEditForm();
        }
    }

    public function openTagsModal($item = null): void
    {
        if ($item && method_exists($this, 'setSelectedItem')) {
            $this->setSelectedItem($item);
        }
        $this->openModal($this->getModalNames()['tags']);
    }

    public function closeTagsModal(): void
    {
        $this->closeModal($this->getModalNames()['tags']);
        if (method_exists($this, 'resetSelectedItem')) {
            $this->resetSelectedItem();
        }
    }

    public function openAssignModal($item = null): void
    {
        if ($item && method_exists($this, 'setSelectedItem')) {
            $this->setSelectedItem($item);
        }
        $this->openModal($this->getModalNames()['assign']);
    }

    public function closeAssignModal(): void
    {
        $this->closeModal($this->getModalNames()['assign']);
        if (method_exists($this, 'resetSelectedItem')) {
            $this->resetSelectedItem();
        }
    }

    // Common modal names - using methods instead of constants
    protected function getModalNames(): array
    {
        return [
            'create' => 'create',
            'edit' => 'edit',
            'delete' => 'delete',
            'assign' => 'assign',
            'tags' => 'tags',
        ];
    }

    // Helper methods that components can override
    protected function resetCreateForm(): void
    {
        if (property_exists($this, 'createForm') && method_exists($this->createForm, 'reset')) {
            $this->createForm->reset();
        }
    }

    protected function resetEditForm(): void
    {
        if (property_exists($this, 'editForm') && method_exists($this->editForm, 'reset')) {
            $this->editForm->reset();
        }
    }
}
