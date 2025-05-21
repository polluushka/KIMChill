<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushSubscription;

class SubscriptionController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);
        $user = auth()->user();
        $user->pushSubscriptions()->where('endpoint', $request->endpoint)->delete();
        $user->pushSubscriptions()->create([
            'endpoint' => $request->endpoint,
            'public_key' => $request->keys['p256dh'],
            'auth_token' => $request->keys['auth'],
            'content_encoding' => $request->input('contentEncoding', 'aesgcm'),
        ]);
        return response()->json(['success' => true]);
    }

}
