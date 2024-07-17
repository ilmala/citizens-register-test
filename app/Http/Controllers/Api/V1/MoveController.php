<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\MovePerson;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Throwable;

class MoveController extends Controller
{
    public function __invoke(Request $request, MovePerson $movePerson): Response|JsonResponse
    {
        $request->validate([
            'person_id' => ['required', 'exists:people,id'],
            'from_family_id' => ['required', 'exists:families,id'],
            'to_family_id' => ['required', 'exists:families,id'],
            'role' => ['required', Rule::enum(Role::class)],
        ]);

        try {
            $movePerson->handle(
                request: $request,
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
