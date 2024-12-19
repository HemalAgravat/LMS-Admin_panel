<?php

use App\Http\Controllers\Books\BookController;
use App\Http\Controllers\Books\BorrowingRecordController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pay-penalty/{borrowing}', [PaymentController::class, 'showPaymentForm'])->name('pay.penalty');
Route::post('/process-payment/{borrowing}', [PaymentController::class, 'processPayment'])->name('process.payment');

Route::get('/payment-success/{borrowing}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment-cancel/{borrowing}', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');

Route::get('borrowings/user-stats', [BorrowingRecordController::class, 'userStats'])->name('borrowings.userStats');
Route::get('publication-stats', [BookController::class, 'showPublicationStats']);
Route::get('book/return-stats', [BorrowingRecordController::class, 'showReturnStats'])->name('book.return_stats');
