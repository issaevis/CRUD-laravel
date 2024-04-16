@extends('layouts.app')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="container">
            <div class="row">
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <div class="card bg-warning rounded">
                        <div class="card-body text-center">
                            <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg">Go to Products</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <div class="card bg-warning rounded">
                        <div class="card-body text-center">
                            <a href="{{ route('categories.index') }}" class="btn btn-warning btn-lg">Go to
                                Categories</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@stop
