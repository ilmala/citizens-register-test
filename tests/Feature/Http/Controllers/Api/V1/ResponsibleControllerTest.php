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

    $response->assertStatus(404);
    $response->assertJsonFragment([
        'status' => 'error',
        'message' => "Il cittadino non è membro della famiglia indicata.",
    ]);

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

    $response->assertStatus(404);
    $response->assertJsonFragment([
        'status' => 'error',
        'message' => "Un cittadino con ruolo child non puo diventare responsabile.",
    ]);

    $family = $family->fresh();
    expect($family->responsible)->toBeNull();
});

test('Un cittadino responsabile di una famiglia non puo essere promosso a responsabile', function (): void {
    $person = Person::factory()->create();
    $family = Family::factory()->create();
    $family->members()->attach($person, ['role' => Role::Parent]);

    $family->responsible()->associate($person);
    $family->save();

    $response = $this->postJson("/api/v1/responsible", [
        'person_id' => $person->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(404);
    $response->assertJsonFragment([
        'status' => 'error',
        'message' => "Questo cittadino è gia responsabile di questa famiglia.",
    ]);
});

test("Il genitore può essere responsabile di famiglie con massimo 6 membri", function (): void {
    $persons = Person::factory()->count(6)->create();
    $family = Family::factory()->create();
    $family->members()->attach([
        $persons[0]->id => ['role' => Role::Parent],
        $persons[1]->id => ['role' => Role::Parent],
        $persons[2]->id => ['role' => Role::Child],
        $persons[3]->id => ['role' => Role::Child],
        $persons[4]->id => ['role' => Role::Child],
        $persons[5]->id => ['role' => Role::Child],
    ]);

    $response = $this->postJson("/api/v1/responsible", [
        'person_id' => $persons[0]->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(204);

    $family = $family->fresh();
    expect($family->responsible->id)->toEqual($persons[0]->id);
});

test("Il genitore non può essere responsabile di famiglie con più 6 membri", function (): void {
    $persons = Person::factory()->count(7)->create();
    $family = Family::factory()->create();
    $family->members()->attach([
        $persons[0]->id => ['role' => Role::Parent],
        $persons[1]->id => ['role' => Role::Parent],
        $persons[2]->id => ['role' => Role::Child],
        $persons[3]->id => ['role' => Role::Child],
        $persons[4]->id => ['role' => Role::Child],
        $persons[5]->id => ['role' => Role::Child],
        $persons[6]->id => ['role' => Role::Child],
    ]);

    $response = $this->postJson("/api/v1/responsible", [
        'person_id' => $persons[0]->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(404);
    $response->assertJsonFragment([
        'status' => 'error',
        'message' => "Un cittadino parente non puo essere responsabile di una famiglia con + di 6 membri.",
    ]);
});

test("Il genitore può essere responsabile per non più di 3 famiglie", function (): void {
    $parentPerson = Person::factory()->create();
    $families = Family::factory()->count(3)->create();
    foreach ($families as $family) {
        $otherPersons = Person::factory()->count(2)->create();
        $family->members()->attach([
            $parentPerson->id => ['role' => Role::Parent],
            $otherPersons[0]->id => ['role' => Role::Parent],
            $otherPersons[1]->id => ['role' => Role::Child],
        ]);

        $family->responsible()->associate($parentPerson);
        $family->save();
    }
    $newFamily = Family::factory()->create();
    $newFamilyPersons = Person::factory()->count(2)->create();
    $newFamily->members()->attach([
        $parentPerson->id => ['role' => Role::Parent],
        $newFamilyPersons[0]->id => ['role' => Role::Parent],
        $newFamilyPersons[1]->id => ['role' => Role::Child],
    ]);

    $response = $this->postJson("/api/v1/responsible", [
        'person_id' => $parentPerson->id,
        'family_id' => $newFamily->id,
    ]);

    $response->assertStatus(404);
    $response->assertJsonFragment([
        'status' => 'error',
        'message' => "Un cittadino parente non puo essere responsabile di più di 3 famiglie.",
    ]);
});

test("Il tutore può essere responsabile di famiglie con più 6 membri", function (): void {
    $persons = Person::factory()->count(7)->create();
    $family = Family::factory()->create();
    $family->members()->attach([
        $persons[0]->id => ['role' => Role::Tutor],
        $persons[1]->id => ['role' => Role::Tutor],
        $persons[2]->id => ['role' => Role::Child],
        $persons[3]->id => ['role' => Role::Child],
        $persons[4]->id => ['role' => Role::Child],
        $persons[5]->id => ['role' => Role::Child],
        $persons[6]->id => ['role' => Role::Child],
    ]);

    $response = $this->postJson("/api/v1/responsible", [
        'person_id' => $persons[0]->id,
        'family_id' => $family->id,
    ]);

    $response->assertStatus(204);
    $family = $family->fresh();
    expect($family->responsible->id)->toEqual($persons[0]->id);
});
