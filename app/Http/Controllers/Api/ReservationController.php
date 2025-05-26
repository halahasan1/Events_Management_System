<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReservationResource;
use App\Http\Requests\Reservation\ReservationStoreRequest;

class ReservationController extends Controller
{
    protected ReservationService $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * List all reservations (optionally filtered)
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Check permission with Spatie
        if (!auth()->user()->can('view reservations')) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $filters = $request->only(['event_id', 'user_id']);
        $reservations = $this->reservationService->list($filters);
        return $this->successResponse(ReservationResource::collection($reservations));
    }

    /**
     * Store a new reservation
     *
     * @param ReservationStoreRequest $request
     * @return JsonResponse
     */
    public function store(ReservationStoreRequest $request): JsonResponse
    {

        $data = [
            'user_id' => Auth::id(),
            'event_id' => $request->validated('event_id'),
        ];

        $reservation = $this->reservationService->create($data);

        if ($reservation) {
            return $this->successResponse(new ReservationResource($reservation), 'Reservation created successfully');
        }

        return $this->errorResponse('Failed to create reservation', 500);
    }

    /**
     * Update an existing reservation
     *
     * @param ReservationStoreRequest $request
     * @param Reservation $reservation
     * @return JsonResponse
     */
    public function update(ReservationStoreRequest $request, Reservation $reservation): JsonResponse
    {

        $data = $request->validated();

        $updated = $this->reservationService->update($reservation, $data);

        if ($updated) {
            return $this->successResponse(new ReservationResource($updated), 'Reservation updated successfully');
        }

        return $this->errorResponse('Failed to update reservation', 500);
    }

    /**
     * Delete a reservation
     *
     * @param Reservation $reservation
     * @return JsonResponse
     */
    public function destroy(Reservation $reservation): JsonResponse
    {
        if (!auth()->user()->can('delete reservations')) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $this->reservationService->delete($reservation);

        return $this->successResponse(null, 'Reservation deleted successfully');


    }
}
