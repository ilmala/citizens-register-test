<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\AddMemberToFamily;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Family;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Throwable;

class FamilyMemberController extends Controller
{
    public function __invoke(Request $request, Family $family, AddMemberToFamily $addMemberToFamily): Response|JsonResponse
    {
        $request->validate([
            'person_id' => ['required', 'exists:people,id'],
            'role' => ['required', Rule::enum(Role::class)],
        ]);

        try {
            $addMemberToFamily->handle(
                request: $request,
                family: $family,
            );
        } catch (Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], $exception->getCode() ?? Response::HTTP_BAD_REQUEST);
        }

        return response()->noContent();
    }
}
