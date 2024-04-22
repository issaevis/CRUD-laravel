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
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->id }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->title }}</td>
                            <td>{{ strtoupper($invoice->type) }}</td>
                            <td>{{ $invoice->description }}</td>
                            <td>
                                @if($invoice->image)
                                    <a href="{{ asset('storage/images/' . $invoice->image) }}" class="d-block">
                                        <img src="{{ asset('storage/images/' . $invoice->image) }}" alt="Invoice Image" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                    </a>
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>
                                <ol>
                                    @foreach($invoice->products as $product)
                                        <li>{{ $product->name }}</li>
                                    @endforeach
                                </ol>
                            </td>
                            <td>
                                <ol>
                                    @foreach($invoice->products as $product)
                                        <li>{{ $product->pivot->quantity }}x</li>
                                    @endforeach
                                </ol>
                            </td>
                            <td>
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary ">Edit</a>
                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.delete-btn').click(function(event) {
                event.preventDefault();
                if (confirm("Are you sure you want to delete this invoice?")) {
                    var form = $(this).closest('form');
                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: form.serialize(),
                        success: function () {
                            location.reload();
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@stop
