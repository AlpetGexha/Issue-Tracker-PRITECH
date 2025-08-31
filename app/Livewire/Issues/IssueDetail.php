<?php

namespace App\Livewire\Issues;

use App\Models\Issue;
use App\Enums\ProjectStatus;
use App\Enums\ProjectPriority;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class IssueDetail extends Component
{
    public Issue $issue;

    // Edit Modal Properties
    public bool $showEditModal = false;
    public string $editTitle = '';
    public string $editDescription = '';
    public string $editStatus = '';
    public string $editPriority = '';
    public string $editDueDate = '';

    public function mount(Issue $issue): void
    {
        $this->issue = $issue->load(['project', 'tags', 'users', 'comments.user']);
    }

    public function title(): string
    {
        return $this->issue->title;
    }

    public function openEditModal(): void
    {
        $this->editTitle = $this->issue->title;
        $this->editDescription = $this->issue->description ?? '';
        $this->editStatus = $this->issue->status->value;
        $this->editPriority = $this->issue->priority->value;
        $this->editDueDate = $this->issue->due_date ? date('Y-m-d', strtotime($this->issue->due_date)) : '';
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->reset(['editTitle', 'editDescription', 'editStatus', 'editPriority', 'editDueDate']);
    }

    public function updateIssue(): void
    {
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editDescription' => 'nullable|string',
            'editStatus' => 'required|string',
            'editPriority' => 'required|string',
            'editDueDate' => 'nullable|date',
        ]);

        $this->issue->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'status' => ProjectStatus::from($this->editStatus),
            'priority' => ProjectPriority::from($this->editPriority),
            'due_date' => $this->editDueDate ?: null,
        ]);

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

    public function render()
    {
        return view('livewire.issues.issue-detail');
    }
}
