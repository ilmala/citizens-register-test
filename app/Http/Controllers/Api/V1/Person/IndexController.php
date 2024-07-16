<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $people = Person::query()->with('families')->paginate(25);

        return PersonResource::collection(
            resource: $people,
        );
    }
}
