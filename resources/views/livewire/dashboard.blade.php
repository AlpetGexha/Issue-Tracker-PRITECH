<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400">Welcome back! Here's what's happening with your projects.</p>
    </div>

    {{-- Quick Stats --}}
    <div class="grid gap-6 md:grid-cols-3">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/20">
                    <flux:icon icon="briefcase" class="size-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['total_projects'] }} Projects</h3>
                    <p class="text-gray-600 dark:text-gray-400">Manage your projects</p>
                </div>
            </div>
            <div class="mt-4">
                <flux:button href="{{ route('project.index') }}" variant="subtle" size="sm" wire:navigate>
                    View All Projects
                </flux:button>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/20">
                    <flux:icon icon="document-text" class="size-6 text-green-600 dark:text-green-400" />
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['my_issues'] }} My Issues</h3>
                    <p class="text-gray-600 dark:text-gray-400">Issues assigned to you</p>
                </div>
            </div>
            <div class="mt-4">
                <flux:button href="{{ route('issues.my') }}" variant="subtle" size="sm" wire:navigate>
                    View My Issues
                </flux:button>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 dark:bg-orange-900/20">
                    <flux:icon icon="exclamation-triangle" class="size-6 text-orange-600 dark:text-orange-400" />
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['open_issues'] }} Open Issues</h3>
                    <p class="text-gray-600 dark:text-gray-400">Issues that need attention</p>
                </div>
            </div>
            <div class="mt-4">
                <flux:button href="{{ route('tags.index') }}" variant="subtle" size="sm" wire:navigate>
                    Manage Tags
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h2>
        </div>
        <div class="p-6">
            @if ($recentIssues->count() > 0 || $recentProjects->count() > 0 || $recentComments->count() > 0)
                <div class="space-y-6">
                    {{-- Recent Issues --}}
                    @if ($recentIssues->count() > 0)
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Recent Issues</h3>
                            <div class="space-y-3">
                                @foreach ($recentIssues as $issue)
                                    <div class="flex items-start space-x-3">
                                        <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-900/20">
                                            <flux:icon icon="document-text" class="size-4 text-blue-600 dark:text-blue-400" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                <a wire:navigate href="{{ route('issues.detail', $issue) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $issue->title }}
                                                </a>
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                in {{ $issue->project->name }} • {{ $issue->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if ($issue->status->value === 'open') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif ($issue->status->value === 'in_progress') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $issue->status->value)) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Recent Projects --}}
                    @if ($recentProjects->count() > 0)
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Recent Projects</h3>
                            <div class="space-y-3">
                                @foreach ($recentProjects as $project)
                                    <div class="flex items-start space-x-3">
                                        <div class="p-2 rounded-full bg-purple-100 dark:bg-purple-900/20">
                                            <flux:icon icon="briefcase" class="size-4 text-purple-600 dark:text-purple-400" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                <a wire:navigate href="{{ route('project.detail', $project) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $project->name }}
                                                </a>
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $project->description ? Str::limit($project->description, 50) : 'No description' }} • {{ $project->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Recent Comments --}}
                    @if ($recentComments->count() > 0)
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Recent Comments</h3>
                            <div class="space-y-3">
                                @foreach ($recentComments as $comment)
                                    <div class="flex items-start space-x-3">
                                        <div class="p-2 rounded-full bg-green-100 dark:bg-green-900/20">
                                            <flux:icon icon="chat-bubble-left" class="size-4 text-green-600 dark:text-green-400" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900 dark:text-white">
                                                <span class="font-medium">{{ $comment->user->name }}</span> commented on
                                                <a wire:navigate href="{{ route('issues.detail', $comment->issue) }}" class="font-medium hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $comment->issue->title }}
                                                </a>
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ Str::limit($comment->body, 80) }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-8">
                    <flux:icon icon="clock" class="size-12 text-gray-400 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No recent activity</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Recent project and issue activities will appear here.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
