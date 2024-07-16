<?php

declare(strict_types=1);

use App\Models\Person;

test('Elenco dei cittadini inseriti', function (): void {
    Person::factory()->count(10)->create();

    $response = $this->getJson('/api/v1/members');

    $response->assertStatus(200);

    expect($response->json('data'))->toHaveCount(10);
});
