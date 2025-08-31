<?php

declare(strict_types=1);

use App\Livewire\Project\ProjectList;
use App\Models\User;

test('can add users as project owners in create form', function () {
    $users = User::factory()->count(3)->create();

    $component = livewire(ProjectList::class);

    // Initially no owners selected
    expect($component->createForm->selectedOwners)->toBeEmpty();

    // Add first owner
    $component->call('createForm.addOwner', $users[0]->id);
    expect($component->createForm->selectedOwners)->toContain($users[0]->id);

    // Add second owner
    $component->call('createForm.addOwner', $users[1]->id);
    expect($component->createForm->selectedOwners)->toHaveCount(2);
    expect($component->createForm->selectedOwners)->toContain($users[0]->id);
    expect($component->createForm->selectedOwners)->toContain($users[1]->id);

    // Cannot add duplicate owner
    $component->call('createForm.addOwner', $users[0]->id);
    expect($component->createForm->selectedOwners)->toHaveCount(2);
});

test('can remove users from project owners in create form', function () {
    $users = User::factory()->count(2)->create();

    $component = livewire(ProjectList::class);
    $component->createForm->selectedOwners = [$users[0]->id, $users[1]->id];

    // Remove first owner
    $component->call('createForm.removeOwner', $users[0]->id);
    expect($component->createForm->selectedOwners)->toHaveCount(1);
    expect($component->createForm->selectedOwners)->toContain($users[1]->id);
    expect($component->createForm->selectedOwners)->not->toContain($users[0]->id);

    // Remove second owner
    $component->call('createForm.removeOwner', $users[1]->id);
    expect($component->createForm->selectedOwners)->toBeEmpty();
});

test('can search for available owners in create form', function () {
    $targetUser = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
    $otherUser = User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);
    $selectedUser = User::factory()->create(['name' => 'Already Selected']);

    $component = livewire(ProjectList::class);
    $component->createForm->selectedOwners = [$selectedUser->id];
    $component->createForm->ownerSearch = 'john';

    $results = $component->createForm->searchAvailableOwners();

    expect($results)->toHaveCount(1);
    expect($results->first()->id)->toBe($targetUser->id);
    expect($results->pluck('id'))->not->toContain($selectedUser->id);
});

test('search returns empty collection when search term is empty in create form', function () {
    User::factory()->count(3)->create();

    $component = livewire(ProjectList::class);
    $component->createForm->ownerSearch = '';

    $results = $component->createForm->searchAvailableOwners();

    expect($results)->toBeEmpty();
});

test('can get selected owners as user models in create form', function () {
    $users = User::factory()->count(2)->create();

    $component = livewire(ProjectList::class);
    $component->createForm->selectedOwners = $users->pluck('id')->toArray();

    $selectedOwners = $component->createForm->getSelectedOwnersProperty();

    expect($selectedOwners)->toHaveCount(2);
    expect($selectedOwners->pluck('id')->toArray())->toEqual($users->pluck('id')->toArray());
});

test('can add and remove users in edit form', function () {
    $users = User::factory()->count(2)->create();

    $component = livewire(ProjectList::class);

    // Test adding to edit form
    $component->call('editForm.addOwner', $users[0]->id);
    expect($component->editForm->selectedOwners)->toContain($users[0]->id);

    // Test removing from edit form
    $component->call('editForm.removeOwner', $users[0]->id);
    expect($component->editForm->selectedOwners)->not->toContain($users[0]->id);
});
