@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card mx-auto" style="max-width: 700px; border-radius: 15px; overflow: hidden;">
        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
            <div class="book-info mb-4">
                <h3 class="text-primary font-weight-bold animated fadeIn">Book Title: {{ $borrowing->book->title }}</h3>
                <h5 class="text-muted font-italic animated fadeIn" style="animation-delay: 0.3s;">Book ID: {{ $borrowing->id }}</h5>
                <h4 class="text-success font-weight-bold animated fadeIn" style="animation-delay: 0.6s;">Penalty Total Amount: ₹{{ number_format($penalty, 2) }}</h4>
            </div>
            <div class="payment-form animated fadeIn" style="animation-delay: 1s;">
                <form action="{{ route('process.payment', $borrowing->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg animated bounceInUp">Proceed to Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .container {
        max-width: 700px;
        padding: 40px;
        margin-top: 50px;
        background: linear-gradient(135deg, #f3f4f6, #ffffff);
    }
    .card {
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        animation: fadeInUp 1s ease-out;
    }

    .card-body {
        padding: 40px;
    }

    .book-info {
        margin-bottom: 40px;
    }

    .book-info h3 {
        font-family: 'Arial', sans-serif;
        font-weight: 700;
        font-size: 2rem;
        color: #007bff;
    }

    .book-info h5 {
        font-family: 'Arial', sans-serif;
        font-weight: 400;
        font-size: 1.3rem;
        color: #6c757d;
    }

    .book-info h4 {
        font-family: 'Arial', sans-serif;
        font-weight: 600;
        font-size: 1.5rem;
        color: #28a745;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        border-radius: 50px;
        padding: 14px 30px;
        font-size: 1.2rem;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: all 0.3s ease-in-out;
        width: 100%;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: scale(1.05);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animated {
        animation-duration: 1s;
        animation-fill-mode: both;
    }

    .fadeIn {
        animation-name: fadeIn;
    }

    .fadeInUp {
        animation-name: fadeInUp;
    }

    .bounceInUp {
        animation-name: bounceInUp;
    }

    @keyframes bounceInUp {
        0% {
            opacity: 0;
            transform: translateY(3000px);
        }
        60% {
            opacity: 1;
            transform: translateY(-20px);
        }
        80% {
            transform: translateY(10px);
        }
        100% {
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 25px;
            max-width: 100%;
        }

        .book-info h3 {
            font-size: 1.6rem;
        }

        .book-info h4 {
            font-size: 1.3rem;
        }

        .btn-primary {
            width: 100%;
            font-size: 1rem;
            padding: 12px;
        }
    }
</style>
@endsection
