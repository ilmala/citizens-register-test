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
