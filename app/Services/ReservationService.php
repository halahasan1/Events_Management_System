<?php

namespace App\Services;

use Exception;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationService
{
    /**
     * Create a new reservation.
     *
     * @param array $data
     * @return Reservation
     *
     * @throws \RuntimeException
     */
    public function create(array $data): Reservation
    {
        try {
            $event = Event::findOrFail($data['event_id']);

            if ($event->reservations()->where('status', 'confirmed')->count() >= $event->max_capacity) {
                throw new \RuntimeException('Event is fully booked');
            }

            return DB::transaction(function () use ($data) {
                $reservation = Reservation::create($data);

                if ($reservation->wasRecentlyCreated) {
                    Log::info('Reservation created successfully', ['reservation_id' => $reservation->id]);
                }

                return $reservation->refresh();
            });

        } catch (Exception $e) {
            Log::error('Failed to create reservation', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Could not create reservation');
        }
    }


    /**
     * Update an existing reservation
     *
     * @param Reservation $reservation
     * @param array $data
     * @return Reservation
     *
     * @throws \RuntimeException
     */
    public function update(Reservation $reservation, array $data): Reservation
    {
        try {
            return DB::transaction(function () use ($reservation, $data) {
                $reservation->fill($data);

                if ($reservation->isDirty()) {
                    $reservation->save();
                    Log::info('Reservation updated', ['reservation_id' => $reservation->id]);
                }

                return $reservation->refresh();
            });
        } catch (Exception $e) {
            Log::error('Failed to update reservation', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Could not update reservation.');
        }
    }

    /**
     * Delete a reservation
     *
     * @param Reservation $reservation
     * @return void
     *
     * @throws \RuntimeException
     */
    public function delete(Reservation $reservation): void
    {
        try {
            DB::transaction(function () use ($reservation) {
                $reservation->delete();
                Log::info('Reservation deleted', ['reservation_id' => $reservation->id]);
            });
        } catch (Exception $e) {
            Log::error('Failed to delete reservation', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Could not delete reservation.');
        }
    }

    /**
     * Get a list of reservations with optional filtering and eager loading
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list(array $filters = [])
    {
        $query = Reservation::query();

        $query->with(['user', 'event']);

        //  filter by event_id
        if (!empty($filters['event_id'])) {
            $query->where('event_id', $filters['event_id']);
        }

        // filter by user_id
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->get();
    }
}
