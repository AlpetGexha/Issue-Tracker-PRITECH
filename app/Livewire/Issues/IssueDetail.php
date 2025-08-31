<?php

declare(strict_types=1);

namespace App\Livewire\Issues;

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
    use AuthorizesRequests;

    public Issue $issue;

    // Edit Modal Properties
    public bool $showEditModal = false;
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

    public function openEditModal(): void
    {
        $this->editForm->setIssue($this->issue);
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editForm->reset();
    }

    public function updateIssue(): void
    {
        $this->editForm->update();

        $this->closeEditModal();

        // Flash success message
        session()->flash('success', 'Issue updated successfully!');

        // Refresh the issue data
        $this->issue->refresh();
    }

    public function shareIssue(): void
    {
        $url = route('issues.detail', $this->issue);

        $this->dispatch('copy-to-clipboard', text: $url);

        session()->flash('success', 'Issue URL copied to clipboard!');
    }

    public function deleteIssue()
    {
        $this->authorize('delete', $this->issue);

        $projectId = $this->issue->project_id;
        $issueTitle = $this->issue->title;

        $this->issue->delete();

        session()->flash('success', "Issue '{$issueTitle}' deleted successfully!");

        // Redirect to project detail page
        return redirect()->route('project.detail', $projectId);
    }

    public function render()
    {
        return view('livewire.issues.issue-detail');
    }
}
