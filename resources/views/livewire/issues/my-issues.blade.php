<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Issues</h1>
            <p class="text-gray-600 dark:text-gray-400">Issues assigned to you</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search issues..."
                    class="w-full"
                />
            </div>

            <div>
                <flux:select wire:model.live="statusFilter" placeholder="All Status">
                    <option value="">All Status</option>
                    <option value="Open">Open</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Closed">Closed</option>
                </flux:select>
            </div>

            <div>
                <flux:select wire:model.live="priorityFilter" placeholder="All Priorities">
                    <option value="">All Priorities</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </flux:select>
            </div>
        </div>
    </div>

    {{-- Issues Grid --}}
    @if ($issues->count() > 0)
        <div class="grid gap-6">
            @foreach ($issues as $issue)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        <a href="{{ route('issues.detail', $issue) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $issue->title }}
                                        </a>
                                    </h3>
                                </div>

                                <p class="text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                    {{ Str::limit($issue->description, 120) }}
                                </p>

                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">
                                        Project: <span class="font-medium">{{ $issue->project->name }}</span>
                                    </span>

                                    @if ($issue->due_date)
                                        <span class="text-gray-500 dark:text-gray-400">
                                            Due: {{ $issue->due_date->format('M j, Y') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Tags --}}
                                @if ($issue->tags->count() > 0)
                                    <div class="flex items-center space-x-2 mt-3">
                                        @foreach ($issue->tags->take(3) as $tag)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white"
                                                  style="background-color: {{ $tag->color }}">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                        @if ($issue->tags->count() > 3)
                                            <span class="text-xs text-gray-500">+{{ $issue->tags->count() - 3 }} more</span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col items-end space-y-2">
                                <div class="flex space-x-2">
                                    <flux:badge :variant="$issue->status->value === 'Open' ? 'success' : ($issue->status->value === 'In Progress' ? 'warning' : 'default')">
                                        {{ $issue->status->value }}
                                    </flux:badge>

                                    <flux:badge :variant="$issue->priority->value === 'High' ? 'danger' : ($issue->priority->value === 'Medium' ? 'warning' : 'default')">
                                        {{ $issue->priority->value }}
                                    </flux:badge>
                                </div>

                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $issue->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $issues->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <flux:icon icon="document-text" class="size-12 text-gray-400 mx-auto mb-4" />
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No issues found</h3>
            <p class="text-gray-600 dark:text-gray-400">
                @if ($search || $statusFilter || $priorityFilter)
                    Try adjusting your filters to see more results.
                @else
                    You don't have any issues assigned to you yet.
                @endif
            </p>
        </div>
    @endif
</div>
