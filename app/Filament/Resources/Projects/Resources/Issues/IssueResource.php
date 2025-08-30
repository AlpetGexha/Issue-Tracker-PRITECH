<?php

namespace App\Filament\Resources\Projects\Resources\Issues;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Resources\Issues\Pages\CreateIssue;
use App\Filament\Resources\Projects\Resources\Issues\Pages\EditIssue;
use App\Filament\Resources\Projects\Resources\Issues\Schemas\IssueForm;
use App\Filament\Resources\Projects\Resources\Issues\Tables\IssuesTable;
use App\Models\Issue;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IssueResource extends Resource
{
    protected static ?string $model = Issue::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = ProjectResource::class;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return IssueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IssuesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'view' => \App\Filament\Resources\Projects\Resources\Issues\Pages\ViewIssue::route('/{record}'),
            // 'create' => CreateIssue::route('/create'),
            // 'edit' => EditIssue::route('/{record}/edit'),
        ];
    }
}
