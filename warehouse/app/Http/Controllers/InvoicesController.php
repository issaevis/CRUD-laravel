<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Invoice;

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
            'quantity' => 'required',
            'image' => '',
        ]);

        $invoice = new Invoice();
        $invoice->invoice_number = rand();
        $invoice->title = $request->input('title');
        $invoice->type = $request->input('type');
        $invoice->description = $request->input('description');
        $invoice->quantity = $request->input('quantity')[0];
        $invoice->image = $request->input('image');
        $invoice->save();



        foreach ($request->input('products') as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $quantity = $request->input('quantity')[0] ?? 1; // Default to 1 if quantity not provided

                if ($invoice->type == 'buy' && $product->quantity < $quantity) {
                    $invoice->delete();
                    return response()->json(['error' => 'Insufficient quantity for product ' . $product->name], 400);
                }

                if ($invoice->type == 'buy') {
                    $product->quantity -= $quantity;
                } else {
                    $product->quantity += $quantity;
                }

                $product->save();

                $invoice->products()->attach($product);
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
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
