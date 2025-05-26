<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to create a reservation.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create reservations');
    }

    /**
     * Get the validation rules for storing a reservation.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'event_id'    => 'required|exists:events,id',
            'status'      => 'sometimes|in:pending,confirmed,cancelled',
            'reserved_at' => 'sometimes|date',
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'event_id.required' => 'The event field is required.',
            'event_id.exists'   => 'The selected event does not exist.',
            'status.in'         => 'The status must be one of: pending, confirmed, cancelled.',
            'reserved_at.date'  => 'The reserved at must be a valid date.',
        ];
    }
}
