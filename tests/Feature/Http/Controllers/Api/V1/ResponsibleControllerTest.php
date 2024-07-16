<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Family;
use App\Models\Person;

test('Un cittadino membro di una famiglia puo essere promosso a responsabile', function (): void {
    $person = Person::factory()->create();
    $family = Family::factory()->create();
    $family->members()->attach($person, ['role' => Role::Parent]);

    expect($family->responsible)->toBeNull();

    $response = $this->postJson("/api/v1/responsible", [
        'person_id' => $person->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(204);

    $family = $family->fresh();
    expect($family->responsible)
        ->toBeInstanceOf(Person::class);
});

test('Un cittadino non membro di una famiglia non puo essere promosso a responsabile', function (): void {
    $person = Person::factory()->create();
    $family = Family::factory()->create();

    expect($family->responsible)->toBeNull();

    $response = $this->postJson("/api/v1/responsible", [
        'person_id' => $person->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['family_id']);

    $family = $family->fresh();
    expect($family->responsible)->toBeNull();
});

test('Alla promozione a responsabile, il cittadino sostituisce un eventuale altro responsabile già definito', function (): void {
    $personA = Person::factory()->create();
    $personB = Person::factory()->create();
    $family = Family::factory()->create();
    $family->members()->attach($personA, ['role' => Role::Parent]);
    $family->members()->attach($personB, ['role' => Role::Parent]);
    $family->responsible()->associate($personA);
    $family->save();

    expect($family->responsible->is($personA))->toBeTrue();

    $response = $this->postJson("/api/v1/responsible", [
        'person_id' => $personB->id,
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

    $response = $this->postJson("/api/v1/responsible", [
        'person_id' => $person->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['family_id']);

    $family = $family->fresh();
    expect($family->responsible)->toBeNull();
});
