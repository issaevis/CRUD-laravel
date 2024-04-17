@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Invoices</h1>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice Number</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Products</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->id }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->title }}</td>
                            <td>{{ $invoice->type }}</td>
                            <td>{{ $invoice->description }}</td>
                            <td>
                                @if($invoice->image)
                                    <img src="{{ asset($invoice->image) }}" alt="Invoice Image" style="max-width: 100px; max-height: 100px;">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>
                                <ul>
                                    @foreach($invoice->products as $product)
                                        <li>{{ $product->name }} (Quantity: {{ $invoice->quantity }})</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
