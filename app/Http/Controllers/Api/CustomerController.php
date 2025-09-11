<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use Webkul\Customer\Models\Customer;

class CustomerController extends Controller
{
    /**
     * GET /api/v1/customer/get
     * Returns authenticated customer with profile image URL.
     */
    public function get(Request $request)
    {
        // Try standard guards first
        $customer = auth('customer')->user() ?? auth()->user();

        // Fallback: resolve via Sanctum bearer token if present
        if (! $customer && ($token = $request->bearerToken())) {
            $accessToken = PersonalAccessToken::findToken($token);
            if ($accessToken && $accessToken->tokenable instanceof Customer) {
                $customer = $accessToken->tokenable;
            }
        }

        if (! $customer) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Ensure fresh model instance to include appended attributes
        $customer->refresh();

        // Return only safe fields including computed image_url
        $data = [
            'id'                      => $customer->id,
            'first_name'              => $customer->first_name,
            'last_name'               => $customer->last_name,
            'email'                   => $customer->email,
            'phone'                   => $customer->phone,
            'gender'                  => $customer->gender,
            'date_of_birth'           => $customer->date_of_birth,
            'subscribed_to_news_letter' => (bool) $customer->subscribed_to_news_letter,
            'status'                  => (bool) $customer->status,
            'is_verified'             => (bool) $customer->is_verified,
            'is_suspended'            => (bool) $customer->is_suspended,
            'image_url'               => $customer->image_url,
        ];

        return response()->json(['data' => $data]);
    }
}

