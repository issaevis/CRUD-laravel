@extends('layouts.app')

@section('content')

    <!-- resources/views/products/create.blade.php -->

    <!DOCTYPE html>
    <html>
    <head>
        <title>Create Product</title>
    </head>
    <body>

    <h2>Create Product</h2>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name">
        </div>

        <div>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity">
        </div>

        <div>
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price">
        </div>

        <div>
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: none;">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>

        <div>
            <button type="submit">Create</button>
        </div>
    </form>

    </body>
    </html>



@stop
