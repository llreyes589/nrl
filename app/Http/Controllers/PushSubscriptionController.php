<?php

// app/Http/Controllers/PushSubscriptionController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PushSubscriptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'endpoint'    => 'required|url',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);

        // Link browser endpoint data to the authenticated user
        $request->user()->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth']
        );

        return response()->json(['success' => true]);
    }
}
