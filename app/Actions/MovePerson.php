<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\Role;
use App\Exceptions\InvalidFamilyMemberException;
use App\Models\Family;
use App\Models\Person;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class MovePerson
{
    public function handle(Request $request): void
    {
        $person = Person::query()->findOrFail(
            id: $request->string('person_id')->toString(),
        );

        $familyFrom = Family::query()->findOrFail(
            id: $request->string('from_family_id')->toString(),
        );

        // Check if person is member of the family from
        if( ! $familyFrom->hasMember($person)) {
            throw new InvalidFamilyMemberException(
                message: "Il cittadino non è membro della famiglia indicata.",
                code: 404,
            );
        }

        $familyTo = Family::query()->findOrFail(
            id: $request->string('to_family_id')->toString(),
        );

        $toRole = $request->enum('role', Role::class);

        // Check if person is responsible for the family from
        if($familyFrom->isLedBy($person)) {
            throw new Exception(
                message: "Non si può spostare il responsabile di una famiglia",
                code: 404,
            );
        }

        DB::transaction(function () use ($familyFrom, $familyTo, $toRole, $person): void {
            // remove from family
            $familyFrom->members()->detach($person);

            // add to family
            $familyTo->members()->attach($person, [
                'role' => $toRole->value,
            ]);
        });
    }
}
