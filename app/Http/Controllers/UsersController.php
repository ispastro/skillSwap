<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UploadProfilePictureRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();

        // Optional search by name or skills
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereJsonContains('skills_offered', $search)
                  ->orWhereJsonContains('skills_needed', $search);
        }

        // Filter by location
        if ($request->has('city')) {
            $query->where('city', $request->input('city'));
        }

        $users = $query->paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProfileRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $user->update($request->validated());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        // Delete profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    /**
     * Upload a profile picture for the specified user.
     *
     * @param  \App\Http\Requests\UploadProfilePictureRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadProfilePicture(UploadProfilePictureRequest $request, User $user)
    {
        $this->authorize('update', $user);

        // Delete existing picture if present
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $path = $request->file('image')->store('profile_pictures', 'public');
        $user->update(['profile_picture' => $path]);

        return new UserResource($user);
    }
}