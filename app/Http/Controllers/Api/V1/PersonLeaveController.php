<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PersonLeaveController extends Controller
{
    /**
     * @param Request $request
     * @param Person $person
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request, Person $person): Response
    {
        $request->validate([
            'family_id' => ['required', 'exists:families,id'],
        ]);

        $family = Family::query()->findOrFail(
            id: $request->string('family_id')->toString(),
        );

        // Check if person is responsible for the family from
        if($family->isLedBy($person)) {
            throw ValidationException::withMessages([
                'family_id' => ['Family responsible cannot leave the family.'],
            ]);
        }

        // Check if person is member of the family from
        if( ! $family->hasMember($person)) {
            throw ValidationException::withMessages([
                'family_id' => ['Person is not a family member.'],
            ]);
        }

        $member = $family->members()->find($person->id);

        $familyMemberRole = Role::tryFrom($member->pivot->role);
        // Check if member is a child and is the only member of family
        if($familyMemberRole === Role::Child && $family->members()->count() === 1 && $person->families()->count() === 1) {
            throw ValidationException::withMessages([
                'family_id' => ['Person is a child and is the only member in the family.'],
            ]);
        }

        $family->members()->detach($person);

        return response()->noContent();
    }
}
