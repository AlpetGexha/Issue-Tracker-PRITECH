<?php

declare(strict_types=1);

namespace App\Livewire\Tags;

use App\Models\Tag;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Tags Management')]
final class TagList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    // Modal states
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?Tag $selectedTag = null;

    // Create tag form
    public string $name = '';
    public string $color = '#3B82F6';

    // Edit tag form
    public string $editName = '';
    public string $editColor = '#3B82F6';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetCreateForm();
    }

    public function openEditModal(Tag $tag): void
    {
        $this->selectedTag = $tag;
        $this->editName = $tag->name;
        $this->editColor = $tag->color;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->selectedTag = null;
        $this->resetEditForm();
    }

    public function createTag(): void
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Tag::create([
            'name' => $this->name,
            'color' => $this->color,
        ]);

        $this->closeCreateModal();
        $this->dispatch('notify', message: 'Tag created successfully!', type: 'success');
    }

    public function updateTag(): void
    {
        if (! $this->selectedTag) {
            $this->closeEditModal();

            return;
        }

        $this->validate([
            'editName' => 'required|string|max:255|unique:tags,name,' . $this->selectedTag->id,
            'editColor' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $this->selectedTag->update([
            'name' => $this->editName,
            'color' => $this->editColor,
        ]);

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

    private function resetCreateForm(): void
    {
        $this->name = '';
        $this->color = '#3B82F6';
    }

    private function resetEditForm(): void
    {
        $this->editName = '';
        $this->editColor = '#3B82F6';
    }
}
