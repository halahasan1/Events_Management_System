<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class LocationUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage locations');
    }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|required|string|max:255',
            'address'     => 'nullable|string|max:500',
            'city'        => 'nullable|string|max:255',
            'country'     => 'nullable|string|max:255',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Location name is required.',
            'name.string'       => 'Location name must be a valid string.',
            'name.max'          => 'Location name cannot exceed 255 characters.',

            'address.string'    => 'Address must be a valid string.',
            'address.max'       => 'Address cannot exceed 500 characters.',

            'city.string'       => 'City must be a valid string.',
            'city.max'          => 'City cannot exceed 255 characters.',

            'country.string'    => 'Country must be a valid string.',
            'country.max'       => 'Country cannot exceed 255 characters.',

            'images.*.image'    => 'Each file must be a valid image.',
            'images.*.mimes'    => 'Allowed image types: jpeg, png, jpg, gif.',
            'images.*.max'      => 'Each image may not be greater than 2MB.',
        ];
    }
}
