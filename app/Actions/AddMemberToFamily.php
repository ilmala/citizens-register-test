<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\Role;
use App\Models\Family;
use App\Models\Person;
use Exception;
use Illuminate\Http\Request;

final class AddMemberToFamily
{
    public function handle(Request $request, Family $family): void
    {
        $person = Person::query()->findOrFail(
            id: $request->string('person_id')->toString(),
        );

        if($family->hasMember($person)) {
            throw new Exception(
                message: "Il cittadino è gia un membro della famiglia.",
                code: 404,
            );
        }

        $role = $request->enum('role', Role::class);

        $family->members()->attach($person, [
            'role' => $role->value,
        ]);
    }
}
