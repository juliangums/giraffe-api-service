<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\DataCollectionEvent;
use App\Models\SinglePhotoVideo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\ProfileImage;

class ProfileController extends Controller
{
    /**
     * Fetch currently logged in user.
     *
     * @param Request $request
     * @return UserResource
     */
    public function user(Request $request): UserResource
    {
        return new UserResource(Auth::user());
    }

    /**
     * Profile edit data
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request): Response
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('USERS', 'EMAILADDRESS')->ignore(Auth::id(), 'UUID')],
        ]);

        $user = Auth::user();

        $user->update([
            'FULLNAME' => $validatedData['name'],
            'EMAILADDRESS' => $validatedData['email'],
        ]);

        return new Response([
            'message' => 'Profile info updated',
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Delete profile data
     *
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request): Response
    {
        $request->validate([
            'uuid' => 'required',
        ]);

        $user = User::find($request->input('uuid'));

        $user->capture()->detach();

        $user->submitters()->detach();

        User::destroy($request->input('uuid'));

        return new Response([
           'message' => 'Delete Successful',
        ]);
    }
    
    /**
     * Update profile Image
     *
     * @param Request $request
     * @return UserResource
     */
    public function updateImage(Request $request): UserResource
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,raw,heif', 'max:2048'],
        ]);

        /**
         * @var User $user
         */
        $user = Auth::user();
        $file = $request->file('image');

        $path = $file->storeAs('users/' . ($user->USERNAME ?? $user->EMAILADDRESS), $file->getClientOriginalName(), 'encounters');

        $image = ProfileImage::query()->updateOrCreate([
            'CORRESPONDINGUSERNAME' => $user?->USERNAME ?? $user->EMAILADDRESS,
        ], [
            'FULLFILESYSTEMPATH' => "/data/giraffe_data_dir/$path",
            'FILENAME' => $request->file('image')->getClientOriginalName(),
            'WEBURL' => env('WEB_URL') . "/giraffe_data_dir/$path",
        ]);
        $user->USERIMAGE_DATACOLLECTIONEVENTID_OID = $image->DATACOLLECTIONEVENTID;
        $user->save();
    
        return new UserResource($user->refresh()->load('profileImage'));
    }
}
