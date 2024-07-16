<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Family;
use App\Models\Person;

test('spostamento di un cittadino da una famiglia a un\'altra', function (): void {
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

test("Il cittadino responsabile non può lasciare la famiglia", function (): void {
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

// todo: move
test("I cittadini figli non possono lasciare la famiglia se sono gli unici membri di quella famiglia e non appartengono già ad altre famiglie", function (): void {
    $person = Person::factory()->create();
    $familyA = Family::factory()->create();
    $familyB = Family::factory()->create();

    $familyA->members()->attach($person, ['role' => Role::Child]);

    $response = $this->postJson("/api/v1/person/{$person->id}/move", [
        'from_family_id' => $familyA->id,
        'to_family_id' => $familyB->id,
        'to_role' => Role::Child,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['from_family_id']);

    expect($person->isMemberOf($familyA))->toBeTrue();
});

test("I cittadini figli possono lasciare la famiglia se non sono gli unici membri di quella famiglia", function (): void {
    $personParent = Person::factory()->create();
    $personChild = Person::factory()->create();
    $familyA = Family::factory()->create();
    $familyB = Family::factory()->create();

    $familyA->members()->attach($personParent, ['role' => Role::Parent]);
    $familyA->members()->attach($personChild, ['role' => Role::Child]);

    $response = $this->postJson("/api/v1/person/{$personChild->id}/move", [
        'from_family_id' => $familyA->id,
        'to_family_id' => $familyB->id,
        'to_role' => Role::Child,
    ]);

    $response->assertStatus(204);

    expect($personChild->isMemberOf($familyB))->toBeTrue();
});
