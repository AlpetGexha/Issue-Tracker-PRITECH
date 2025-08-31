<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

final class CommentList extends Component
{
    use WithPagination;

    public Issue $issue;
    public string $newComment = '';

    public function mount(Issue $issue): void
    {
        $this->issue = $issue;
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|max:1000',
        ]);

        Comment::create([
            'body' => $this->newComment,
            'issue_id' => $this->issue->id,
            'user_id' => Auth::id(),
        ]);

        $this->newComment = '';

        // Reset to first page to show the new comment
        $this->resetPage();

        session()->flash('success', 'Comment added successfully!');
    }

    public function deleteComment(Comment $comment): void
    {
        if ($comment->user_id !== Auth::id()) {
            session()->flash('error', 'You can only delete your own comments.');

            return;
        }

        $comment->delete();

        // No need to manually refresh - pagination will handle it
    }

    public function render()
    {
        $comments = $this->issue->comments()
                ->with('user')
                ->latest()
                ->paginate(10);

        return view('livewire.comments.comment-list', compact('comments'));
    }
}
