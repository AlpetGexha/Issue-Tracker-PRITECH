<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class ProjectForm extends Form
{
    public ?Project $project = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:1000')]
    public ?string $description = '';

    #[Validate('required|date')]
    public string $startDate = '';

    #[Validate('required|date|after:start_date')]
    public ?string $deadline = '';

    #[Validate('array|min:1')]
    public array $selectedOwners = [];

    public string $ownerSearch = '';

    public function setProject(Project $project): void
    {
        $this->project = $project;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->startDate = $project->start_date instanceof Carbon
            ? $project->start_date->format('Y-m-d')
            : $project->start_date;
        $this->deadline = $project->deadline instanceof Carbon
            ? $project->deadline->format('Y-m-d')
            : $project->deadline;
        $this->selectedOwners = $project->users->pluck('id')->toArray();
    }

    public function store(): Project
    {
        $this->validate();

        $project = Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->startDate,
            'deadline' => $this->deadline,
        ]);

        $owners = collect($this->selectedOwners)->mapWithKeys(fn ($id) => [
            $id => ['role' => 'owner'],
        ])->toArray();

        $project->users()->attach($owners);

        $this->reset();

        return $project;
    }

    public function update(): Project
    {
        $this->validate();

        $this->project->update([
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->startDate,
            'deadline' => $this->deadline,
        ]);

        // Sync owners with 'owner' role
        $ownersWithRole = [];
        foreach ($this->selectedOwners as $userId) {
            $ownersWithRole[$userId] = ['role' => 'owner'];
        }
        $this->project->users()->sync($ownersWithRole);

        return $this->project->fresh();
    }

    public function searchAvailableOwners()
    {
        if (empty($this->ownerSearch)) {
            return collect();
        }

        return User::where('name', 'like', '%' . $this->ownerSearch . '%')
            ->orWhere('email', 'like', '%' . $this->ownerSearch . '%')
            ->whereNotIn('id', $this->selectedOwners)
            ->limit(10)
            ->get();
    }

    public function addOwner(int $userId): void
    {
        if (! in_array($userId, $this->selectedOwners)) {
            $this->selectedOwners[] = $userId;
            $this->ownerSearch = ''; // Clear search after selection
        }
    }

    public function removeOwner(int $userId): void
    {
        $this->selectedOwners = array_filter($this->selectedOwners, fn ($id) => $id !== $userId);
        $this->selectedOwners = array_values($this->selectedOwners); // Re-index array
    }

    public function getSelectedOwnersProperty()
    {
        return User::whereIn('id', $this->selectedOwners)->get();
    }
}
