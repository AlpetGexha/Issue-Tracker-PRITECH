<?php

namespace App\Filament\Schemas\Components;

use Closure;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Collection;

class CommentsList extends Component
{
    protected string $view = 'filament.schemas.components.comments-list';

    protected Collection | Closure | null $comments = null;
    protected int | Closure | null $maxHeight = null;
    protected bool | Closure $showTimestamp = true;
    protected bool | Closure $showAvatar = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public function comments(Collection | Closure | null $comments): static
    {
        $this->comments = $comments;
        return $this;
    }

    public function maxHeight(int | Closure | null $maxHeight): static
    {
        $this->maxHeight = $maxHeight;
        return $this;
    }

    public function showTimestamp(bool | Closure $showTimestamp = true): static
    {
        $this->showTimestamp = $showTimestamp;
        return $this;
    }

    public function showAvatar(bool | Closure $showAvatar = true): static
    {
        $this->showAvatar = $showAvatar;
        return $this;
    }

    public function getComments(): ?Collection
    {
        return $this->evaluate($this->comments);
    }

    public function getMaxHeight(): ?int
    {
        return $this->evaluate($this->maxHeight);
    }

    public function getShowTimestamp(): bool
    {
        return $this->evaluate($this->showTimestamp);
    }

    public function getShowAvatar(): bool
    {
        return $this->evaluate($this->showAvatar);
    }
}
