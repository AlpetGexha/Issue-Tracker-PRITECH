<div>
    {{-- Project Header --}}
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $project->name }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $project->description }}</p>
                <div class="mt-4 flex gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span>Start Date: {{ $project->start_date?->format('M d, Y') ?? 'Not set' }}</span>
                    <span>Deadline: {{ $project->deadline?->format('M d, Y') ?? 'Not set' }}</span>
                    <span>Total Issues: {{ $issues->total() }}</span>
                </div>
            </div>
            <div>
                <flux:button wire:click="openCreateIssueModal" variant="primary">
                    Create New Issue
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="mb-6 space-y-4">
        {{-- Search --}}
        <div>
            <input
                type="search"
                wire:model.live.debounce.500ms="search"
                placeholder="Search issues..."
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap gap-4">
            {{-- Status Filter --}}
            <select wire:model.live="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">All Statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">{{ ucfirst(str_replace('_', ' ', $status->value)) }}</option>
                @endforeach
            </select>

            {{-- Priority Filter --}}
            <select wire:model.live="priorityFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">All Priorities</option>
                @foreach ($priorities as $priority)
                    <option value="{{ $priority->value }}">{{ ucfirst($priority->value) }}</option>
                @endforeach
            </select>

            {{-- Tag Filter --}}
            <select wire:model.live="tagFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">All Tags</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>

            {{-- Clear Filters --}}
            @if ($search || $statusFilter || $priorityFilter || $tagFilter)
                <button
                    wire:click="$set('search', ''); $set('statusFilter', ''); $set('priorityFilter', ''); $set('tagFilter', '')"
                    class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200"
                >
                    Clear Filters
                </button>
            @endif
        </div>
    </div>

    {{-- Issues List --}}
    <div class="space-y-4">
        @forelse ($issues as $issue)
            <div wire:key="issue-{{ $issue->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <a wire:navigate href="{{ route('issues.detail', $issue) }}"
                               class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                {{ $issue->title }}
                            </a>
                        </h3>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $issue->description }}</p>

                        {{-- Issue Meta --}}
                        <div class="mt-3 flex flex-wrap gap-2">
                            {{-- Status Badge --}}
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($issue->status === \App\Enums\ProjectStatus::Open) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif ($issue->status === \App\Enums\ProjectStatus::InProgress) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $issue->status->value)) }}
                            </span>

                            {{-- Priority Badge --}}
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($issue->priority === \App\Enums\ProjectPriority::Low) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif ($issue->priority === \App\Enums\ProjectPriority::Medium) bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @endif">
                                {{ ucfirst($issue->priority->value) }} Priority
                            </span>

                            {{-- Due Date --}}
                            @if ($issue->due_date)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    Due: {{ $issue->due_date->format('M d, Y') }}
                                </span>
                            @endif
                        </div>

                        {{-- Tags --}}
                        @if ($issue->tags->count() > 0)
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach ($issue->tags as $tag)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Assigned Users --}}
                        @if ($issue->users->count() > 0)
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach ($issue->users as $user)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        👤 {{ $user->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="ml-4 flex flex-col gap-2">
                        @can('update', $issue)
                            <button
                                wire:click="openEditIssueModal({{ $issue->id }})"
                                class="px-3 py-1 text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 border border-indigo-300 rounded hover:bg-indigo-50 dark:border-indigo-600 dark:hover:bg-indigo-900"
                            >
                                Edit Issue
                            </button>
                        @endcan
                        <button
                            wire:click="openTagModal({{ $issue->id }})"
                            class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 border border-blue-300 rounded hover:bg-blue-50 dark:border-blue-600 dark:hover:bg-blue-900"
                        >
                            Manage Tags
                        </button>
                        <button
                            wire:click="openUserModal({{ $issue->id }})"
                            class="px-3 py-1 text-sm text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 border border-green-300 rounded hover:bg-green-50 dark:border-green-600 dark:hover:bg-green-900"
                        >
                            Assign Users
                        </button>
                        @can('delete', $issue)
                            <button
                                wire:click="deleteIssue({{ $issue->id }})"
                                wire:confirm="Are you sure you want to delete this issue? This action cannot be undone."
                                class="px-3 py-1 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 border border-red-300 rounded hover:bg-red-50 dark:border-red-600 dark:hover:bg-red-900"
                            >
                                Delete Issue
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="text-gray-500 dark:text-gray-400">
                    @if ($search || $statusFilter || $priorityFilter || $tagFilter)
                        No issues found matching your filters.
                    @else
                        This project doesn't have any issues yet.
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($issues->hasPages())
        <div class="mt-6">
            {{ $issues->links() }}
        </div>
    @endif

    {{-- Tag Management Modal --}}
    @if ($showTagModal && $selectedIssue && $selectedIssue->exists)
        <flux:modal name="tag-modal" wire:model.self="showTagModal">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Manage Tags for "{{ $selectedIssue->title }}"</flux:heading>
                </div>

                {{-- Tags List --}}
                <div class="space-y-3 max-h-60 overflow-y-auto">
                    @foreach ($tags as $tag)
                        <label class="flex items-center space-x-3 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                            <input
                                type="checkbox"
                                wire:model="selectedTags"
                                value="{{ $tag->id }}"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            />
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                            </div>
                            @if ($selectedIssue && in_array($tag->id, $selectedIssue->tags->pluck('id')->toArray()))
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium">Currently Tagged</span>
                            @endif
                        </label>
                    @endforeach
                </div>

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button wire:click="closeTagModal" variant="ghost">
                        Cancel
                    </flux:button>
                    <flux:button wire:click="updateTags" variant="primary">
                        Update Tags
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

    {{-- User Assignment Modal --}}
    @if ($showUserModal && $selectedIssue && $selectedIssue->exists)
        <flux:modal name="user-modal" wire:model.self="showUserModal">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Assign Users to "{{ $selectedIssue->title }}"</flux:heading>
                </div>

                {{-- User Search --}}
                <div>
                    <flux:input
                        wire:model.live.debounce.300ms="userSearch"
                        placeholder="Search users by name or email..."
                        label="Search Users"
                    />
                </div>

                {{-- Users List --}}
                <div class="space-y-3 max-h-60 overflow-y-auto">
                    @forelse ($users as $user)
                        <label class="flex items-center space-x-3 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                            <input
                                type="checkbox"
                                wire:model="selectedUsers"
                                value="{{ $user->id }}"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            />
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                            </div>
                            @if ($selectedIssue && in_array($user->id, $selectedIssue->users->pluck('id')->toArray()))
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium">Currently Assigned</span>
                            @endif
                        </label>
                    @empty
                        <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                            @if ($userSearch)
                                No users found matching "{{ $userSearch }}"
                            @else
                                No users available
                            @endif
                        </div>
                    @endforelse
                </div>

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button wire:click="closeUserModal" variant="ghost">
                        Cancel
                    </flux:button>
                    <flux:button wire:click="updateUsers" variant="primary">
                        Update Assignments
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

    {{-- Create Issue Modal --}}
    @if ($showCreateIssueModal)
        <flux:modal name="create-issue-modal" wire:model.self="showCreateIssueModal">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Create New Issue</flux:heading>
                </div>

                <div class="space-y-4">
                    <div>
                        <flux:input
                            wire:model="newIssueTitle"
                            label="Title"
                            placeholder="Enter issue title..."
                            required
                        />
                    </div>

                    <div>
                        <flux:textarea
                            wire:model="newIssueDescription"
                            label="Description"
                            placeholder="Describe the issue..."
                            rows="4"
                            required
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:select wire:model="newIssueStatus" label="Status">
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
                                <option value="closed">Closed</option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:select wire:model="newIssuePriority" label="Priority">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </flux:select>
                        </div>
                    </div>

                    <div>
                        <flux:input
                            wire:model="newIssueDueDate"
                            label="Due Date"
                            type="date"
                        />
                    </div>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button wire:click="closeCreateIssueModal" variant="ghost">
                        Cancel
                    </flux:button>
                    <flux:button wire:click="createIssue" variant="primary">
                        Create Issue
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

    {{-- Edit Issue Modal --}}
    @if ($showEditIssueModal && $selectedIssue)
        <flux:modal name="edit-issue-modal" wire:model.self="showEditIssueModal">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Edit Issue: "{{ $selectedIssue->title }}"</flux:heading>
                </div>

                <div class="space-y-4">
                    <div>
                        <flux:input
                            wire:model="editIssueTitle"
                            label="Title"
                            placeholder="Issue title..."
                            required
                        />
                    </div>

                    <div>
                        <flux:textarea
                            wire:model="editIssueDescription"
                            label="Description"
                            placeholder="Describe the issue..."
                            rows="4"
                            required
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:select wire:model="editIssueStatus" label="Status">
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
                                <option value="closed">Closed</option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:select wire:model="editIssuePriority" label="Priority">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </flux:select>
                        </div>
                    </div>

                    <div>
                        <flux:input
                            wire:model="editIssueDueDate"
                            label="Due Date"
                            type="date"
                        />
                    </div>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button wire:click="closeEditIssueModal" variant="ghost">
                        Cancel
                    </flux:button>
                    <flux:button wire:click="updateIssue" variant="primary">
                        Update Issue
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif
</div>
