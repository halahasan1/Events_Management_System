<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'address'    => $this->address,
            'city'       => $this->city,
            'country'    => $this->country,
            'images'     => $this->images->map(fn($image) => $image->url),
            'images_count' => $this->images_count ?? $this->images->count(),
        ];
    }
}
