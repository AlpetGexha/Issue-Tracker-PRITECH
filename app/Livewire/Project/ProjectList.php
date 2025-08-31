<?php

declare(strict_types=1);

namespace App\Livewire\Project;

use App\Livewire\Forms\ProjectForm;
use App\Models\Project;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Project List')]
#[Lazy]
final class ProjectList extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public string $search = '';

    // Modal states
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?Project $selectedProject = null;

    public ProjectForm $createForm;
    public ProjectForm $editForm;

    #[On('refresh-projects')]
    public function refresh(): void
    {
        // Force re-render
    }

    public function placeholder()
    {
        return view('skeletons.project-list');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->createForm->reset();
        $this->showCreateModal = true;

        if (auth()->id()) {
            $this->createForm->selectedOwners = [auth()->id()];
        }
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->createForm->reset();
    }

    public function openEditModal(int $projectId): void
    {
        $project = Project::findOrFail($projectId);
        $this->selectedProject = $project;
        $this->editForm->setProject($project);
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->selectedProject = null;
        $this->editForm->reset();
    }

    public function createProject(): void
    {
        try {
            $project = $this->createForm->store();

            $this->closeCreateModal();
            $this->dispatch('project-created', name: $project->name);
            $this->dispatch('notify', message: 'Project created successfully!', type: 'success');
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

        try {
            $project = $this->editForm->update();

            $this->closeEditModal();
            $this->dispatch('project-updated', name: $project->name);
            $this->dispatch('notify', message: 'Project updated successfully!', type: 'success');
        } catch (Exception $e) {
            $this->dispatch('notify', message: 'Failed to update project. ' . $e->getMessage(), type: 'error');
        }
    }

    public function deleteProject(Project $project): void
    {
        $this->authorize('delete', $project);

        $project->delete();

        $this->dispatch('project-deleted', name: $project->name);
    }

    public function addCreateOwner(int $userId): void
    {
        $this->createForm->addOwner($userId);
    }

    public function removeCreateOwner(int $userId): void
    {
        $this->createForm->removeOwner($userId);
    }

    public function addEditOwner(int $userId): void
    {
        $this->editForm->addOwner($userId);
    }

    public function removeEditOwner(int $userId): void
    {
        $this->editForm->removeOwner($userId);
    }

    public function render()
    {
        $projects = Project::query()
            ->search($this->search)
            ->with('owners')
            ->withCount('issues')
            ->latest()
            ->paginate(12);

        // Search owners only if modals are open
        $createUsers = $this->showCreateModal
            ? $this->createForm->searchAvailableOwners()
            : collect();

        $editUsers = $this->showEditModal
            ? $this->editForm->searchAvailableOwners()
            : collect();

        return view('livewire.project.project-list', compact('projects', 'createUsers', 'editUsers'));
    }
}
