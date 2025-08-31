<?php

declare(strict_types=1);

namespace App\Livewire\Tags;

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
    use WithPagination;

    #[Url]
    public string $search = '';

    // Modal states
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
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

    public function openCreateModal(): void
    {
        $this->createForm->resetToDefaults();
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->createForm->resetToDefaults();
    }

    public function openEditModal(Tag $tag): void
    {
        $this->selectedTag = $tag;
        $this->editForm->setTag($tag);
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->selectedTag = null;
        $this->editForm->resetToDefaults();
    }

    public function createTag(): void
    {
        $tag = $this->createForm->store();

        $this->closeCreateModal();
        $this->dispatch('notify', message: 'Tag created successfully!', type: 'success');
    }

    public function updateTag(): void
    {
        if (! $this->selectedTag) {
            $this->closeEditModal();

            return;
        }

        $this->editForm->update();

        $this->closeEditModal();
        $this->dispatch('notify', message: 'Tag updated successfully!', type: 'success');
    }

    public function deleteTag(Tag $tag): void
    {
        $tag->delete();
        $this->dispatch('notify', message: 'Tag deleted successfully!', type: 'success');
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
}
