<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Tag;
use Exception;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class TagForm extends Form
{
    public ?Tag $tag = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|regex:/^#[0-9A-Fa-f]{6}$/')]
    public string $color = '#3B82F6';

    public function setTag(Tag $tag): void
    {
        $this->tag = $tag;
        $this->name = $tag->name;
        $this->color = $tag->color;
    }

    public function store(): Tag
    {
        $this->validate();

        $tag = Tag::create([
            'name' => $this->name,
            'color' => $this->color,
        ]);

        $this->reset();

        return $tag;
    }

    public function update(): Tag
    {
        if (! $this->tag) {
            throw new Exception('No tag set for update');
        }

        $this->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $this->tag->id,
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $this->tag->update([
            'name' => $this->name,
            'color' => $this->color,
        ]);

        return $this->tag->fresh();
    }

    public function resetToDefaults(): void
    {
        $this->reset();
        $this->color = '#3B82F6';
    }
}
