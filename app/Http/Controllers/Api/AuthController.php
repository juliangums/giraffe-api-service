<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Mails;
use App\Models\SinglePhotoVideo;
use App\Models\User;
use App\Services\HashAndSalt;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PHPUnit\Util\Exception;

class AuthController extends Controller
{

    private $hashingService;

    public function __construct(HashAndSalt $hashAndSalt)
    {
        $this->hashingService = $hashAndSalt;
    }

    /**
     * Register user
     *
     * @param Request $request
     * @return Response
     * @throws Exception|\Exception
     */
    public function register(Request $request): Response
    {
        $validatedData = $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|unique:USERS,EMAILADDRESS',
            'termsOfAgreement' => 'required|boolean',
            'password' => 'required|min:3',
        ]);

        $salt = mb_convert_encoding($this->hashingService->salting(), 'UTF-8', 'UTF-8');
        $hashedPass = $this->hashingService->hashing($salt, $request->input('password'));
        $user = User::create([
            'FULLNAME' => $validatedData['fullName'],
            'EMAILADDRESS' => $validatedData['email'],
            'PASSWORD' => $hashedPass,
            'ACCEPTEDUSERAGREEMENT' => $validatedData['termsOfAgreement'],
            'RECEIVEEMAILS' => false,
            'SALT' => $salt,
            'HASHEDEMAILADDRESS' => $this->hashingService->hashing($salt, $request->input('email')),
            'LASTLOGIN' => -1,
            'USERNAME' => Str::before($request->input('email'), '@'),
            'DATEINMILLISECONDS' => Carbon::now()->getPreciseTimestamp(3),
        ]);

        $user->token = $user->createToken('auth_token')->plainTextToken;

        return new Response([
            'message' => 'Register Successful',
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Login user
     *
     * @param Request $request
     * @return UserResource
     * @throws ValidationException
     */
    public function login(Request $request): UserResource
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);

        $user = User::query()->where('EMAILADDRESS', $request->input('email'))->first();

        if (!$user || !$this->hashingService->checkHash($user->SALT, $request->input('password'), $user->PASSWORD)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->token = $user->createToken('auth_token')->plainTextToken;

        return new UserResource($user);
    }

    /**
     * Logout user
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = User::where('UUID', Auth::id())->firstOrFail();
        $user->tokens()->delete();
        return response()->json([
            'message' => 'Tokens Revoked',
        ]);
    }

    /**
     * Reset password
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function resetPassword(Request $request): Response
    {
        $request->validate([
            'password' => 'required|min:3',
            'confirmPassword' => 'required|min:3',
            'email' => 'required|email',
            'token' => 'required'
        ]);

        if ($request->input('password') !== $request->input('confirmPassword')) {
            throw ValidationException::withMessages([
                'password' => ['passwords dont match'],
            ]);
        }

        $salt = mb_convert_encoding($this->hashingService->salting(), 'UTF-8', 'UTF-8');

        $token = Mails::query()->where('email', $request->input('email'))->pluck('token')->first();

        if ($token != $request->input('token')) {
            throw ValidationException::withMessages([
              'token' => ['Token not valid'],
            ]);
        }

        $hashedPass = $this->hashingService->hashing($salt, $request->input('password'));

        User::query()->where('EMAILADDRESS', $request->input('email'))->update([
            'PASSWORD' => $hashedPass,
            'SALT' => $salt,
        ]);

        Mails::query()->where('email', $request->input('email'))->delete();

        return new Response([
            'message' => 'Reset successful',
        ]);
    }
}
