@extends('layouts.app')

@section('content')


    <h2>All Products</h2>

<table border="1">
    <thead>
    <tr>
        <th>Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Category</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->category->title }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@stop
