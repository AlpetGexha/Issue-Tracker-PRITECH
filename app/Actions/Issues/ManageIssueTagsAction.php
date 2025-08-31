<?php

declare(strict_types=1);

namespace App\Actions\Issues;

use App\Models\Issue;
use Illuminate\Support\Facades\DB;

final class ManageIssueTagsAction
{
    public function execute(Issue $issue, array $tagIds): Issue
    {
        return DB::transaction(function () use ($issue, $tagIds) {
            $issue->tags()->sync($tagIds);

            return $issue->fresh(['tags']);
        });
    }
}
