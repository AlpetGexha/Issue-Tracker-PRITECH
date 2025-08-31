<?php

declare(strict_types=1);

namespace App\Livewire\Project;

use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Project Details')]
#[Lazy]
final class ProjectDetail extends Component
{
    use AuthorizesRequests, WithPagination;

    public Project $project;
    public string $search = '';
    public string $statusFilter = '';
    public string $priorityFilter = '';
    public string $tagFilter = '';

    // Modal state
    public bool $showTagModal = false;
    public bool $showUserModal = false;
    public bool $showCreateIssueModal = false;
    public bool $showEditIssueModal = false;
    public ?Issue $selectedIssue = null;
    public array $selectedTags = [];
    public array $selectedUsers = [];
    public string $userSearch = '';

    // Create issue form
    public string $newIssueTitle = '';
    public string $newIssueDescription = '';
    public string $newIssueStatus = 'open';
    public string $newIssuePriority = 'medium';
    public string $newIssueDueDate = '';

    // Edit issue form
    public string $editIssueTitle = '';
    public string $editIssueDescription = '';
    public string $editIssueStatus = '';
    public string $editIssuePriority = '';
    public string $editIssueDueDate = '';

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function placeholder()
    {
        return view('skeletons.project-detail');
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
        if (! $issue->exists) {
            return;
        }

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
        if (! $this->selectedIssue || ! $this->selectedIssue->exists) {
            $this->closeTagModal();

            return;
        }

        try {
            $issueId = $this->selectedIssue->id;
            $this->selectedIssue->tags()->sync($this->selectedTags);
            $this->closeTagModal();
            $this->dispatch('tags-updated', issueId: $issueId);
        } catch (Exception $e) {
            $this->closeTagModal();
        }
    }

    public function openUserModal(Issue $issue): void
    {
        if (! $issue->exists) {
            return;
        }

        $this->selectedIssue = $issue;
        $this->selectedUsers = $issue->users->pluck('id')->toArray();
        $this->userSearch = '';
        $this->showUserModal = true;
    }

    public function closeUserModal(): void
    {
        $this->showUserModal = false;
        $this->selectedIssue = null;
        $this->selectedUsers = [];
        $this->userSearch = '';
    }

    public function updateUsers(): void
    {
        if (! $this->selectedIssue || ! $this->selectedIssue->exists) {
            $this->closeUserModal();

            return;
        }

        try {
            $issueId = $this->selectedIssue->id;
            $this->selectedIssue->users()->sync($this->selectedUsers);
            $this->closeUserModal();
            $this->dispatch('users-updated', issueId: $issueId);
        } catch (Exception $e) {
            $this->closeUserModal();
            // Optionally log the error or show a user message
        }
    }

    public function openCreateIssueModal(): void
    {
        $this->resetCreateIssueForm();
        $this->showCreateIssueModal = true;
    }

    public function closeCreateIssueModal(): void
    {
        $this->showCreateIssueModal = false;
        $this->resetCreateIssueForm();
    }

    public function resetCreateIssueForm(): void
    {
        $this->newIssueTitle = '';
        $this->newIssueDescription = '';
        $this->newIssueStatus = 'open';
        $this->newIssuePriority = 'medium';
        $this->newIssueDueDate = '';
    }

    public function createIssue(): void
    {
        $this->validate([
            'newIssueTitle' => 'required|string|max:255',
            'newIssueDescription' => 'required|string',
            'newIssueStatus' => 'required|in:open,in_progress,closed',
            'newIssuePriority' => 'required|in:low,medium,high',
            'newIssueDueDate' => 'nullable|date|after:today',
        ]);

        $issue = $this->project->issues()->create([
            'title' => $this->newIssueTitle,
            'description' => $this->newIssueDescription,
            'status' => $this->newIssueStatus,
            'priority' => $this->newIssuePriority,
            'due_date' => $this->newIssueDueDate ?: null,
        ]);

        $this->closeCreateIssueModal();
        $this->dispatch('issue-created', issueId: $issue->id);
    }

    public function openEditIssueModal(Issue $issue): void
    {
        if (! $issue->exists) {
            return;
        }

        $this->selectedIssue = $issue;
        $this->editIssueTitle = $issue->title ?? '';
        $this->editIssueDescription = $issue->description ?? '';
        $this->editIssueStatus = $issue->status ? $issue->status->value : 'open';
        $this->editIssuePriority = $issue->priority ? $issue->priority->value : 'medium';
        $this->editIssueDueDate = $issue->due_date ? (string) $issue->due_date : '';
        $this->showEditIssueModal = true;
    }

    public function closeEditIssueModal(): void
    {
        $this->showEditIssueModal = false;
        $this->selectedIssue = null;
        $this->resetEditIssueForm();
    }

    public function resetEditIssueForm(): void
    {
        $this->editIssueTitle = '';
        $this->editIssueDescription = '';
        $this->editIssueStatus = '';
        $this->editIssuePriority = '';
        $this->editIssueDueDate = '';
    }

    public function deleteIssue(Issue $issue): void
    {
        $this->authorize('delete', $issue);

        $issue->delete();

        $this->dispatch('issue-deleted');
    }

    public function updateIssue(): void
    {
        if (! $this->selectedIssue || ! $this->selectedIssue->exists) {
            $this->closeEditIssueModal();

            return;
        }

        $this->validate([
            'editIssueTitle' => 'required|string|max:255',
            'editIssueDescription' => 'required|string',
            'editIssueStatus' => 'required|in:open,in_progress,closed',
            'editIssuePriority' => 'required|in:low,medium,high',
            'editIssueDueDate' => 'nullable|date',
        ]);

        try {
            $issueId = $this->selectedIssue->id;
            $this->selectedIssue->update([
                'title' => $this->editIssueTitle,
                'description' => $this->editIssueDescription,
                'status' => $this->editIssueStatus,
                'priority' => $this->editIssuePriority,
                'due_date' => $this->editIssueDueDate ?: null,
            ]);

            $this->closeEditIssueModal();
            $this->dispatch('issue-updated', issueId: $issueId);
        } catch (Exception $e) {
            $this->closeEditIssueModal();
            // Optionally log the error or show a user message
        }
    }

    #[On('tags-updated')]
    #[On('users-updated')]
    #[On('issue-created')]
    #[On('issue-updated')]
    public function refresh(): void
    {
        // Force refresh of the component
    }

    public function render()
    {
        // Simulate loading delay for demonstration (remove in production)
        sleep(1);

        $issues = $this->project->issues()
            ->with(['tags', 'users'])
            ->search($this->search)
            ->status($this->statusFilter)
            ->priority($this->priorityFilter)
            ->tag($this->tagFilter)
            ->latest()
            ->paginate(10);

        $tags = Tag::all();

        // Only search users when the user modal is open to improve performance
        $users = $this->showUserModal
            ? User::query()
                ->search($this->userSearch)
                ->get()
            : collect();

        $statuses = ProjectStatus::cases();
        $priorities = ProjectPriority::cases();

        return view('livewire.project.project-detail', compact('issues', 'tags', 'users', 'statuses', 'priorities'));
    }
}
