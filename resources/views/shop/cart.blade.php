@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1>Shopping Cart</h1>
            <div class="cart-items">
                <p>Your shopping cart is currently empty. Add some products to get started.</p>
            </div>
            <div class="cart-summary">
                <p>Cart summary and total will be displayed here.</p>
            </div>
            <div class="cart-actions">
                <p>Proceed to checkout or continue shopping options will be available here.</p>
            </div>
        </div>
    </div>
</div>
@endsection