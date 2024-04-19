<?php

namespace App\Http\Controllers;

use App\Rules\QuantityCheckRule;
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
            'quantity' => ['required', new QuantityCheckRule($request->input('products'), $request->input('quantity'), $request->input('type'))],
            'invoice_number' => 'unique:invoices',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $invoiceNumber = 'INV-' . Str::random(12);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storeAs('images', $request->file('image')->hashName(), 'public');
        }
        $invoice = [
            'invoice_number' => $invoiceNumber,
            'title' => $request->input('title'),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'image' => '/storage/' . $imagePath,
        ];

        $createdInvoice = Invoice::create($invoice);
        $index = 0;

        foreach ($request->input('products') as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $quantityChange = ($createdInvoice->type == Invoice::BUY)
                    ? -$request->input('quantity')[$index]
                    : $request->input('quantity')[$index];

                $product->quantity += $quantityChange;
                $product->save();

                $createdInvoice->products()->attach($product, ['quantity' => $quantityChange]);
            }
            $index++;
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
            'quantity' => ['required', new QuantityCheckRule($request->input('products'), $request->input('quantity'), $request->input('type'))],
            'invoice_number' => 'unique:invoices',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $invoice = Invoice::findOrFail($id);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storeAs('images', $request->file('image')->hashName(), 'public');
            $invoice->image = '/storage/' . $imagePath;
        }

        $invoice->title = $request->input('title');
        $invoice->type = $request->input('type');
        $invoice->description = $request->input('description');
        $invoice->save();

        $index = 0;
        foreach ($request->input('products') as $productId) {
            $product = Product::find($productId);
            if ($product) {
                if ($invoice->type == Invoice::BUY) {
                    $product->quantity -= $request->input('quantity')[$index];
                } elseif ($invoice->type == Invoice::SELL) {
                    $product->quantity += $request->input('quantity')[$index];
                }
                $product->save();
                $quantity = $request->input('quantity')[$index];
                $invoice->products()->syncWithoutDetaching([$product->id => ['quantity' => $quantity]]);
            }
            $index++;
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }
}
