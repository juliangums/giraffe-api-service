<?php

namespace App\Http\Controllers;

use App\Mail\ForgotEmail;
use App\Models\Mails;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MailController extends Controller
{

    /**
     * SendMail to user
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function sendMail(Request  $request): Response
    {

        $request->validate([
            'email' => 'required|email',
            'link' => 'required'
        ]);

        $user = User::query()->where('EMAILADDRESS', $request->input('email'))->first();
        $isToken = Mails::query()->where('email', $request->input('email'))->pluck('token')->first();


        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Email not found'],
            ]);
        }

        Mail::to($request->input('email'))->send(new ForgotEmail($request->input('link')));

        if (isset($isToken)) {
            return new Response([
                'message' => 'Link sent to e-mail',
                'token' => $isToken,
            ]);
        }

        $token = Str::random(60);

        Mails::create([
            'email' => $request->input('email'),
            'token' => $token,
        ]);

        return new Response([
            'message' => 'Link sent to e-mail',
            'token' => $token,
        ]);
    }
}
