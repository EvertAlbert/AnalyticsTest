@extends('layouts.app')

@section('title', 'ProductDetail')

@section('content')
    <section class="row" id="productField">
        <h3 class="center">{{$product->name}}</h3>
        <article class="col s12 m12 l12">
            <h4>More information</h4>
            <p>This is some random text that I put her just to show that there is a detial page for
                <b>{{$product->name}}</b></p>
            <p>Did you know this product has a productId? No? The id of this product is <b>{{$product->id}}</b></p>
        </article>
    </section>

    <script src="/scripts/productDetailScript.js"></script>
@endsection
