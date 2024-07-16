<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Family;
use App\Models\Person;

test('a person member of a family can be promoted to responsible', function (): void {
    $person = Person::factory()->create();
    $family = Family::factory()->create();
    $family->members()->attach($person, ['role' => Role::Parent]);

    expect($family->responsible)->toBeNull();

    $response = $this->postJson("/api/v1/person/{$person->id}/responsible", [
        'family_id' => $family->id,
    ]);

    $response->assertStatus(204);

    $family = $family->fresh();
    expect($family->responsible)
        ->toBeInstanceOf(Person::class);
});

test('a person not a member of a family can not be promoted to responsible', function (): void {
    $person = Person::factory()->create();
    $family = Family::factory()->create();
    //$family->members()->attach($person, ['role' => Role::Parent]);

    expect($family->responsible)->toBeNull();

    $response = $this->postJson("/api/v1/person/{$person->id}/responsible", [
        'family_id' => $family->id,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['family_id']);

    $family = $family->fresh();
    expect($family->responsible)->toBeNull();
});

test('a person member of a family can replace a current responsible when promoted', function (): void {
    $personA = Person::factory()->create();
    $personB = Person::factory()->create();
    $family = Family::factory()->create();
    $family->members()->attach($personA, ['role' => Role::Parent]);
    $family->members()->attach($personB, ['role' => Role::Parent]);
    $family->responsible()->associate($personA);
    $family->save();

    expect($family->responsible->is($personA))->toBeTrue();

    $response = $this->postJson("/api/v1/person/{$personB->id}/responsible", [
        'family_id' => $family->id,
    ]);

    $response->assertStatus(204);

    $family = $family->fresh();
    expect($family->responsible->is($personB))->toBeTrue();

});

test("Solo i cittadini genitori o tutori possono diventare responsabili", function (): void {
    $person = Person::factory()->create();
    $family = Family::factory()->create();
    $family->members()->attach($person, ['role' => Role::Child]);

    expect($family->responsible)->toBeNull();

    $response = $this->postJson("/api/v1/person/{$person->id}/responsible", [
        'family_id' => $family->id,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['family_id']);

    $family = $family->fresh();
    expect($family->responsible)->toBeNull();
});
