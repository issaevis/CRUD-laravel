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
                        <th>Action</th>
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
                            <td>
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary">Edit</a>
                                <button class="btn btn-danger" onclick="deleteInvoice({{ $invoice->id }})">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function deleteInvoice(invoiceId) {
            if (confirm("Are you sure you want to delete this invoice?")) {
                $.ajax({
                    url: "{{ route('invoices.destroy', ['invoice' => ':invoiceId']) }}".replace(':invoiceId', invoiceId),
                    type: "DELETE",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function () {
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        }
    </script>
@endsection
