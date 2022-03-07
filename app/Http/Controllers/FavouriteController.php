<?php

namespace App\Http\Controllers;

use App\Http\Resources\MarkedIndividualResource;
use App\Models\MarkedIndividual;
use App\Models\User;
use App\Models\Encounter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FavouriteController extends Controller
{
    /**
     * Get all favorite posts by user
     *
     * @return AnonymousResourceCollection
     */
    public function favourites(): AnonymousResourceCollection
    {
        /**
         * @var User $user
         */
        $user = User::query()->where('UUID', Auth::id())->firstOrFail();

        return MarkedIndividualResource::collection($user->favorites->load('encounters.annotations.media'));
    }

    /**
     * Favorite a particular encounter
     *
     * @param MarkedIndividual $markedIndividual
     * @return JsonResponse
     */
    public function favoriteEncounter(MarkedIndividual $markedIndividual): JsonResponse
    {
        $user = User::where('UUID', Auth::id())->firstOrFail();

        $user->favorites()->attach($markedIndividual->INDIVIDUALID);

        return response()->json([
            'message' => 'Giraffe has been added to favourites',
        ]);
    }

    /**
     * Un favorite a particular encounter
     *
     * @param MarkedIndividual $markedIndividual
     * @return JsonResponse
     */
    public function unFavoriteEncounter(MarkedIndividual $markedIndividual): JsonResponse
    {
        $user = User::where('UUID', Auth::id())->firstOrFail();

        $user->favorites()->detach($markedIndividual->INDIVIDUALID);

        return response()->json([
            'message' => 'Giraffe has been removed favourites',
        ]);
    }
}
