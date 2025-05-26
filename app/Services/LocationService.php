<?php

namespace App\Services;

use App\Models\Location;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LocationService
{
    /**
     * Create a new location with optional images.
     *
     * @param array $data
     * @return Location
     * @throws \RuntimeException
     */
    public function create(array $data): Location
    {
        try {
            return DB::transaction(function () use ($data) {
                $images = $data['images'] ?? null;
                unset($data['images']);

                $location = Location::create($data);

                if ($images) {
                    $this->storeImages($location, $images);
                }

                if ($location->wasRecentlyCreated) {
                    Log::info('New location created', ['location_id' => $location->id]);
                }

                return $location->refresh();
            });
        } catch (Exception $e) {
            Log::error('Error creating location', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Could not create location.');
        }
    }

    /**
     * Update an existing location and manage images.
     *
     * @param Location $location
     * @param array $data
     * @return Location
     * @throws \RuntimeException
     */
    public function update(Location $location, array $data): Location
    {
        try {
            return DB::transaction(function () use ($location, $data) {
                $images = $data['images'] ?? null;
                unset($data['images']);

                $location->fill($data);

                if ($location->isDirty()) {
                    $location->save();
                    Log::info('Location updated', ['location_id' => $location->id]);
                }

                if ($images) {
                    $this->deleteImages($location);
                    $this->storeImages($location, $images);
                }

                return $location->refresh();
            });
        } catch (Exception $e) {
            Log::error('Error updating location', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Could not update location.');
        }
    }

    /**
     * Delete a location and all its images.
     *
     * @param Location $location
     * @return void
     * @throws \RuntimeException
     */
    public function delete(Location $location): void
    {

        try {
            if ($location->events()->exists()) {
                throw new \RuntimeException('Cannot delete location with associated events');
            }
            DB::transaction(function () use ($location) {
                $this->deleteImages($location);
                $location->delete();
            });
        } catch (Exception $e) {
            Log::error('Error deleting location', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Could not delete location.');
        }
    }

    /**
     * Store multiple images for the location.
     *
     * @param Location $location
     * @param array $images
     * @return void
     */
    protected function storeImages(Location $location, array $images): void
    {
        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $path = $image->store('locations', 'public');
                $location->images()->create(['path' => $path]);
            }
        }
    }

    /**
     * Delete all images related to the location.
     *
     * @param Location $location
     * @return void
     */
    protected function deleteImages(Location $location): void
    {
        foreach ($location->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }
}
