<?php

declare(strict_types=1);

namespace App\Livewire\Tags;

use App\Livewire\Concerns\WithModalActions;
use App\Livewire\Forms\TagForm;
use App\Models\Tag;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Tags Management')]
#[Lazy]
final class TagList extends Component
{
    use WithModalActions, WithPagination;

    #[Url]
    public string $search = '';

    public ?Tag $selectedTag = null;

    // Forms
    public TagForm $createForm;
    public TagForm $editForm;

    public function placeholder()
    {
        return view('skeletons.tag-list');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // Override trait methods
    public function setEditForm($tag): void
    {
        $this->selectedTag = $tag;
        $this->editForm->setTag($tag);
    }

    public function createTag(): void
    {
        $this->createForm->store();
        $this->closeCreateModal();
        $this->notifySuccess('Tag created successfully!');
    }

    public function updateTag(): void
    {
        if (! $this->selectedTag) {
            $this->closeEditModal();

            return;
        }

        $this->editForm->update();
        $this->closeEditModal();
        $this->notifySuccess('Tag updated successfully!');
    }

    public function deleteTag(Tag $tag): void
    {
        $this->confirmDelete($tag->name, 'performDeleteTag', [$tag->id]);
    }

    public function performDeleteTag(int $tagId): void
    {
        $tag = Tag::find($tagId);
        if ($tag) {
            $tag->delete();
            $this->notifySuccess('Tag deleted successfully!');
        }
    }

    public function render()
    {
        $tags = Tag::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->withCount('issues')
            ->latest()
            ->paginate(15);

        return view('livewire.tags.tag-list', compact('tags'));
    }

    protected function resetEditForm(): void
    {
        $this->editForm->resetToDefaults();
        $this->selectedTag = null;
    }

    protected function resetCreateForm(): void
    {
        $this->createForm->resetToDefaults();
    }
}
