<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Person $resource
 */
class PersonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'name' => $this->resource->first_name . ' ' . $this->resource->last_name,
            'tax_id' => $this->resource->tax_id,
            'families' => FamilyResource::collection($this->whenLoaded('families')),
        ];
    }
}
