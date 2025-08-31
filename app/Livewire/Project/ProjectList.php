<?php

declare(strict_types=1);

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Project List')]
final class ProjectList extends Component
{
    use AuthorizesRequests, WithPagination;

    public string $search = '';

    // Modal states
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?Project $selectedProject = null;

    // Create project form
    public string $name = '';
    public string $description = '';
    public string $startDate = '';
    public string $deadline = '';
    public array $selectedOwners = [];
    public string $ownerSearch = '';

    // Edit project form
    public string $editName = '';
    public string $editDescription = '';
    public string $editStartDate = '';
    public string $editDeadline = '';
    public array $editSelectedOwners = [];
    public string $editOwnerSearch = '';

    #[On('refresh-projects')]
    public function refresh(): void
    {
        // This will force Livewire to run render() again
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
        // Add current user as default owner
        if (Auth::id()) {
            $this->selectedOwners = [Auth::id()];
        }
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetCreateForm();
    }

    public function openEditModal(int $projectId): void
    {
        $project = Project::findOrFail($projectId);
        $this->selectedProject = $project;
        $this->editName = $project->name;
        $this->editDescription = $project->description ?? '';
        $this->editStartDate = $project->start_date?->format('Y-m-d') ?? '';
        $this->editDeadline = $project->deadline?->format('Y-m-d') ?? '';
        $this->editSelectedOwners = $project->owners->pluck('id')->toArray();
        $this->editOwnerSearch = '';
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->selectedProject = null;
        $this->resetEditForm();
    }

    public function createProject(): void
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string|max:1000',
            'startDate' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:startDate',
            'selectedOwners' => 'required|array|min:1',
            'selectedOwners.*' => 'exists:users,id',
        ]);

        try {
            DB::transaction(function () {
                $project = Project::create([
                    'name' => $this->name,
                    'description' => $this->description ?: null,
                    'start_date' => $this->startDate ?: null,
                    'deadline' => $this->deadline ?: null,
                ]);

                // Attach owners with role
                $ownerData = array_fill_keys($this->selectedOwners, ['role' => 'owner']);
                $project->users()->attach($ownerData);

                $this->closeCreateModal();
                $this->dispatch('project-created', name: $project->name);
                $this->dispatch('notify', message: 'Project created successfully!', type: 'success');
            });
        } catch (Exception $e) {
            $this->dispatch('notify', message: 'Failed to create project. Please try again.', type: 'error');
        }
    }

    public function updateProject(): void
    {
        if (! $this->selectedProject) {
            $this->closeEditModal();

            return;
        }

        $this->validate([
            'editName' => 'required|string|max:255|unique:projects,name,' . $this->selectedProject->id,
            'editDescription' => 'nullable|string|max:1000',
            'editStartDate' => 'nullable|date',
            'editDeadline' => 'nullable|date|after_or_equal:editStartDate',
            'editSelectedOwners' => 'required|array|min:1',
            'editSelectedOwners.*' => 'exists:users,id',
        ]);

        try {
            DB::transaction(function () {
                $this->selectedProject->update([
                    'name' => $this->editName,
                    'description' => $this->editDescription ?: null,
                    'start_date' => $this->editStartDate ?: null,
                    'deadline' => $this->editDeadline ?: null,
                ]);

                // Sync owners with role
                $ownerData = array_fill_keys($this->editSelectedOwners, ['role' => 'owner']);
                $this->selectedProject->users()->sync($ownerData);

                $this->closeEditModal();
                $this->dispatch('project-updated', name: $this->selectedProject->name);
                $this->dispatch('notify', message: 'Project updated successfully!', type: 'success');
            });
        } catch (Exception $e) {
            $this->dispatch('notify', message: 'Failed to update project. Please try again.' . $e->getMessage(), type: 'error');
        }
    }

    public function deleteProject(Project $project): void
    {
        $this->authorize('delete', $project);

        $project->delete();

        $this->dispatch('project-deleted', name: $project->name);
    }

    public function render()
    {
        $projects = Project::query()
            ->search($this->search)
            ->with('owners')
            ->withCount('issues')
            ->latest()
            ->paginate(4);

        // Get users for owner selection (only when modals are open for performance)
        $createUsers = $this->showCreateModal
            ? $this->getSearchableUsers($this->ownerSearch)
            : collect();

        $editUsers = $this->showEditModal
            ? $this->getSearchableUsers($this->editOwnerSearch)
            : collect();

        return view('livewire.project.project-list', compact('projects', 'createUsers', 'editUsers'));
    }

    public function removeOwner($userId): void
    {
        $this->selectedOwners = array_values(array_diff($this->selectedOwners, [$userId]));
    }

    public function removeEditOwner($userId): void
    {
        $this->editSelectedOwners = array_values(array_diff($this->editSelectedOwners, [$userId]));
    }

    public function addOwner($userId): void
    {
        if (! in_array($userId, $this->selectedOwners)) {
            $this->selectedOwners[] = $userId;
        }
    }

    public function addEditOwner($userId): void
    {
        if (! in_array($userId, $this->editSelectedOwners)) {
            $this->editSelectedOwners[] = $userId;
        }
    }

    private function resetCreateForm(): void
    {
        $this->name = '';
        $this->description = '';
        $this->startDate = '';
        $this->deadline = '';
        $this->selectedOwners = [];
        $this->ownerSearch = '';
    }

    private function resetEditForm(): void
    {
        $this->editName = '';
        $this->editDescription = '';
        $this->editStartDate = '';
        $this->editDeadline = '';
        $this->editSelectedOwners = [];
        $this->editOwnerSearch = '';
    }

    private function getSearchableUsers(string $search)
    {
        return User::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })->orderBy('name')->get();
    }
}
