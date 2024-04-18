<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Product;

class QuantityCheck implements Rule
{
    protected $productIds;
    protected $quantities;

    /**
     * Create a new rule instance.
     *
     * @param  array  $productIds
     * @param  array  $quantities
     * @return void
     */
    public function __construct(array $productIds, array $quantities)
    {
        $this->productIds = $productIds;
        $this->quantities = $quantities;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($this->productIds as $key => $productId) {
            $quantity = $this->quantities[$key];
            $product = Product::find($productId);
            if ($quantity > $product->quantity) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be smaller than or equal to the product quantity.';
    }
}
