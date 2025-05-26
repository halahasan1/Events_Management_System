<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the reservation model into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'user'       => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
                'email'=> $this->user->email,
            ],
            'event'      => [
                'id'         => $this->event->id,
                'title'      => $this->event->title,
                'start_time' => $this->event->start_time,
                'end_time'   => $this->event->end_time,
            ],
            'status'         => $this->status,
            'reserved_at'    => $this->created_at->toDateTimeString(),
            'created_at'     => $this->created_at->toDateTimeString(),
            'updated_at'     => $this->updated_at->toDateTimeString(),
        ];
    }
}
