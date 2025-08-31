<?php

use App\Livewire\Issues\IssueDetail;
use App\Livewire\Issues\MyIssues;
use App\Livewire\Project\ProjectDetail;
use App\Livewire\Project\ProjectList;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Tags\TagList;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/projects', ProjectList::class)->name('project.index');
    Route::get('/projects/{project}', ProjectDetail::class)->name('project.detail');

    Route::get('/issues/{issue}', IssueDetail::class)->name('issues.detail');
    Route::get('/my-issues', MyIssues::class)->name('issues.my');

    Route::get('/tags', TagList::class)->name('tags.index');
});

require __DIR__.'/auth.php';
