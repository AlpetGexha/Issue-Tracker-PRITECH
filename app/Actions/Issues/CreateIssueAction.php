<?php

namespace App\Actions\Issues;

use App\Models\Issue;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class CreateIssueAction
{
    public function execute(Project $project, array $data): Issue
    {
        return DB::transaction(function () use ($project, $data) {
            return $project->issues()->create([
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $data['status'],
                'priority' => $data['priority'],
                'due_date' => $data['due_date'] ?? null,
            ]);
        });
    }
}
