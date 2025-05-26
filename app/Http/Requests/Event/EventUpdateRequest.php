<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit events');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'start_time'     => 'required|date|after:now',
            'end_time'       => 'required|date|after:start_time',
            'event_type_id'  => 'required|exists:event_types,id',
            'location_id'    => 'required|exists:locations,id',
            'created_by'     => 'sometimes|exists:users,id',
            'images'         => 'nullable|array',
            'images.*'       => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'max_capacity'   => 'required|integer|min:1',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required'          => 'The event title is required.',
            'title.string'            => 'The title must be a valid string.',
            'title.max'               => 'The title may not be more than 255 characters.',

            'description.string'      => 'The description must be a valid string.',

            'start_time.required'     => 'The start time is required.',
            'start_time.date'         => 'The start time must be a valid date.',
            'start_time.after'        => 'The start time must be in the future.',

            'end_time.required'       => 'The end time is required.',
            'end_time.date'           => 'The end time must be a valid date.',
            'end_time.after'          => 'The end time must be after the start time.',

            'event_type_id.required'  => 'The event type is required.',
            'event_type_id.exists'    => 'The selected event type is invalid.',

            'location_id.required'    => 'The location is required.',
            'location_id.exists'      => 'The selected location is invalid.',

            'images.array'            => 'Images must be an array.',
            'images.*.image'          => 'Each file must be a valid image.',
            'images.*.mimes'          => 'Each image must be a jpeg, png, jpg, gif, or webp file.',
            'images.*.max'            => 'Each image must not exceed 2MB.',
        ];
    }
}
