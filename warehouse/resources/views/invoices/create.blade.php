@extends('layouts.app')
@section('content')
    <div class="container h-100 mt-5">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-10 col-md-8 col-lg-6">
                <h3 class="text-center mb-4">Add an Invoice</h3>
                <form action="{{ route('invoices.store') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" name="type" id="type">
                            <option value="buy">Buy</option>
                            <option value="sell">Sell</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" name="description">
                    </div>

                    <div id="product-fields">
                        <div class="product-field">
                            <div class="form-group">
                                <label for="products">Select Product</label>
                                <select class="form-control" name="products[]">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" class="form-control" name="quantity[]" min="1" value="1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Image">Image</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                    </div>

                    <button type="button" class="btn btn-success btn-block" onclick="addProductField()">Add Product</button>

                    <button type="submit" class="btn btn-primary btn-block mt-3">Create Invoice</button>
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger mt-3" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function addProductField() {
            var productField = document.createElement('div');
            productField.classList.add('product-field');

            var productSelect = document.querySelector('.product-field select[name="product_id[]"]').cloneNode(true);
            productSelect.name = 'product_id[]';

            var quantityInput = document.querySelector('.product-field input[name="quantity[]"]').cloneNode(true);
            quantityInput.name = 'quantity[]';

            var productLabel = document.createElement('label');
            productLabel.textContent = 'Select Product';
            var quantityLabel = document.createElement('label');
            quantityLabel.textContent = 'Quantity';

            productField.appendChild(productLabel);
            productField.appendChild(productSelect);
            productField.appendChild(quantityLabel);
            productField.appendChild(quantityInput);

            document.getElementById('product-fields').appendChild(productField);
        }
    </script>
@stop
