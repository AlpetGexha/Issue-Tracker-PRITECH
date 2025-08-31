<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Concerns\WithNotifications;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
#[Lazy]
final class Dashboard extends Component
{
    use WithNotifications;

    public function placeholder()
    {
        return view('skeletons.dashboard');
    }

    // Test notification methods
    public function testSuccessNotification(): void
    {
        $this->notifySuccess('This is a success notification!');
    }

    public function testErrorNotification(): void
    {
        $this->notifyError('This is an error notification!');
    }

    public function testWarningNotification(): void
    {
        $this->notifyWarning('This is a warning notification!');
    }

    public function testInfoNotification(): void
    {
        $this->notifyInfo('This is an info notification!');
    }

    public function render()
    {
        $user = Auth::user();

        $recentIssues = Issue::with(['project', 'users'])
            ->whereHas('users', fn ($query) => $query->where('user_id', $user->id))
            ->orWhereHas('project.users', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->limit(5)
            ->get();

        $recentProjects = Project::with('users')
            ->whereHas('users', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->limit(3)
            ->get();

        $recentComments = Comment::with(['issue.project', 'user'])
            ->where('user_id', $user->id)
            ->orWhereHas('issue.project.users', fn ($query) => $query->where('user_id', $user->id))
            ->orWhereHas('issue.users', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->limit(5)
            ->get();

        $stats = [
            'total_projects' => Project::whereHas('users', fn ($query) => $query->where('user_id', $user->id))
                ->count(),
            'my_issues' => Issue::whereHas('users', fn ($query) => $query->where('user_id', $user->id))
                ->count(),
            'open_issues' => Issue::whereHas('users', fn ($query) => $query->where('user_id', $user->id))
                ->where('status', 'open')
                ->count(),
        ];

        return view('livewire.dashboard', compact('recentIssues', 'recentProjects', 'recentComments', 'stats'));
    }
}
