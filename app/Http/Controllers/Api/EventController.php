<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventStoreRequest;
use App\Http\Requests\Event\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(protected EventService $eventService) {}

    /**
     * Display a paginated list of events.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['has_reservations', 'event_type_id', 'location_id']);

        $events = $this->eventService->list($filters);

        return $this->successResponse(EventResource::collection($events));
    }


    /**
     * Store a newly created event.
     *
     * @param EventStoreRequest $request
     * @return JsonResponse
     */
    public function store(EventStoreRequest $request): JsonResponse
    {
        $event = $this->eventService->create($request->validated());
        return $this->successResponse(new EventResource($event), 'Event created successfully.', 201);
    }

    /**
     * Display the specified event with details.
     *
     * @param Event $event
     * @return JsonResponse
     */
    public function show(Event $event): JsonResponse
    {
        $event->load(['type', 'location', 'images', 'creator']);
        return $this->successResponse(new EventResource($event));
    }

    /**
     * Update the specified event.
     *
     * @param EventUpdateRequest $request
     * @param Event $event
     * @return JsonResponse
     */
    public function update(EventUpdateRequest $request, Event $event): JsonResponse
    {
        $updatedEvent = $this->eventService->update($event, $request->validated());
        return $this->successResponse(new EventResource($updatedEvent), 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     *
     * @param Event $event
     * @return JsonResponse
     */
    public function destroy(Event $event): JsonResponse
    {
        if (!auth()->user()->can('delete events')) {
            return $this->errorResponse('Unauthorized', 403);
        }
        $this->eventService->delete($event);
        return $this->successResponse(null, 'Event deleted successfully');
    }
}
