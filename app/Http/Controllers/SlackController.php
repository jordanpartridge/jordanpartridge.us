<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\RespondToUser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SlackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): FoundationApplication|Response|Application|ResponseFactory
    {

        $verification = $this->handleUrlVerification($request);
        if ($request->input('event.type') === 'app_mention') {
            $message = $request->input('event.blocks.0.elements.0.elements.1.text');
            $userId = $request->input('event.user'); // Extract the user ID

            User::first()->notify(new RespondToUser($message, $userId));
        }


        return response($verification, 200);

    }

    private function handleUrlVerification(Request $request): ?string
    {
        if ($request->input('type') !== 'url_verification') {
            return null;
        }

        return $request->input('challenge');
    }
}
