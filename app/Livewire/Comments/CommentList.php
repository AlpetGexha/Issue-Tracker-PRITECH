<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
final class CommentList extends Component
{
    use AuthorizesRequests, WithPagination;

    public Issue $issue;
    public string $newComment = '';

    public function mount(Issue $issue): void
    {
        $this->issue = $issue;
    }

    public function placeholder()
    {
        return view('skeletons.comment-list');
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|max:1000',
        ]);

        Comment::create([
            'body' => $this->newComment,
            'issue_id' => $this->issue->id,
        ]);

        $this->newComment = '';

        $this->resetPage();

        session()->flash('success', 'Comment added successfully!');
    }

    public function deleteComment(Comment $comment): void
    {
        $this->authorize('delete', $comment);

        $comment->delete();
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
