<?php

declare(strict_types=1);

namespace App\Livewire\Project;

use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use App\Livewire\Concerns\WithModalActions;
use App\Livewire\Forms\IssueForm;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
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
    use AuthorizesRequests, WithModalActions, WithPagination;

    public Project $project;
    public string $search = '';
    public string $statusFilter = '';
    public string $priorityFilter = '';
    public string $tagFilter = '';

    // Selected items for modals
    public ?Issue $selectedIssue = null;
    public array $selectedTags = [];
    public array $selectedUsers = [];
    public string $userSearch = '';

    // Forms
    public IssueForm $createForm;
    public IssueForm $editForm;

    #[Computed(cache: true)]
    public function statuses()
    {
        return ProjectStatus::cases();
    }

    #[Computed(cache: true)]
    public function priorities()
    {
        return ProjectPriority::cases();
    }

    #[Computed(cache: true, seconds: 3600)]
    public function tags()
    {
        return Tag::all();
    }

    public function mount(Project $project): void
    {
        $this->project = $project;

        $this->createForm->setProject($project);
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

    public function openTagModal(int $issueId): void
    {
        $issue = Issue::find($issueId);

        if (! $issue) {
            return;
        }

        $this->selectedIssue = $issue;
        $this->selectedTags = $issue->tags->pluck('id')->toArray();

        $this->openModal('tags');
    }

    public function closeTagModal(): void
    {
        $this->closeModal('tags');
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

            unset($this->tags);

            $this->closeTagModal();
            $this->notifySuccess('Issue tags updated successfully!');
            $this->dispatch('tags-updated', issueId: $issueId);
        } catch (Exception $e) {
            $this->closeTagModal();
            $this->notifyError('Failed to update tags. Please try again.');
        }
    }

    public function openUserModal(int $issueId): void
    {
        $issue = Issue::find($issueId);

        if (! $issue || ! $issue->exists) {
            return;
        }

        $this->selectedIssue = $issue;
        $this->selectedUsers = $issue->users->pluck('id')->toArray();
        $this->userSearch = '';
        $this->openModal('assignees');
    }

    public function closeUserModal(): void
    {
        $this->closeModal('assignees');
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
        $this->resetCreateForm();
        $this->openModal('create');
    }

    public function closeCreateIssueModal(): void
    {
        $this->closeModal('create');
    }

    public function resetCreateForm(): void
    {
        $this->createForm->resetForm();
        $this->createForm->setProject($this->project);
    }

    public function createIssue(): void
    {
        try {
            // Ensure project is set before validation
            $this->createForm->setProject($this->project);

            $issue = $this->createForm->store();

            $this->closeCreateIssueModal();
            $this->notifySuccess('Issue created successfully!');
            $this->dispatch('issue-created', issueId: $issue->id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Don't close modal on validation errors - let Livewire handle displaying them
            throw $e;
        } catch (Exception $e) {
            $this->notifyError('Failed to create issue. Please try again.');
        }
    }

    public function openEditIssueModal(int $issueId): void
    {
        $issue = Issue::find($issueId);

        if (! $issue || ! $issue->exists) {
            return;
        }

        $this->selectedIssue = $issue;
        $this->editForm->setIssue($issue);
        $this->openModal('edit');
    }

    public function closeEditIssueModal(): void
    {
        $this->closeModal('edit');
        $this->selectedIssue = null;
        $this->editForm->reset();
    }

    public function resetEditIssueForm(): void
    {
        $this->editForm->reset();
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

        try {
            $issueId = $this->selectedIssue->id;
            $this->editForm->update();

            $this->closeEditIssueModal();
            $this->notifySuccess('Issue updated successfully!');
            $this->dispatch('issue-updated', issueId: $issueId);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Don't close modal on validation errors - let form show field errors
            throw $e;
        } catch (Exception $e) {
            $this->notifyError('Failed to update issue. Please try again.');
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
        $issues = $this->project->issues()
            ->with(['tags', 'users'])
            ->search($this->search)
            ->status($this->statusFilter)
            ->priority($this->priorityFilter)
            ->tag($this->tagFilter)
            ->latest()
            ->paginate(10);

        $tags = $this->tags;

        $users = $this->isModalOpen('assignees')
            ? User::query()
                ->where('name', 'like', "%{$this->userSearch}%")
                ->orWhere('email', 'like', "%{$this->userSearch}%")
                ->get()
            : collect();

        $statuses = $this->statuses;
        $priorities = $this->priorities;

        return view('livewire.project.project-detail', compact('issues', 'tags', 'users', 'statuses', 'priorities'));
    }
}
