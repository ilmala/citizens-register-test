<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\Role;
use App\Exceptions\InvalidFamilyMemberException;
use App\Exceptions\InvalidMemberRoleException;
use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Person;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ResponsibleController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws InvalidFamilyMemberException
     * @throws InvalidMemberRoleException
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

        $familyMember = $person->families()->find(
            id: $request->string('family_id')->toString(),
        );

        if ( ! $familyMember) {
            throw new InvalidFamilyMemberException(
                message: "Il cittadino non è membro della famiglia indicata.",
                code: 404,
            );
        }

        $familyMemberRole = Role::tryFrom($familyMember->pivot->role);
        if( ! in_array($familyMemberRole, [Role::Parent,Role::Tutor])) {
            throw new InvalidMemberRoleException(
                message: "Un cittadino con ruolo {$familyMemberRole->value} non puo diventare responsabile.",
                code: 404,
            );
        }

        $family = Family::query()->findOrFail(
            id: $request->string('family_id')->toString(),
        );

        if(Role::Parent === $familyMemberRole && $person->families()->count() > 3) {
            throw new Exception(
                message: "Un cittadino parente non puo essere responsabile di più di 3 famiglie.",
                code: 404,
            );
        }

        if(Role::Parent === $familyMemberRole && $family->members()->count() > 6) {
            throw new Exception(
                message: "Un cittadino parente non puo essere responsabile di una famiglia con + di 6 membri.",
                code: 404,
            );
        }

        if($familyMember && $family->isLedBy($person)) {
            throw new Exception(
                message: "Questo cittadino è gia responsabile di questa famiglia.",
                code: 404,
            );
        }

        $family->responsible()->associate($person);
        $family->save();

        return response()->noContent();
    }
}
