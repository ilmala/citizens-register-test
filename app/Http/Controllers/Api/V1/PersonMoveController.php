<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PersonMoveController extends Controller
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
            'from_family_id' => ['required', 'exists:families,id'],
            'to_family_id' => ['required', 'exists:families,id'],
            'to_role' => ['required', Rule::enum(Role::class)],
        ]);

        $familyMember = $person->families()->find(
            id: $request->string('from_family_id')->toString(),
        );

        // Check if person is member of the family from
        if( ! $familyMember) {
            throw ValidationException::withMessages([
                'from_family_id' => ['Person is not a family member.'],
            ]);
        }

        $familyFrom = Family::query()->findOrFail(
            id: $request->string('from_family_id')->toString(),
        );

        $familyMemberRole = Role::tryFrom($familyMember->pivot->role);
        // Check if member is a child and is the only member of family
        if($familyMemberRole === Role::Child && $familyFrom->members()->count() === 1) {
            throw ValidationException::withMessages([
                'from_family_id' => ['Person is a child and is the only member in the family.'],
            ]);
        }

        $familyTo = Family::query()->findOrFail(
            id: $request->string('to_family_id')->toString(),
        );
        $toRole = $request->enum('to_role', Role::class);

        // Check if person is responsible for the family from
        if($familyFrom->responsible?->is($person)) {
            throw ValidationException::withMessages([
                'from_family_id' => ['You cannot move the family responsible.'],
            ]);
        }

        DB::transaction(function () use ($familyFrom, $familyTo, $toRole, $person): void {
            // remove from family
            $familyFrom->members()->detach($person);

            // add to family
            $familyTo->members()->attach($person, [
                'role' => $toRole->value,
            ]);
        });

        return response()->noContent();
    }
}