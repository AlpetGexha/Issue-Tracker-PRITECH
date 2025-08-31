<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tags Management</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage tags for organizing issues</p>
        </div>

        <flux:button wire:click="openCreateModal" variant="primary">
            <flux:icon icon="plus" class="size-4 mr-1" />
            Create Tag
        </flux:button>
    </div>

    {{-- Search --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="Search tags..."
            class="max-w-md"
        />
    </div>

    {{-- Tags Table --}}
    @if ($tags->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tag
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Color
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Issues Count
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Created
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($tags as $tag)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white mr-3"
                                          style="background-color: {{ $tag->color }}">
                                        {{ $tag->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded border mr-2" style="background-color: {{ $tag->color }}"></div>
                                    <span class="text-sm font-mono text-gray-900 dark:text-white">{{ $tag->color }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $tag->issues_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $tag->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <flux:button
                                        wire:click="openEditModal({{ $tag->id }})"
                                        variant="subtle"
                                        size="sm"
                                    >
                                        <flux:icon icon="pencil-square" class="size-4" />
                                    </flux:button>

                                    <flux:button
                                        wire:click="deleteTag({{ $tag->id }})"
                                        wire:confirm="Are you sure you want to delete this tag? This action cannot be undone."
                                        variant="danger"
                                        size="sm"
                                    >
                                        <flux:icon icon="trash" class="size-4" />
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $tags->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <flux:icon icon="tag" class="size-12 text-gray-400 mx-auto mb-4" />
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No tags found</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                @if ($search)
                    No tags match your search criteria.
                @else
                    Get started by creating your first tag.
                @endif
            </p>
            @if (!$search)
                <flux:button wire:click="openCreateModal" variant="primary">
                    <flux:icon icon="plus" class="size-4 mr-1" />
                    Create Your First Tag
                </flux:button>
            @endif
        </div>
    @endif

    {{-- Create Tag Modal --}}
    <flux:modal wire:model.self="showCreateModal" name="create-tag">
        <form wire:submit="createTag" class="space-y-6">
            <div>
                <flux:heading size="lg">Create New Tag</flux:heading>
                <flux:subheading>Add a new tag to organize your issues</flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:field>
                    <flux:label>Tag Name</flux:label>
                    <flux:input wire:model="createForm.name" placeholder="Enter tag name" />
                    <flux:error name="createForm.name" />
                </flux:field>

                <flux:field>
                    <flux:label>Color</flux:label>
                    <div class="flex items-center space-x-3">
                        <input
                            type="color"
                            wire:model.live="createForm.color"
                            class="w-12 h-10 rounded border border-gray-300 dark:border-gray-600"
                        />
                        <flux:input wire:model="createForm.color" placeholder="#3B82F6" class="flex-1" />
                    </div>
                    <flux:error name="createForm.color" />
                </flux:field>

                {{-- Preview --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                    <flux:label>Preview</flux:label>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white"
                              style="background-color: {{ $createForm->color }}">
                            {{ $createForm->name ?: 'Tag Name' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <flux:button wire:click="closeCreateModal" variant="ghost">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Create Tag</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Edit Tag Modal --}}
    <flux:modal wire:model.self="showEditModal" name="edit-tag">
        <form wire:submit="updateTag" class="space-y-6">
            <div>
                <flux:heading size="lg">Edit Tag</flux:heading>
                <flux:subheading>Update tag information</flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:field>
                    <flux:label>Tag Name</flux:label>
                    <flux:input wire:model="editForm.name" placeholder="Enter tag name" />
                    <flux:error name="editForm.name" />
                </flux:field>

                <flux:field>
                    <flux:label>Color</flux:label>
                    <div class="flex items-center space-x-3">
                        <input
                            type="color"
                            wire:model.live="editForm.color"
                            class="w-12 h-10 rounded border border-gray-300 dark:border-gray-600"
                        />
                        <flux:input wire:model="editForm.color" placeholder="#3B82F6" class="flex-1" />
                    </div>
                    <flux:error name="editForm.color" />
                </flux:field>

                {{-- Preview --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                    <flux:label>Preview</flux:label>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white"
                              style="background-color: {{ $editForm->color }}">
                            {{ $editForm->name ?: 'Tag Name' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <flux:button wire:click="closeEditModal" variant="ghost">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Update Tag</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
