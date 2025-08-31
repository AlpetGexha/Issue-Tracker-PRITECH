<?php

declare(strict_types=1);

namespace App\Livewire\Issues;

use App\Models\Issue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('My Issues')]
#[Lazy]
final class MyIssues extends Component
{
    use AuthorizesRequests, WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    #[Url]
    public string $priorityFilter = '';

    public function placeholder()
    {
        return view('skeletons.my-issues');
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

    public function deleteIssue(Issue $issue): void
    {
        $this->authorize('delete', $issue);

        $issueTitle = $issue->title;
        $issue->delete();

        session()->flash('success', "Issue '{$issueTitle}' deleted successfully!");
    }

    public function render()
    {
        $issues = Issue::query()
            ->with(['project', 'tags', 'users'])
            ->myIssue()
            ->search($this->search)
            ->status($this->statusFilter)
            ->priority($this->priorityFilter)
            ->latest()
            ->paginate(10);

        return view('livewire.issues.my-issues', compact('issues'));
    }
}
