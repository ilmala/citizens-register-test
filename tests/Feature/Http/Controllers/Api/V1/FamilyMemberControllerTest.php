<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Family;
use App\Models\Person;

test("Un cittadino puo essere associato ad un'altra famiglia senza lasciare la corrente", function (): void {
    $person = Person::factory()->create();
    $familyA = Family::factory()->create();
    $familyB = Family::factory()->create();
    $familyA->members()->attach($person, ['role' => Role::Parent]);

    $response = $this->postJson("/api/v1/families/{$familyB->id}/member", [
        'person_id' => $person->id,
        'role' => Role::Parent,
    ]);

    $response->assertStatus(204);

    expect($person->isMemberOf($familyA))->toBeTrue();
    expect($person->isMemberOf($familyB))->toBeTrue();
});

test("Un cittadino puo essere associato ad una famiglia a cui gia appartiene", function (): void {
    $person = Person::factory()->create();
    $family = Family::factory()->create();
    $family->members()->attach($person, ['role' => Role::Parent]);

    $response = $this->postJson("/api/v1/families/{$family->id}/member", [
        'person_id' => $person->id,
        'role' => Role::Parent,
    ]);

    $response->assertStatus(404);
    $response->assertJsonFragment([
        'status' => 'error',
        'message' => "Il cittadino è gia un membro della famiglia.",
    ]);
});
