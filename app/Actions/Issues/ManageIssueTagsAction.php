<?php

namespace App\Actions\Issues;

use App\Models\Issue;
use Illuminate\Support\Facades\DB;

class ManageIssueTagsAction
{
    public function execute(Issue $issue, array $tagIds): Issue
    {
        return DB::transaction(function () use ($issue, $tagIds) {
            $issue->tags()->sync($tagIds);
            return $issue->fresh(['tags']);
        });
    }
}
