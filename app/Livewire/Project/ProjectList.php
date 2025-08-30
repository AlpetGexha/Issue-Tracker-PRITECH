<?php

namespace App\Livewire\Project;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectList extends Component
{
    use WithPagination;

    public string $search = '';

    #[On('refresh-projects')]
    public function refresh(): void
    {
        // This will force Livewire to run render() again
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
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
            ->withCount('issues')
            ->latest()
            ->paginate(4);

        return view('livewire.project.project-list', compact('projects'));
    }
}
