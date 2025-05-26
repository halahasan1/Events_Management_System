<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'title'                     => $this->title,
            'description'              => $this->description,
            'start_time'                => $this->start_time,
            'end_time'                  => $this->end_time,
            'event_type'                => $this->type?->name,
            'location'                  => $this->location?->name,
            'image_url'                 => $this->images->first()?->url,
            'images'                    => $this->images->map(fn($img) => $img->url),
            'creator'                   => $this->creator?->name,
            'confirmed_reservations'    => $this->confirmedReservationsCount(),
            'reservations_count'        => $this->reservations_count ?? 0,
        ];

    }
}
