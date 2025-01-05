
@extends('front.layout.layout')
@section('content')
<div align="center"><div class="print-error-msg"></div></div>
<div class="app-content" id="appendCartItems">
    @include('front.products.cart_items')
</div>

@endsection
