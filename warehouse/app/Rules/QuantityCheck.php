<?php

namespace App\Rules;

use Closure;
use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class QuantityCheck implements Rule
{
    protected $productIds;
    protected $quantities;
    protected $typeOf;

    /**
     * Create a new rule instance.
     *
     * @param array $productIds
     * @param array $quantities
     * @return void
     */
    public function __construct(array $productIds, array $quantities, string $typeOf)
    {
        $this->productIds = $productIds;
        $this->quantities = $quantities;
        $this->typeOf = $typeOf;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->typeOf == 'buy') {
            foreach ($this->productIds as $key => $productId) {
                $quantity = $this->quantities[$key];
                $product = Product::find($productId);
                if ($quantity > $product->quantity) {
                    return false;
                }
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
