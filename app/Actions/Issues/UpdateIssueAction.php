<?php

namespace App\Actions\Issues;

use App\Models\Issue;
use Illuminate\Support\Facades\DB;

class UpdateIssueAction
{
    public function execute(Issue $issue, array $data): Issue
    {
        return DB::transaction(function () use ($issue, $data) {
            $issue->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $data['status'],
                'priority' => $data['priority'],
                'due_date' => $data['due_date'] ?? null,
            ]);

            return $issue->fresh();
        });
    }
}
