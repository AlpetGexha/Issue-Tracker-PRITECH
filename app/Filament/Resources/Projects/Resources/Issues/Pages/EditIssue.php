<?php

namespace App\Filament\Resources\Projects\Resources\Issues\Pages;

use App\Filament\Resources\Projects\Resources\Issues\IssueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIssue extends EditRecord
{
    protected static string $resource = IssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
