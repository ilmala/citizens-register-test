<?php

declare(strict_types=1);

use App\Models\Family;

test('Elenco famiglie inserite', function (): void {
    Family::factory()->count(10)->create();

    $response = $this->getJson('/api/v1/families');

    $response->assertStatus(200);

    expect($response->json('data'))->toHaveCount(10);
});
