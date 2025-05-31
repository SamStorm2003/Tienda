<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Exception;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
        ]);
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100,
                'currency' => 'bob',
                'payment_method_data' => [
                    'billing_details' => [
                        'email' => $request->email,
                        'address' => [
                            'city' => $request->city,
                        ],
                    ],
                ],
            ]);
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
