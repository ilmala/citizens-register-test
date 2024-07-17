<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Family;
use App\Models\Person;

test('Un cittadino puo lasciare una famiglia', function (): void {
    $person = Person::factory()->create();
    $family = Family::factory()->create();

    $family->members()->attach($person, ['role' => Role::Parent]);

    $response = $this->postJson("/api/v1/leave", [
        'person_id' => $person->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(204);
    expect($family->members)->toHaveCount(0);
});

test("Il cittadino responsabile non può lasciare la famiglia", function (): void {
    $person = Person::factory()->create();
    $family = Family::factory()->create();

    $family->members()->attach($person, ['role' => Role::Parent]);
    $family->responsible()->associate($person);
    $family->save();

    expect($person->isMemberOf($family))->toBeTrue();

    $response = $this->postJson("/api/v1/leave", [
        'person_id' => $person->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(404);
    $response->assertJsonFragment([
        'status' => 'error',
        'message' => "Il responsabile non puo lasciare una famiglia.",
    ]);

    expect($person->isMemberOf($family))->toBeTrue();
});

test("I figli non possono lasciare la famiglia se sono gli unici membri e non appartengono già ad altre famiglie", function (): void {
    $person = Person::factory()->create();
    $family = Family::factory()->create();

    $family->members()->attach($person, ['role' => Role::Child]);

    $response = $this->postJson("/api/v1/leave", [
        'person_id' => $person->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(404);
    $response->assertJsonFragment([
        'status' => 'error',
        'message' => "Il cittadino filgio è il solo membro della famiglia.",
    ]);

    expect($person->isMemberOf($family))->toBeTrue();
});

test("I figli possono lasciare la famiglia se non sono gli unici membri", function (): void {
    $parent = Person::factory()->create();
    $child = Person::factory()->create();
    $family = Family::factory()->create();

    $family->members()->attach($parent, ['role' => Role::Parent]);
    $family->members()->attach($child, ['role' => Role::Child]);

    $response = $this->postJson("/api/v1/leave", [
        'person_id' => $child->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(204);

    expect($child->isMemberOf($family))->toBeFalse();
});

test("I figli possono lasciare la famiglia se appartengono gia a un altra famiglia", function (): void {
    $person = Person::factory()->create();
    $family = Family::factory()->create();
    $otherFamily = Family::factory()->create();

    $family->members()->attach($person, ['role' => Role::Child]);
    $otherFamily->members()->attach($person, ['role' => Role::Child]);

    $response = $this->postJson("/api/v1/leave", [
        'person_id' => $person->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(204);
    expect($family->members)->toHaveCount(0);
});

todo("Un cittadino puo lasciare solo se appartiene alla famiglia di provenienza");
