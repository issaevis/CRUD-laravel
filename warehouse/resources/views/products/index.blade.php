@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="row">
            @foreach ($products as $product)
                <div class="col-sm">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ $product->name }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Quantity: {{ $product->quantity }}</p>
                            <p class="card-text">Price: {{ $product->price }}</p>
                            <p class="card-text">Category: {{ $product->categoryTitle }}</p>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm">
                                    <a href="{{ route('products.edit', $product->id) }}"
                                       class="btn btn-primary btn-sm">Edit</a>
                                </div>
                                <div class="col-sm">
                                    <button type="submit" class="btn btn-danger btn-sm delete-btn"
                                            onclick="deleteCategory({{ $product->id }})">Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function deleteCategory(productId) {
            $.ajax({
                url: "{{ route('products.destroy', ['product' => ':productId']) }}".replace(':productId', productId),
                type: "DELETE",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function () {
                    location.reload();
                }
            });
        }
    </script>
@stop
