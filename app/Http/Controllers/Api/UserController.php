<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        if (!auth()->user()->can('view users')) {
            return $this->errorResponse('Unauthorized.', 403);
        }

        $users = User::all();

        return $this->successResponse(UserResource::collection($users));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        if (!auth()->user()->can('delete users')) {
            return $this->errorResponse('Unauthorized', 403);
        }

        if ($user->id === Auth::id()) {
            return $this->errorResponse('You cannot delete your own account', 400);
        }

        $deleted = $user->delete();

        if ($deleted) {
            return $this->successResponse(null, 'User deleted successfully');
        }

        return $this->errorResponse('Failed to delete user', 500);
    }
}
