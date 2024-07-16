<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Family;
use App\Models\Person;

test("Un cittadino si puo spostare da una famiglia all'altra", function (): void {
    $person = Person::factory()->create();
    $familyA = Family::factory()->create();
    $familyB = Family::factory()->create();
    $familyA->members()->attach($person, ['role' => Role::Parent]);

    expect($person->isMemberOf($familyA))->toBeTrue();
    expect($person->isMemberOf($familyB))->toBeFalse();

    $response = $this->postJson("/api/v1/person/{$person->id}/move", [
        'from_family_id' => $familyA->id,
        'to_family_id' => $familyB->id,
        'to_role' => Role::Parent,
    ]);

    $response->assertStatus(204);

    expect($person->isMemberOf($familyA->fresh()))->toBeFalse();
    expect($person->isMemberOf($familyB->fresh()))->toBeTrue();
});

test("Il cittadino responsabile non può spostarsi  dalla famiglia", function (): void {
    $person = Person::factory()->create();
    $familyA = Family::factory()->create();
    $familyB = Family::factory()->create();

    $familyA->members()->attach($person, ['role' => Role::Parent]);
    $familyA->responsible()->associate($person);
    $familyA->save();

    expect($person->isMemberOf($familyA))->toBeTrue();
    expect($person->isMemberOf($familyB))->toBeFalse();

    $response = $this->postJson("/api/v1/person/{$person->id}/move", [
        'from_family_id' => $familyA->id,
        'to_family_id' => $familyB->id,
        'to_role' => Role::Parent,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['from_family_id']);

    expect($person->isMemberOf($familyA))->toBeTrue();
    expect($person->isMemberOf($familyB))->toBeFalse();
});
