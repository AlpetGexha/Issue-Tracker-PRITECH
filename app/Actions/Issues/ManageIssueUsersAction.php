<?php

declare(strict_types=1);

namespace App\Actions\Issues;

use App\Models\Issue;
use Illuminate\Support\Facades\DB;

final class ManageIssueUsersAction
{
    public function execute(Issue $issue, array $userIds): Issue
    {
        return DB::transaction(function () use ($issue, $userIds) {
            $issue->users()->sync($userIds);

            return $issue->fresh(['users']);
        });
    }
}
