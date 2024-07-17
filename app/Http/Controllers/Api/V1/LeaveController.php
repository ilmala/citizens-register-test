<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\Role;
use App\Exceptions\InvalidFamilyMemberException;
use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Person;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LeaveController extends Controller
{
    /**
     * @param Request $request
     * @param Person $person
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $request->validate([
            'person_id' => ['required', 'exists:people,id'],
            'family_id' => ['required', 'exists:families,id'],
        ]);

        $person = Person::query()->findOrFail(
            id: $request->string('person_id')->toString(),
        );

        $family = Family::query()->findOrFail(
            id: $request->string('family_id')->toString(),
        );

        // Check if person is responsible for the family from
        if($family->isLedBy($person)) {
            throw new Exception(
                message: "Il responsabile non puo lasciare una famiglia.",
                code: 404,
            );
        }

        // Check if person is member of the family from
        if( ! $family->hasMember($person)) {
            throw new InvalidFamilyMemberException(
                message: "Il cittadino non è membro della famiglia indicata.",
                code: 404,
            );
        }

        $member = $family->members()->find($person->id);

        $familyMemberRole = Role::tryFrom($member->pivot->role);
        // Check if member is a child and is the only member of family
        if(Role::Child === $familyMemberRole && 1 === $family->members()->count() && 1 === $person->families()->count()) {
            throw new Exception(
                message: "Il cittadino filgio è il solo membro della famiglia.",
                code: 404,
            );
        }

        $family->members()->detach($person);

        return response()->noContent();
    }
}
