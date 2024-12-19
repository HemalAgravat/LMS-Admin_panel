<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\BorrowingRecord;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Method showPaymentForm
     *
     * @param BorrowingRecord $borrowing [explicite description]
     *
     * @return void
     */
    public function showPaymentForm(BorrowingRecord $borrowing)
    {
        if ($borrowing->penalty_paid) {
            return response()->json(["already pay"]);
        }

        $title = $borrowing->book->title;
        $id = $borrowing->id;
        $penalty = $borrowing->total_penalty;

        return view('payments.payment_penalty', compact('borrowing', 'penalty', 'title', 'id'));
    }

    /**
     * Method processPayment
     *
     * @param Request $request [explicite description]
     * @param BorrowingRecord $borrowing [explicite description]
     *
     * @return void
     */
    public function processPayment(Request $request, BorrowingRecord $borrowing)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => $borrowing->book->title,
                    ],
                    'unit_amount' => $borrowing->total_penalty * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['borrowing' => $borrowing->id]),
            'cancel_url' => route('payment.cancel', ['borrowing' => $borrowing->id]),
        ]);

        return redirect($session->url);
    }

    /**
     * Method paymentSuccess
     *
     * @param BorrowingRecord $borrowing [explicite description]
     *
     * @return void
     */
    public function paymentSuccess(BorrowingRecord $borrowing)
    {
        $borrowing->update(['penalty_paid' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment successful and penalty cleared!',
        ], 200);
    }

    /**
     * Method paymentCancel
     *
     * @param BorrowingRecord $borrowing [explicite description]
     *
     * @return void
     */
    public function paymentCancel(BorrowingRecord $borrowing)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Payment cancelled.',
        ], 400);
    }
}
