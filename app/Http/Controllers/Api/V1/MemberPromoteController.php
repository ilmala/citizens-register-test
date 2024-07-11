<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberPromoteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Member $member)
    {
        $request->validate([
            'family_id' => ['required', 'exists:families,id'],
        ]);

        $family = Family::find($request->string('family_id')->toString());

        $family->responsible()->associate($member);
        $family->save();

        return response()->noContent();
    }
}
