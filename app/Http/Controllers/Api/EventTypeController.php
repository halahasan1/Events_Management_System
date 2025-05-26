<?php

namespace App\Http\Controllers\Api;

use App\Models\EventType;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventType\EventTypeStoreRequest;
use App\Http\Requests\EventType\EventTypeUpdateRequest;

class EventTypeController extends Controller
{
    /**
     * Display a listing of event types.
     */
    public function index(): JsonResponse
    {
        $eventTypes = EventType::all();

        return $this->successResponse($eventTypes, 'Event types fetched successfully');
    }

    /**
     * Store a newly created event type in storage.
     */
    public function store(EventTypeStoreRequest $request): JsonResponse
    {
        $eventType = EventType::create([
            'name' => $request->input('name'),
        ]);

        return $this->successResponse($eventType, 'Event type created successfully!', 201);
    }

    /**
     * Display the specified event type.
     */
    public function show(EventType $eventType): JsonResponse
    {
        return $this->successResponse($eventType, 'Event type retrieved successfully');
    }

    /**
     * Update the specified event type in storage.
     */
    public function update(EventTypeUpdateRequest $request, EventType $eventType): JsonResponse
    {
        $eventType->update([
            'name' => $request->input('name'),
        ]);

        return $this->successResponse($eventType, 'Event type updated successfully');
    }

    /**
     * Remove the specified event type from storage.
     */
    public function destroy(EventType $eventType): JsonResponse
    {
        if (!auth()->user()->can('manage event_types')) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $eventType->delete();

        return $this->successResponse(null, 'Event type deleted successfullyy');
    }
}
