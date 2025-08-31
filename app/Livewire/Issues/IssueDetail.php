<?php

declare(strict_types=1);

namespace App\Livewire\Issues;

use App\Livewire\Concerns\WithModalActions;
use App\Livewire\Forms\IssueForm;
use App\Models\Issue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Lazy]
final class IssueDetail extends Component
{
    use AuthorizesRequests, WithModalActions;

    public Issue $issue;
    public IssueForm $editForm;

    public function mount(Issue $issue): void
    {
        $this->issue = $issue->load(['project', 'tags', 'users', 'comments.user']);
    }

    public function placeholder()
    {
        return view('skeletons.issue-detail');
    }

    public function title(): string
    {
        return $this->issue->title;
    }

    public function setEditForm($item): void
    {
        $this->editForm->setIssue($item);
    }

    public function updateIssue(): void
    {
        $this->editForm->update();
        $this->closeEditModal();
        $this->notifySuccess('Issue updated successfully!');
        $this->issue->refresh();
    }

    public function shareIssue(): void
    {
        $url = route('issues.detail', $this->issue);
        $this->dispatch('copy-to-clipboard', text: $url);
        $this->notifySuccess('Issue URL copied to clipboard!');
    }

    public function deleteIssue()
    {
        $this->authorize('delete', $this->issue);

        $projectId = $this->issue->project_id;
        $issueTitle = $this->issue->title;

        $this->confirmDelete($issueTitle, 'performDelete', [$projectId]);
    }

    public function performDelete(int $projectId): void
    {
        $issueTitle = $this->issue->title;
        $this->issue->delete();
        $this->notifySuccess("Issue '{$issueTitle}' deleted successfully!");
        $this->redirect(route('project.detail', $projectId), navigate: true);
    }

    public function render()
    {
        return view('livewire.issues.issue-detail');
    }
}
