<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;

class EventService
{
    /**
     * List events with filters, eager loading, and reservation count
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function list(array $filters = [])
    {
        return Event::query()
            ->with(['type', 'location', 'images', 'creator'])
            ->withCount('reservations')
            ->when(isset($filters['event_type_id']), fn($q) => $q->ofType($filters['event_type_id']))
            ->when($filters['has_reservations'] ?? false, fn($q) => $q->whereHas('reservations'))
            ->upcoming()
            ->latest()
            ->paginate(10);
    }

    /**
     * Create a new event and attach image if present
     *
     * @param array $data
     * @return Event
     */
    public function create(array $data): Event
    {
        try {
            return DB::transaction(function () use ($data) {
                $imageFiles = $data['images'] ?? [];
                unset($data['images']);

                $event = Event::create($data);

                if (!empty($imageFiles)) {
                    $this->storeImage($event, $imageFiles);
                }

                if ($event->wasRecentlyCreated) {
                    Log::info('New event created', ['event_id' => $event->id]);
                }

                return $event->refresh();
            });
        } catch (Exception $e) {
            Log::error('Error creating event', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Could not create event.');
        }
    }

    /**
     * Update an existing event and manage image changes
     *
     * @param Event $event
     * @param array $data
     * @return Event
     */
    public function update(Event $event, array $data): Event
    {
        try {
            return DB::transaction(function () use ($event, $data) {
                $imageFiles = $data['images'] ?? [];
                unset($data['images']);

                $event->fill($data);

                if ($event->isDirty()) {
                    $event->save();
                    Log::info('Event updated', ['event_id' => $event->id]);
                }

                if (!empty($imageFiles)) {
                    $this->deleteImage($event);
                    $this->storeImage($event, $imageFiles);
                }

                return $event->refresh();
            });
        } catch (Exception $e) {
            Log::error('Error updating event', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Could not update event.');
        }
    }

    /**
     * Delete an event and its associated image
     *
     * @param Event $event
     * @return void
     */
    public function delete(Event $event): void
    {
        try {
            DB::transaction(function () use ($event) {
                $this->deleteImage($event);
                $event->delete();
            });
        } catch (Exception $e) {
            Log::error('Error deleting event', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Could not delete event.');
        }
    }

    /**
     * Store image and associate it with the event
     *
     * @param Event $event
     * @param UploadedFile $image
     * @return void
     */
    protected function storeImage(Event $event, array $images): void
    {
        foreach ($images as $image) {
            $path = $image->store('events', 'public');
            $event->images()->create(['path' => $path]);
        }
    }

    /**
     * Delete the event's image from storage and database
     *
     * @param Event $event
     * @return void
     */
    protected function deleteImage(Event $event): void
    {
        foreach ($event->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }
}
