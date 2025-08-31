<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class IssueForm extends Form
{
    public ?Issue $issue = null;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string|max:2000')]
    public ?string $description = '';

    #[Validate('required|exists:projects,id')]
    public int $projectId = 0;

    #[Validate('required|in:open,in_progress,closed')]
    public string $status = 'open';

    #[Validate('required|in:low,medium,high')]
    public string $priority = 'medium';

    #[Validate('nullable|date|after:today')]
    public ?string $dueDate = null;

    #[Validate('array')]
    public array $selectedTags = [];

    #[Validate('array')]
    public array $selectedAssignees = [];

    public string $tagSearch = '';
    public string $assigneeSearch = '';

    public function setIssue(Issue $issue): void
    {
        $this->issue = $issue;
        $this->title = $issue->title;
        $this->description = $issue->description ?? '';
        $this->projectId = $issue->project_id;
        $this->status = $issue->status->value;
        $this->priority = $issue->priority->value;
        $this->dueDate = $issue->due_date instanceof Carbon
            ? $issue->due_date->format('Y-m-d')
            : ($issue->due_date ? Carbon::parse($issue->due_date)->format('Y-m-d') : null);
        $this->selectedTags = $issue->tags->pluck('id')->toArray();
        $this->selectedAssignees = $issue->users->pluck('id')->toArray();
    }

    public function setProject(Project $project): void
    {
        $this->projectId = $project->id;
    }

    public function store(): Issue
    {
        $this->validate();

        $issue = Issue::create([
            'title' => $this->title,
            'description' => $this->description,
            'project_id' => $this->projectId,
            'status' => ProjectStatus::from($this->status),
            'priority' => ProjectPriority::from($this->priority),
            'due_date' => $this->dueDate ? Carbon::parse($this->dueDate) : null,
        ]);

        // Attach tags
        if (! empty($this->selectedTags)) {
            $issue->tags()->attach($this->selectedTags);
        }

        // Attach assignees
        if (! empty($this->selectedAssignees)) {
            $issue->users()->attach($this->selectedAssignees);
        }

        $this->reset();

        return $issue;
    }

    public function update(): Issue
    {
        $this->validate();

        $this->issue->update([
            'title' => $this->title,
            'description' => $this->description,
            'project_id' => $this->projectId,
            'status' => ProjectStatus::from($this->status),
            'priority' => ProjectPriority::from($this->priority),
            'due_date' => $this->dueDate ? Carbon::parse($this->dueDate) : null,
        ]);

        // Sync tags
        $this->issue->tags()->sync($this->selectedTags);

        // Sync assignees
        $this->issue->users()->sync($this->selectedAssignees);

        return $this->issue->fresh();
    }

    public function searchAvailableTags(): \Illuminate\Database\Eloquent\Collection
    {
        if (empty($this->tagSearch)) {
            return collect();
        }

        return Tag::where('name', 'like', '%' . $this->tagSearch . '%')
            ->whereNotIn('id', $this->selectedTags)
            ->limit(10)
            ->get();
    }

    public function searchAvailableAssignees(): \Illuminate\Database\Eloquent\Collection
    {
        if (empty($this->assigneeSearch)) {
            return collect();
        }

        // Only search within project users for better UX
        if ($this->projectId > 0) {
            $project = Project::find($this->projectId);

            return $project->users()
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->assigneeSearch . '%')
                        ->orWhere('email', 'like', '%' . $this->assigneeSearch . '%');
                })
                ->whereNotIn('users.id', $this->selectedAssignees)
                ->limit(10)
                ->get();
        }

        return User::where('name', 'like', '%' . $this->assigneeSearch . '%')
            ->orWhere('email', 'like', '%' . $this->assigneeSearch . '%')
            ->whereNotIn('id', $this->selectedAssignees)
            ->limit(10)
            ->get();
    }

    public function addTag(int $tagId): void
    {
        if (! in_array($tagId, $this->selectedTags)) {
            $this->selectedTags[] = $tagId;
        }
        $this->tagSearch = '';
    }

    public function removeTag(int $tagId): void
    {
        $this->selectedTags = array_values(
            array_filter($this->selectedTags, fn ($id) => $id !== $tagId)
        );
    }

    public function addAssignee(int $userId): void
    {
        if (! in_array($userId, $this->selectedAssignees)) {
            $this->selectedAssignees[] = $userId;
        }
        $this->assigneeSearch = '';
    }

    public function removeAssignee(int $userId): void
    {
        $this->selectedAssignees = array_values(
            array_filter($this->selectedAssignees, fn ($id) => $id !== $userId)
        );
    }

    public function getSelectedTagsCollection(): \Illuminate\Database\Eloquent\Collection
    {
        if (empty($this->selectedTags)) {
            return collect();
        }

        return Tag::whereIn('id', $this->selectedTags)->get();
    }

    public function getSelectedAssigneesCollection(): \Illuminate\Database\Eloquent\Collection
    {
        if (empty($this->selectedAssignees)) {
            return collect();
        }

        return User::whereIn('id', $this->selectedAssignees)->get();
    }

    public function getStatusOptions(): array
    {
        return [
            ProjectStatus::Open->value => 'Open',
            ProjectStatus::InProgress->value => 'In Progress',
            ProjectStatus::Closed->value => 'Closed',
        ];
    }

    public function getPriorityOptions(): array
    {
        return [
            ProjectPriority::Low->value => 'Low',
            ProjectPriority::Medium->value => 'Medium',
            ProjectPriority::High->value => 'High',
        ];
    }
}
