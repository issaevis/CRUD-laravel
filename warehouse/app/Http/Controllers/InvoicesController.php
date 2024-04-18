<?php

namespace App\Http\Controllers;

use App\Rules\QuantityCheck;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Invoice;
use Illuminate\Support\Str;

class InvoicesController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $products = Product::all();
        return view('invoices.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:invoices',
            'type' => 'required',
            'description' => 'max:255',
            'quantity' => ['required', new QuantityCheck($request->input('products'), $request->input('quantity'))],
            'invoice_number' => 'unique:invoices',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $invoiceNumber = 'INV-' . Str::random(12);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storeAs('public/images', $request->file('image')->hashName());
        }

        $invoice = [
            'invoice_number' => $invoiceNumber,
            'title' => $request->input('title'),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ];

        $createdInvoice = Invoice::create($invoice);

        foreach ($request->input('products') as $productId) {
            $product = Product::find($productId);
            if ($product) {
                if ($createdInvoice->type == Invoice::BUY) {
                    $product->quantity -= $request->input('quantity')[$productId];
                } elseif ($createdInvoice->type == Invoice::SELL) {
                    $product->quantity += $request->input('quantity')[$productId];
                }

                $product->save();

                $quantity = $request->input('quantity')[$productId];
                $createdInvoice->products()->attach($product, ['quantity' => $quantity]);
            }
        }
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $products = Product::all();

        return view('invoices.edit', compact('invoice', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|unique:invoices',
            'type' => 'required',
            'description' => 'max:255',
            'quantity' => 'required|min:1',
            'invoice_number' => 'unique:invoices',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $invoice = Invoice::findOrFail($id);


        $invoice->update($request->all());

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return response()->noContent();
    }
}
