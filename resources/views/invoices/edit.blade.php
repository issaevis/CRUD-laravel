@extends('layouts.app')

@section('content')
    <div class="container h-100 mt-5">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-10 col-md-8 col-lg-6">
                <h3 class="text-center mb-4">Update Invoice</h3>
                <form action="{{ route('invoices.update', $invoice->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $invoice->title }}" required>
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" name="type" id="type">
                            <option value="buy" {{ $invoice->type == 'buy' ? 'selected' : '' }}>Buy</option>
                            <option value="sell" {{ $invoice->type == 'sell' ? 'selected' : '' }}>Sell</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" name="description" value="{{ $invoice->description }}">
                    </div>

                    <div id="product-fields">
                        @foreach($invoice->products as $product)
                            <div class="product-field">
                                <div class="form-group">
                                    <label for="product">Select Product</label>
                                    <select class="form-control" name="products[]">
                                        @foreach ($products as $prod)
                                            <option value="{{ $prod->id }}" {{ $product->id == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" class="form-control" name="quantity[]" min="1" value="{{ $product->pivot->quantity }}">
                                </div>

                                <button type="button" class="btn btn-danger btn-sm" onclick="removeProductField(this)">Remove Product</button>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>

                    <button type="button" class="btn btn-success btn-block" onclick="addProductField()">Add Product</button>

                    <button type="submit" class="btn btn-primary btn-block mt-3">Update Invoice</button>
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

            var productSelect = document.querySelector('.product-field select[name="products[]"]').cloneNode(true);
            productSelect.name = 'products[]';

            var quantityInput = document.querySelector('.product-field input[name="quantity[]"]').cloneNode(true);
            quantityInput.name = 'quantity[]';

            var productLabel = document.createElement('label');
            productLabel.textContent = 'Select Product';
            var quantityLabel = document.createElement('label');
            quantityLabel.textContent = 'Quantity';

            var removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add('btn', 'btn-danger', 'btn-sm');
            removeButton.textContent = 'Remove Product';
            removeButton.addEventListener('click', function() {
                productField.remove();
            });

            productField.appendChild(productLabel);
            productField.appendChild(productSelect);
            productField.appendChild(quantityLabel);
            productField.appendChild(quantityInput);
            productField.appendChild(removeButton);

            document.getElementById('product-fields').appendChild(productField);
        }

        function removeProductField(button) {
            button.closest('.product-field').remove();
        }
    </script>
@stop
