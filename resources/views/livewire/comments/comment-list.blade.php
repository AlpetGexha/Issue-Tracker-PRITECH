<div class="space-y-4 p-6">
    {{-- Add New Comment Form --}}
    <div class="space-y-4">
        <div>
            <flux:textarea
                wire:model="newComment"
                placeholder="Add a comment..."
                rows="3"
                class="w-full"
            />
            @error('newComment')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end">
            <flux:button
                wire:click="addComment"
                variant="primary"
                wire:loading.attr="disabled"
                wire:target="addComment"
            >
                <span wire:loading.remove wire:target="addComment">
                    <flux:icon icon="plus" class="size-4 mr-1" />
                    Add Comment
                </span>
                <span wire:loading wire:target="addComment">
                    Adding...
                </span>
            </flux:button>
        </div>
    </div>

    {{-- Comments List --}}
    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
        {{-- Comments Header --}}
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                Comments ({{ $comments->total() }})
            </h3>
        </div>

        <div class="space-y-4">
            @forelse ($comments as $comment)
                <div class="flex space-x-3">
                    <flux:avatar size="sm" :alt="$comment->user->name" />

                    <div class="flex-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $comment->user->name }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>

                            @if ($comment->user_id === auth()->id())
                                <flux:button
                                    wire:click="deleteComment({{ $comment->id }})"
                                    wire:confirm="Are you sure you want to delete this comment?"
                                    variant="ghost"
                                    size="sm"
                                    class="text-red-500 hover:text-red-600"
                                >
                                    <flux:icon icon="trash" class="size-3" />
                                </flux:button>
                            @endif
                        </div>

                        <div class="text-gray-700 dark:text-gray-300">
                            {!! nl2br(e($comment->body)) !!}
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                    No comments yet. Be the first to comment!
                </p>
            @endforelse
        </div>

        {{-- Pagination Links --}}
        @if ($comments->hasPages())
            <div class="mt-6">
                {{ $comments->links() }}
            </div>
        @endif
    </div>
</div>
