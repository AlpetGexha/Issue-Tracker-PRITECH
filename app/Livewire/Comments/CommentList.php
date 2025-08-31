<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

final class CommentList extends Component
{
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

        session()->flash('success', 'Comment added successfully!');
    }

    #[On('comment-added')]
    public function refreshComments(): void
    {
        $this->issue->load('comments.user');
    }

    public function deleteComment(Comment $comment): void
    {
        if ($comment->user_id !== Auth::id()) {
            session()->flash('error', 'You can only delete your own comments.');

            return;
        }

        $comment->delete();
        session()->flash('success', 'Comment deleted successfully!');
        $this->refreshComments();
    }

    public function render()
    {
        $comments = $this->issue->comments()->with('user')->latest()->get();

        return view('livewire.comments.comment-list', compact('comments'));
    }
}
