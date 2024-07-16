<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FamilyMemberController extends Controller
{
    public function __invoke(Request $request, Family $family): Response
    {
        $request->validate([
            'person_id' => ['required', 'exists:people,id'],
            'role' => ['required', Rule::enum(Role::class)],
        ]);

        $person = Person::query()->findOrFail(
            id: $request->string('person_id')->toString(),
        );

        if($family->hasMember($person)){
            throw ValidationException::withMessages([
                'person_id' => ['Person is already member of this family.'],
            ]);
        }

        $role = $request->enum('role', Role::class);

        $family->members()->attach($person, [
            'role' => $role->value,
        ]);

        return response()->noContent();
    }
}
