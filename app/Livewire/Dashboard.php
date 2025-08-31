<?php

declare(strict_types=1);

namespace App\Livewire;

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
    public function placeholder()
    {
        return view('skeletons.dashboard');
    }

    public function render()
    {
        // Simulate loading delay for demonstration (remove in production)
        sleep(1);

        $user = Auth::user();

        // Get recent activities
        $recentIssues = Issue::with(['project', 'users'])
            ->whereHas('users', fn ($query) => $query->where('user_id', $user->id))
            ->orWhereHas('project', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->limit(5)
            ->get();

        $recentProjects = Project::with('owners')
            ->where('user_id', $user->id)
            ->orWhereHas('owners', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->limit(3)
            ->get();

        $recentComments = Comment::with(['issue.project', 'user'])
            ->where('user_id', $user->id)
            ->orWhereHas('issue.project', fn ($query) => $query->where('user_id', $user->id))
            ->orWhereHas('issue.users', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->limit(5)
            ->get();

        // Get stats
        $stats = [
            'total_projects' => Project::where('user_id', $user->id)
                ->orWhereHas('owners', fn ($query) => $query->where('user_id', $user->id))
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
