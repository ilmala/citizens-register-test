<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Family;

use App\Http\Controllers\Controller;
use App\Http\Resources\FamilyResource;
use App\Models\Family;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $families = Family::query()->with(['responsible', 'members'])->paginate(25);

        return FamilyResource::collection(
            resource: $families,
        );
    }
}
