<?php

declare(strict_types=1);

namespace App\Filament\Resources\Projects\Resources\Issues\Pages;

use App\Filament\Resources\Projects\Resources\Issues\IssueResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateIssue extends CreateRecord
{
    protected static string $resource = IssueResource::class;
}
