<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class ReservationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to update the reservation.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit reservations');
    }

    /**
     * Get the validation rules for updating a reservation.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,confirmed,cancelled',
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
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be one of: pending, confirmed, cancelled.',
            'reserved_at.date' => 'The reserved at must be a valid date.',
        ];
    }
}
