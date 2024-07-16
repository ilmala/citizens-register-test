<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PersonResponsibleController extends Controller
{
    public function __invoke(Request $request, Person $person): Response
    {
        $request->validate([
            'family_id' => ['required', 'exists:families,id'],
        ]);

        $familyMember = $person->families()->find(
            id: $request->string('family_id')->toString(),
        );

        // Check if person is part of the family
        if( ! $familyMember) {
            throw ValidationException::withMessages([
                'family_id' => ['Person is not a family member.'],
            ]);
        }

        // check if person role is parent or tutor
        $familyMemberRole = Role::tryFrom($familyMember->pivot->role);
        if( ! in_array($familyMemberRole, [Role::Parent,Role::Guardian])) {
            throw ValidationException::withMessages([
                'family_id' => ['Person is not allowed to became a family responsible.'],
            ]);
        }

        $family = Family::query()->findOrFail(
            id: $request->string('family_id')->toString(),
        );

        $family->responsible()->associate($person);
        $family->save();

        return response()->noContent();
    }
}
