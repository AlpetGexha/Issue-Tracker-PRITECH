<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Projects</h1>
        <flux:button wire:click="openCreateModal" icon="plus" variant="primary">
            Create Project
        </flux:button>
    </div>

    <!-- Search Section -->
    <div class="w-full max-w-md">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="Search projects..."
            icon="magnifying-glass"
        />
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($projects as $project)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <!-- Project Header -->
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            <a wire:navigate href="{{ route('project.detail', $project->id) }}"
                               class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                {{ $project->name }}
                            </a>
                        </h3>
                        @if ($project->description)
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                {{ $project->description }}
                            </p>
                        @endif
                    </div>
                    <!-- Project Actions -->
                    <flux:dropdown position="bottom-end">
                        <flux:button icon="ellipsis-vertical" variant="ghost" size="sm" />
                        <flux:menu>
                            <flux:menu.item wire:click="openEditModal({{ $project->id }})" icon="pencil">
                                Edit Project
                            </flux:menu.item>
                            <flux:menu.separator />
                            @can('delete', $project)
                                <flux:menu.item
                                    wire:click="deleteProject({{ $project->id }})"
                                    wire:confirm="Are you sure you want to delete this project?"
                                    icon="trash"
                                    variant="danger">
                                    Delete Project
                                </flux:menu.item>
                            @endcan
                        </flux:menu>
                    </flux:dropdown>
                </div>

                <!-- Project Stats -->
                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <span class="flex items-center gap-1">
                        <flux:icon.document-text class="w-4 h-4" />
                        {{ $project->issues_count }} {{ Str::plural('issue', $project->issues_count) }}
                    </span>
                    @if ($project->deadline)
                        <span class="flex items-center gap-1">
                            <flux:icon.calendar class="w-4 h-4" />
                            {{ $project->deadline->format('M j, Y') }}
                        </span>
                    @endif
                </div>

                <!-- Project Owners -->
                @if ($project->owners->isNotEmpty())
                    <div class="mb-4">
                        <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                            Project Owners
                        </h4>
                        <div class="flex flex-wrap gap-1">
                            @foreach ($project->owners->take(3) as $owner)
                                <flux:badge variant="lime" size="sm">
                                    {{ $owner->name }}
                                </flux:badge>
                            @endforeach
                            @if ($project->owners->count() > 3)
                                <flux:badge variant="gray" size="sm">
                                    +{{ $project->owners->count() - 3 }} more
                                </flux:badge>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <flux:icon.folder class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No projects found</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by creating your first project.</p>
                <flux:button wire:click="openCreateModal" variant="primary">
                    Create Project
                </flux:button>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($projects->hasPages())
        <div class="mt-6">
            {{ $projects->links() }}
        </div>
    @endif

    <!-- Create Project Modal -->
    <flux:modal wire:model="modals.create" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create New Project</flux:heading>
                <flux:subheading>Fill in the details to create a new project.</flux:subheading>
            </div>

            <form wire:submit="createProject" class="space-y-6">
                <!-- Project Name -->
                <flux:field>
                    <flux:label>Project Name</flux:label>
                    <flux:input wire:model="createForm.name" placeholder="Enter project name" />
                    <flux:error name="createForm.name" />
                </flux:field>

                <!-- Project Description -->
                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="createForm.description" placeholder="Enter project description" rows="3" />
                    <flux:error name="createForm.description" />
                </flux:field>

                <!-- Start Date -->
                <flux:field>
                    <flux:label>Start Date</flux:label>
                    <flux:input type="date" wire:model="createForm.startDate" />
                    <flux:error name="createForm.startDate" />
                </flux:field>

                <!-- Deadline -->
                <flux:field>
                    <flux:label>Deadline</flux:label>
                    <flux:input type="date" wire:model="createForm.deadline" />
                    <flux:error name="createForm.deadline" />
                </flux:field>

                <!-- Project Owners -->
                <flux:field>
                    <flux:label>Project Owners</flux:label>
                    <flux:input
                        wire:model.live.debounce.300ms="createForm.ownerSearch"
                        placeholder="Search users by name or email..."
                    />
                    <flux:error name="createForm.selectedOwners" />

                    <!-- Selected Owners -->
                    @if (!empty($createForm->selectedOwners))
                        <div class="mt-2 space-y-1">
                            @foreach ($createForm->selectedOwners as $userId)
                                @php
                                    $user = $createUsers->firstWhere('id', $userId) ?? \App\Models\User::find($userId);
                                @endphp
                                @if ($user)
                                    <div class="flex items-center justify-between bg-blue-50 dark:bg-blue-900/20 px-3 py-2 rounded-md">
                                        <span class="text-sm text-blue-700 dark:text-blue-300">{{ $user->name }}</span>
                                        <flux:button
                                            type="button"
                                            wire:click="removeCreateOwner({{ $userId }})"
                                            icon="x-mark"
                                            variant="ghost"
                                            size="xs"
                                        />
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <!-- User Search Results -->
                    @if ($createForm->ownerSearch && $createUsers->isNotEmpty())
                        <div class="mt-2 max-h-40 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-md">
                            @foreach ($createUsers as $user)
                                @if (!in_array($user->id, $createForm->selectedOwners))
                                    <button
                                        type="button"
                                        wire:click="addCreateOwner({{ $user->id }})"
                                        class="w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700 last:border-0"
                                    >
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </flux:field>

                <!-- Modal Actions -->
                <div class="flex justify-end space-x-2">
                    <flux:button type="button" wire:click="closeCreateModal" variant="ghost">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Create Project
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Edit Project Modal -->
    <flux:modal wire:model="modals.edit" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit Project</flux:heading>
                <flux:subheading>Update the project details.</flux:subheading>
            </div>

            <form wire:submit="updateProject" class="space-y-6">
                <!-- Project Name -->
                <flux:field>
                    <flux:label>Project Name</flux:label>
                    <flux:input wire:model="editForm.name" placeholder="Enter project name" />
                    <flux:error name="editForm.name" />
                </flux:field>

                <!-- Project Description -->
                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="editForm.description" placeholder="Enter project description" rows="3" />
                    <flux:error name="editForm.description" />
                </flux:field>

                <!-- Start Date -->
                <flux:field>
                    <flux:label>Start Date</flux:label>
                    <flux:input type="date" wire:model="editForm.startDate" />
                    <flux:error name="editForm.startDate" />
                </flux:field>

                <!-- Deadline -->
                <flux:field>
                    <flux:label>Deadline</flux:label>
                    <flux:input type="date" wire:model="editForm.deadline" />
                    <flux:error name="editForm.deadline" />
                </flux:field>

                <!-- Project Owners -->
                <flux:field>
                    <flux:label>Project Owners</flux:label>
                    <flux:input
                        wire:model.live.debounce.300ms="editForm.ownerSearch"
                        placeholder="Search users by name or email..."
                    />
                    <flux:error name="editForm.selectedOwners" />

                    <!-- Selected Owners -->
                    @if (!empty($editForm->selectedOwners))
                        <div class="mt-2 space-y-1">
                            @foreach ($editForm->selectedOwners as $userId)
                                @php
                                    $user = $editUsers->firstWhere('id', $userId) ?? \App\Models\User::find($userId);
                                @endphp
                                @if ($user)
                                    <div class="flex items-center justify-between bg-blue-50 dark:bg-blue-900/20 px-3 py-2 rounded-md">
                                        <span class="text-sm text-blue-700 dark:text-blue-300">{{ $user->name }}</span>
                                        <flux:button
                                            type="button"
                                            wire:click="removeEditOwner({{ $userId }})"
                                            icon="x-mark"
                                            variant="ghost"
                                            size="xs"
                                        />
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <!-- User Search Results -->
                    @if ($editForm->ownerSearch && $editUsers->isNotEmpty())
                        <div class="mt-2 max-h-40 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-md">
                            @foreach ($editUsers as $user)
                                @if (!in_array($user->id, $editForm->selectedOwners))
                                    <button
                                        type="button"
                                        wire:click="addEditOwner({{ $user->id }})"
                                        class="w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700 last:border-0"
                                    >
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </flux:field>

                <!-- Modal Actions -->
                <div class="flex justify-end space-x-2">
                    <flux:button type="button" wire:click="closeEditModal" variant="ghost">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Update Project
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
