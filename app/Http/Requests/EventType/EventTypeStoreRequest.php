<?php

namespace App\Http\Requests\EventType;

use Illuminate\Foundation\Http\FormRequest;

class EventTypeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage event_types');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:event_types,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The event type name is required.',
            'name.string'   => 'The event type name must be a string.',
            'name.max'      => 'The event type name must not exceed 100 characters.',
            'name.unique'   => 'This event type already exists.',
        ];
    }
}
