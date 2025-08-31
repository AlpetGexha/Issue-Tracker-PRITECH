<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Concerns\WithNotifications;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
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

    #[Computed(cache: true, seconds: 300)] // Cache for 5 minutes
    public function stats()
    {
        $user = Auth::user();

        return [
            'total_projects' => Project::whereHas('users', fn ($query) => $query->where('user_id', $user->id))
                ->count(),
            'my_issues' => Issue::whereHas('users', fn ($query) => $query->where('user_id', $user->id))
                ->count(),
            'open_issues' => Issue::whereHas('users', fn ($query) => $query->where('user_id', $user->id))
                ->where('status', 'open')
                ->count(),
        ];
    }

    #[Computed(cache: true, seconds: 300)] // Cache for 5 minutes
    public function recentIssues()
    {
        $user = Auth::user();

        return Issue::with(['project', 'users'])
            ->whereHas('users', fn ($query) => $query->where('user_id', $user->id))
            ->orWhereHas('project.users', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->limit(5)
            ->get();
    }

    #[Computed(cache: true, seconds: 300)] // Cache for 5 minutes
    public function recentProjects()
    {
        $user = Auth::user();

        return Project::with('users')
            ->whereHas('users', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->limit(3)
            ->get();
    }

    #[Computed(cache: true, seconds: 180)] // Cache for 3 minutes (comments change more frequently)
    public function recentComments()
    {
        $user = Auth::user();

        return Comment::with(['issue.project', 'user'])
            ->where('user_id', $user->id)
            ->orWhereHas('issue.project.users', fn ($query) => $query->where('user_id', $user->id))
            ->orWhereHas('issue.users', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->limit(5)
            ->get();
    }
    public function render()
    {
        $recentIssues = $this->recentIssues;
        $recentProjects = $this->recentProjects;
        $recentComments = $this->recentComments;
        $stats = $this->stats;

        return view('livewire.dashboard', compact('recentIssues', 'recentProjects', 'recentComments', 'stats'));
    }
}
