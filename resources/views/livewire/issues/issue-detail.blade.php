<div class="space-y-6">
    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg dark:bg-green-900/20 dark:border-green-800 dark:text-green-200">
            <div class="flex items-center">
                <span class="w-5 h-5 mr-2 text-green-600">✓</span>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg dark:bg-red-900/20 dark:border-red-800 dark:text-red-200">
            <div class="flex items-center">
                <span class="w-5 h-5 mr-2 text-red-600">⚠</span>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Issue Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ $issue->title }}
                </h1>

                <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400 mb-4">
                    <span>Project: <span class="font-medium">{{ $issue->project->name }}</span></span>
                    <span>•</span>
                    <span>Created {{ $issue->created_at->diffForHumans() }}</span>
                    @if ($issue->due_date)
                        <span>•</span>
                        <span>Due {{ $issue->due_date->format('M j, Y') }}</span>
                    @endif
                </div>

                <div class="flex items-center space-x-3 mb-4">
                    <flux:badge :variant="$issue->status->value === 'Open' ? 'success' : ($issue->status->value === 'In Progress' ? 'warning' : 'default')">
                        {{ $issue->status->value }}
                    </flux:badge>

                    <flux:badge :variant="$issue->priority->value === 'High' ? 'danger' : ($issue->priority->value === 'Medium' ? 'warning' : 'default')">
                        {{ $issue->priority->value }} Priority
                    </flux:badge>
                </div>

                {{-- Tags --}}
                @if ($issue->tags->count() > 0)
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Tags:</span>
                        @foreach ($issue->tags as $tag)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white"
                                  style="background-color: {{ $tag->color }}">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                {{-- Assigned Users --}}
                @if ($issue->users->count() > 0)
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Assigned to:</span>
                        <div class="flex -space-x-1">
                            @foreach ($issue->users->take(3) as $user)
                                 <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white"
                                  >
                                {{ $user->name }}
                            </span>
                            @endforeach
                            @if ($issue->users->count() > 3)
                                <span class="text-xs text-gray-500 ml-2">+{{ $issue->users->count() - 3 }} more</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex space-x-2">
                @can('update', $issue)
                    <flux:button variant="subtle" size="sm" wire:click="openEditModal">
                        <flux:icon icon="pencil-square" class="size-4" />
                        Edit
                    </flux:button>
                @endcan

                <flux:button variant="subtle" size="sm" wire:click="shareIssue">
                    <flux:icon icon="share" class="size-4" />
                    Share
                </flux:button>

                @can('delete', $issue)
                    <flux:button
                        variant="danger"
                        size="sm"
                        wire:click="deleteIssue"
                        wire:confirm="Are you sure you want to delete this issue? This action cannot be undone."
                    >
                        <flux:icon icon="trash" class="size-4" />
                        Delete
                    </flux:button>
                @endcan
            </div>
        </div>
    </div>

    {{-- Issue Description --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h2>
        <div class="prose dark:prose-invert max-w-none">
            {!! nl2br(e($issue->description)) !!}
        </div>
    </div>

    {{-- Comments Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Comments</h2>
        </div>

        {{-- Comments Component --}}
        <livewire:comments.comment-list :issue="$issue" />
    </div>

    {{-- Edit Modal --}}
        {{-- Edit Modal --}}
    <flux:modal name="edit-issue" wire:model="showEditModal">
        <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
            <flux:heading size="lg">Edit Issue</flux:heading>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <flux:field>
                    <flux:label>Title</flux:label>
                    <flux:input wire:model="editForm.title" placeholder="Enter issue title..." />
                    <flux:error name="editForm.title" />
                </flux:field>
            </div>

            <div>
                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="editForm.description" placeholder="Enter issue description..." rows="4" />
                    <flux:error name="editForm.description" />
                </flux:field>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <flux:field>
                        <flux:label>Status</flux:label>
                        <flux:select wire:model="editForm.status">
                            <flux:select.option value="open">Open</flux:select.option>
                            <flux:select.option value="in_progress">In Progress</flux:select.option>
                            <flux:select.option value="closed">Closed</flux:select.option>
                        </flux:select>
                        <flux:error name="editForm.status" />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Priority</flux:label>
                        <flux:select wire:model="editForm.priority">
                            <flux:select.option value="low">Low</flux:select.option>
                            <flux:select.option value="medium">Medium</flux:select.option>
                            <flux:select.option value="high">High</flux:select.option>
                        </flux:select>
                        <flux:error name="editForm.priority" />
                    </flux:field>
                </div>
            </div>

            <div>
                <flux:field>
                    <flux:label>Due Date (Optional)</flux:label>
                    <flux:input type="date" wire:model="editForm.dueDate" />
                    <flux:error name="editForm.dueDate" />
                </flux:field>
            </div>
        </div>

        <div class="p-6 border-t border-zinc-200 dark:border-zinc-700 flex gap-2">
            <flux:button wire:click="closeEditModal" variant="subtle">Cancel</flux:button>
            <flux:button wire:click="updateIssue" variant="primary">Update Issue</flux:button>
        </div>
    </flux:modal>
</div>

<script>
    // Handle copy to clipboard functionality
    document.addEventListener('livewire:dispatched', function(event) {
        if (event.detail.type === 'copy-to-clipboard') {
            navigator.clipboard.writeText(event.detail.text).then(function() {
                console.log('URL copied to clipboard');
            });
        }
    });
</script>
