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

class MoveController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $request->validate([
            'person_id' => ['required', 'exists:people,id'],
            'from_family_id' => ['required', 'exists:families,id'],
            'to_family_id' => ['required', 'exists:families,id'],
            'role' => ['required', Rule::enum(Role::class)],
        ]);

        $person = Person::query()->findOrFail(
            id: $request->string('person_id')->toString(),
        );

        $familyFrom = Family::query()->findOrFail(
            id: $request->string('from_family_id')->toString(),
        );

        // Check if person is member of the family from
        if( ! $familyFrom->hasMember($person)) {
            throw ValidationException::withMessages([
                'from_family_id' => ['Person is not a family member.'],
            ]);
        }

        $familyTo = Family::query()->findOrFail(
            id: $request->string('to_family_id')->toString(),
        );

        $toRole = $request->enum('role', Role::class);

        // Check if person is responsible for the family from
        if($familyFrom->isLedBy($person)) {
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
