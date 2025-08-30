<?php

namespace App\Livewire\Project;

use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectDetail extends Component
{
    use WithPagination;

    public Project $project;
    public string $search = '';
    public string $statusFilter = '';
    public string $priorityFilter = '';
    public string $tagFilter = '';
    
    // Modal state
    public bool $showTagModal = false;
    public ?Issue $selectedIssue = null;
    public array $selectedTags = [];

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPriorityFilter(): void
    {
        $this->resetPage();
    }

    public function updatedTagFilter(): void
    {
        $this->resetPage();
    }

    public function openTagModal(Issue $issue): void
    {
        $this->selectedIssue = $issue;
        $this->selectedTags = $issue->tags->pluck('id')->toArray();
        $this->showTagModal = true;
    }

    public function closeTagModal(): void
    {
        $this->showTagModal = false;
        $this->selectedIssue = null;
        $this->selectedTags = [];
    }

    public function updateTags(): void
    {
        if ($this->selectedIssue) {
            $this->selectedIssue->tags()->sync($this->selectedTags);
            $this->closeTagModal();
            $this->dispatch('tags-updated', issueId: $this->selectedIssue->id);
        }
    }

    #[On('tags-updated')]
    public function refresh(): void
    {
        // Force refresh of the component
    }

    public function render()
    {
        $issues = $this->project->issues()
            ->with(['tags', 'users'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function ($query) {
                $query->where('priority', $this->priorityFilter);
            })
            ->when($this->tagFilter, function ($query) {
                $query->whereHas('tags', function ($q) {
                    $q->where('tags.id', $this->tagFilter);
                });
            })
            ->latest()
            ->paginate(10);

        $tags = Tag::all();
        $statuses = ProjectStatus::cases();
        $priorities = ProjectPriority::cases();

        return view('livewire.project.project-detail', compact('issues', 'tags', 'statuses', 'priorities'));
    }
}
