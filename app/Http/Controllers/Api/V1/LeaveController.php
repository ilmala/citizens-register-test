<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\LeaveFamily;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class LeaveController extends Controller
{
    public function __invoke(Request $request, LeaveFamily $leaveFamily): Response|JsonResponse
    {
        $request->validate([
            'person_id' => ['required', 'exists:people,id'],
            'family_id' => ['required', 'exists:families,id'],
        ]);

        try {
            $leaveFamily->handle(
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
