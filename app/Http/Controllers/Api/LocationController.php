<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Services\LocationService;
use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Http\Requests\Location\LocationStoreRequest;
use App\Http\Requests\Location\LocationUpdateRequest;

class LocationController extends Controller
{
    protected LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Display a listing of locations.
     */
    public function index(Request $request)
    {
        $locations = Location::withCount('images')->with('images')->paginate(15);
        return $this->successResponse(LocationResource::collection($locations), 'Locations fetched successfully');
    }

    /**
     * Store a newly created location.
     */
    public function store(LocationStoreRequest $request)
    {
        $location = $this->locationService->create($request->validated());
        return $this->successResponse(new LocationResource($location), 'Location created successfully', 201);
    }

    /**
     * Display the specified location.
     */
    public function show(Location $location)
    {
        $location->load('images');
        return $this->successResponse(new LocationResource($location), 'Location retrieved successfully');
    }

    /**
     * Update the specified location.
     */
    public function update(LocationUpdateRequest $request, Location $location)
    {
        $location = $this->locationService->update($location, $request->validated());
        return $this->successResponse(new LocationResource($location), 'Location updated successfully!');
    }

    /**
     * Remove the specified location.
     */
    public function destroy(Location $location)
    {
        if (!auth()->user()->can('manage locations')) {
            return $this->errorResponse('Unauthorize', 403);
        }
        $this->locationService->delete($location);
        return $this->successResponse(null, 'Location deleted successfullly');
    }
}
