<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Family $resource
 */
class FamilyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->name,
            'responsible' => PersonResource::make($this->whenLoaded('responsible')),
            'members' => PersonResource::collection($this->whenLoaded('members')),
        ];
    }
}
