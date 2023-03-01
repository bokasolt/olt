<div>
    @if ($orderPlaced)
        <script>
        	TwoCoInlineCart.setup.setMerchant({{ $merchantCode }});
        	TwoCoInlineCart.register();
        	TwoCoInlineCart.cart.setReset(true);

            TwoCoInlineCart.products.add({
                code: '{{ $productCode }}',
                quantity: '{{ $quantity }}'
            });
            TwoCoInlineCart.cart.setOrderExternalRef('{{ $orderId }}');
            TwoCoInlineCart.cart.setExternalCustomerReference('{{ $userId }}');
            TwoCoInlineCart.cart.setReturnMethod({
                type: 'redirect',
                url: '{{ $urlProcessPayment }}'
            })
            TwoCoInlineCart.cart.setCartLockedFlag(true)
            TwoCoInlineCart.cart.checkout()
        </script>
        <div>
        	Redirect to 2checkout
        </div>
    @else
        <p class="text-center">Purchase form</p>
        <div class="form-group row">
            <div class="col-md-4 offset-md-4">
                <input type="number" name="quantity" id="quantity" class="form-control" wire:model="quantity" min="{{ $min_order }}"
                       max="{{ $max_order }}" required
                       autofocus/>
            </div>
        </div>

        <div class="form-group row mb-0 text-center">
            <div class="col-md-4 offset-md-4">
                <button class="btn btn-primary" wire:click="checkout">@lang('Purchase')</button>
            </div>
        </div>
    @endif
</div>
