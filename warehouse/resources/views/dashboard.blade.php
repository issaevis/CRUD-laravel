@extends('layouts.app')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .block {
            padding: 20px;
            margin: 10px;
            border-radius: 10px;
        }
        .btn-custom {
            background-color: #ffc107;
            border-color: #ffc107;
            color: white;
            border-width: 2px;
            border-style: solid;
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-6 center">
                <div class="card block" style="background-color: #ffc107;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Products</h5>
                        <button class="btn btn-custom btn-lg"
                                onclick="window.location.href='{{ route('products.index') }}'">Go to Products
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 center">
                <div class="card block" style="background-color: #ffc107;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Categories</h5>
                        <button class="btn btn-custom btn-lg"
                                onclick="window.location.href='{{ route('categories.index') }}'">Go to Categories
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@stop
