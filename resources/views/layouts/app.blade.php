<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #F5F7FA, #C0E0DE); /* Soft light gradient */
            font-family: 'Roboto', Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            background-color: #fff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            animation: fadeInUp 1s ease-out;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
            padding: 20px;
        }

        .book-info {
            margin-bottom: 40px;
            text-align: center;
        }

        .book-info h3 {
            color: #007bff;
            font-weight: 600;
        }

        .book-info h5 {
            color: #6c757d;
            font-size: 1.2rem;
        }

        .book-info h4 {
            color: #28a745;
            font-weight: 600;
            font-size: 1.4rem;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 14px 35px;
            font-size: 1.2rem;
            border-radius: 35px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-3px);
        }
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @media (max-width: 768px) {
            .container {
                padding: 25px;
            }

            .book-info h3, .book-info h4 {
                font-size: 1.6rem;
            }

            .btn-primary {
                font-size: 1rem;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    Book Details
                </div>
                <div class="card-body">
                    <div class="book-info mb-4">
                        <h3>Book Title: {{ $borrowing->book->title }}</h3>
                        <h5>Book ID: {{ $borrowing->id }}</h5>
                        <h4>Penalty Total Amount: ₹{{ number_format($penalty, 2) }}</h4>
                    </div>
                    <div class="text-center">
                        <form action="{{ route('process.payment', $borrowing->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
