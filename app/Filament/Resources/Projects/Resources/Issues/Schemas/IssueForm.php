<?php

declare(strict_types=1);

namespace App\Filament\Resources\Projects\Resources\Issues\Schemas;

use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class IssueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(ProjectStatus::class)
                    ->default('open')
                    ->required(),
                Select::make('priority')
                    ->options(ProjectPriority::class)
                    ->default('medium')
                    ->required(),

                Select::make('users')
                    ->relationship('users', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->label('Assigned Users')
                    ->columnSpanFull(),

                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->label('Tags')
                    ->columnSpanFull(),

                DatePicker::make('due_date'),
            ]);
    }
}
