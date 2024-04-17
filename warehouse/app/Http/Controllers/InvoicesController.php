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
            'products' => 'required',
            'quantities' => 'required',
            'image' => '',
        ]);

        if ($request->input('type') == 'buy') {
            for ($i = 0; $i < count($request->input('products')); $i++) {
                $product = Product::find($request->input('products')[$i]);

                if ($product) {
                    if ($product->quantity >= $request->input('quantities')[$i]) {
                        $product->quantity -= $request->input('quantities')[$i];
                        $product->save();

                        Invoice::create([
                            'invoice_nr' => $request->input('title'),
                            'title' => $request->input('title'),
                            'type' => $request->input('type'),
                            'description' => $request->input('description'),
                            'quantities' => $request->input('quantities')[$i],
                            'image' => $request->input('image'),
                            'products' => $product->name,
                        ]);
                    } else {
                        return response()->json(['error' => 'Insufficient quantity for product ' . $product->name], 400);
                    }
                } else {
                    return response()->json(['error' => 'Product not found'], 404);
                }
            }
        } elseif ($request->input('type') == 'sell') {
            for ($i = 0; $i < count($request->input('products')); $i++) {
                $product = Product::find($request->input('products')[$i]);

                if ($product) {
                    $product->quantity += $request->input('quantities')[$i];
                    $product->save();

                    Invoice::create([
                        'title' => $request->input('title'),
                        'type' => $request->input('type'),
                        'description' => $request->input('description'),
                        'product' => $product->name,
                        'quantity' => $request->input('quantities')[$i],
                        'image' => $request->input('image'),
                    ]);
                } else {
                    return response()->json(['error' => 'Product not found'], 404);
                }
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
