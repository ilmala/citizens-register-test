<?php

declare(strict_types=1);

use App\Models\Family;
use App\Models\Member;

test('a member can be promoted to responsible for a family', function (): void {
    $member = Member::factory()->create();
    $family = Family::factory()->create();

    $response = $this->postJson('/api/members/' . $member->id . '/promote', [
        'family_id' => $family->id,
    ]);

    $response->assertStatus(200);
});
