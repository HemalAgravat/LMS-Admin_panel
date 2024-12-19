<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Books\BookController;
use App\Http\Controllers\Books\BorrowingRecordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Users\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::put('users/{uuid}', [UserController::class, 'update']);
    Route::get('users/{uuids}', [UserController::class, 'show']);
    Route::delete('users/{uuid}', [UserController::class, 'destroy']);
    Route::post('/user/search', [UserController::class, 'search']);
    Route::get('user', [UserController::class, 'UserRecords']);



    Route::post('books/import', [BookController::class, 'import']);
    Route::get('books/export', [BookController::class, 'export']);

    Route::post('/books', [BookController::class, 'createBook']);
    Route::get('books/{uuid}', [BookController::class, 'show']);
    Route::put('/books/{uuid}', [BookController::class, 'updateBook']);
    Route::delete('/books/{uuid}', [BookController::class, 'deleteBook']);
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books/availability', [BookController::class, 'updateAvailability']);
    Route::post('/books/search', [BookController::class, 'search']);


    Route::get('borrow/track', [BorrowingRecordController::class, 'getAllBorrowingRecords']);
    Route::post('borrow', [BorrowingRecordController::class, 'createborrowBook']);
    Route::get('borrow/{uuid}', [BorrowingRecordController::class, 'show']);
    Route::post('borrow/search', [BorrowingRecordController::class, 'search']);
    Route::post('books/{id}', [BorrowingRecordController::class, 'return']);

    Route::get('report/user/{user_id}', [BorrowingRecordController::class, 'reportByUser']);
    Route::get('report/book/{book_id}', [BorrowingRecordController::class, 'reportByBook']);
    Route::get('report/returns/{book_id}', [BorrowingRecordController::class, 'returnHistory']);

    Route::post('notifications/send-overdue', [NotificationController::class, 'sendOverdueNotifications']);
    
});
