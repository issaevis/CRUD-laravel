<?php

namespace App\Http\Controllers;

use App\Rules\QuantityCheckRule;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Invoice;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Nette\Schema\ValidationException;

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
        $invoiceNumber = 'INV-' . Str::random(12);

        $request->validate([
            'title' => 'required|unique:invoices',
            'type' => 'required',
            'description' => 'max:255',
            'quantity' => ['required', new QuantityCheckRule($request->input('products'), $request->input('quantity'), $request->input('type'))],
            'invoice_number' => 'required|unique:invoices',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'products.*' => 'exists:products,id',
        ]);

        if ($request->image) {
            $imageName = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $destinationPath = public_path('storage/images/');
            $request->image->move($destinationPath, $imageName);
        }

        $invoice = [
            'invoice_number' => $invoiceNumber,
            'title' => $request->input('title'),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'image' => $imageName,
        ];

        $createdInvoice = Invoice::create($invoice);

        $index = 0;
        foreach ($request->input('products') as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $quantityChange = ($createdInvoice->type == Invoice::SELL)
                    ? -$request->input('quantity')[$index]
                    : $request->input('quantity')[$index];

                $product->quantity += $quantityChange;
                $product->save();

                $createdInvoice->products()->attach($product, ['quantity' => $request->input('quantity')[$index]]);
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
        $invoice = Invoice::findOrFail($id);
        $productIds = $request->input('products');
        $quantities = $request->input('quantity');

        $request->validate([
            'title' => 'required|unique:invoices,title,' . $id,
            'description' => 'max:255',
            'quantity.*' => ['required', new QuantityCheckRule($productIds, $quantities, $invoice->type)],
            'invoice_number' => 'unique:invoices,invoice_number,' . $id,
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $invoice->title = $request->input('title');
        $invoice->description = $request->input('description');

        if ($request->hasFile('image')) {
            $imageName = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $destinationPath = public_path('storage/images/');
            $request->image->move($destinationPath, $imageName);
            $invoice->image = $imageName;
        }
        $invoice->save();

        foreach ($request->input('products') as $index => $productId) {
            $product = Product::find($productId);

            if ($product) {
                $pivotData = $invoice->products()->where('product_id', $productId)->first()->pivot;

                $oldQuantity = $pivotData->quantity;
                $newQuantity = $request->input('quantity')[$index];

                $quantityChange = ($invoice->type == Invoice::BUY) ? $newQuantity - $oldQuantity : $oldQuantity - $newQuantity;
                $product->quantity = $product->quantity + $quantityChange;

                $product->save();
                $invoice->products()->updateExistingPivot($product, ['quantity' => $newQuantity]);
            }
        }
        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return response()->noContent();
    }
}
