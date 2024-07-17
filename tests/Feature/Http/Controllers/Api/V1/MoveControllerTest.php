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

    $response = $this->postJson("/api/v1/move", [
        'person_id' => $person->id,
        'from_family_id' => $familyA->id,
        'to_family_id' => $familyB->id,
        'role' => Role::Parent,
    ]);

    $response->assertStatus(204);

    expect($person->isMemberOf($familyA->fresh()))->toBeFalse();
    expect($person->isMemberOf($familyB->fresh()))->toBeTrue();
});

test("Il cittadino responsabile non può spostarsi  dalla famiglia", function (): void {
    $this->withoutExceptionHandling();

    $person = Person::factory()->create();
    $familyA = Family::factory()->create();
    $familyB = Family::factory()->create();

    $familyA->members()->attach($person, ['role' => Role::Parent]);
    $familyA->responsible()->associate($person);
    $familyA->save();

    expect($person->isMemberOf($familyA))->toBeTrue();
    expect($person->isMemberOf($familyB))->toBeFalse();

    $response = $this->postJson("/api/v1/move", [
        'person_id' => $person->id,
        'from_family_id' => $familyA->id,
        'to_family_id' => $familyB->id,
        'role' => Role::Parent,
    ]);

    $response->assertStatus(404);

    expect($person->isMemberOf($familyA))->toBeTrue();
    expect($person->isMemberOf($familyB))->toBeFalse();
})->throws(Exception::class, "Non si può spostare il responsabile di una famiglia");

todo("Un cittadino si puo spostare solo se appartiene alla famiglia di provenienza");
