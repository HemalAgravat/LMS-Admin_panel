<?php
return [
    'user' => [
        'register' => 'User registered successfully.',
        'registration' => 'User registration failed',
        'login' => 'Login successful',
        'logout' => 'Logout successful',
        'users' => 'User retrieved successfully',
        'failed' => 'Failed to fetch users',
        'notfound' => 'not found',
        'update' => 'User updated successfully',
        'delete' => 'Failed to delete user',
        'deleted' => 'User deleted successfully',
        'unauthenticated' => 'You are not authenticated.',

    ],

    'error' => [
        'default' => 'An error occurred!',

        'login' => [
            'invalid_credentials' => 'Invalid credentials',
        ],
        'logout' => [
            'already' => 'You are already logged out.',
            'unauthenticated' => 'You are not authenticated.',
        ],
    ],

    'books' => [
        'create' =>  'Book Data created successfully',
        'Failed' => 'Failed to fetch book',
        'update' => 'Book Data Update successfully',
        'FailedUpdate' => 'Failed To Update',
        'deleted' => 'Book Data deleted successfully',
        'NoFound' => 'Book can not Found',
        'found' => 'Book Data fetch successfully',
        'import' => 'Books imported successfully',
        'importFailed' => 'Failed to import books',
        'export' => 'Books export successfully',
        'exportFailed' => 'Failed to export books',


    ],
    'search' => 'search field is require',

    'borrow' => [
        'create' =>  'Book Borrowing Successfully',
        'borrowFailed' => 'Failed to fetch Borrowing Book',
        'NoFound' => 'Borrowing Book can not Found',
        'found' => 'Book Data fetch successfully',
        'borrowedBook' => 'You have already borrowed this book',
        'borrowedBookFailed' => 'Failed to borrow book',
        'borrowsearch=' => 'An error occurred while searching for borrowed books.',
        'book_not_available' => 'this book is not available',
        'borrowlimit' => 'You cannot borrow more than 5 books at a time',
        'notifications' => 'Overdue notifications sent successfully',
        'returned' => 'You have already returned this book.',
        'Penalty_paid' => 'Penalty has not been paid. Cannot mark the book as returned',
        'error' => 'An error occurred while fetching borrowings',
        'userborrowings' => 'No borrowings found for this user',
        'bookborrowings' => 'No borrowings found for this book',
        'returnhistory' => 'No return history found',


    ],
];
