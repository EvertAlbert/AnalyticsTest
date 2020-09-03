@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <section class="row" id="productField">
        <h3 class="center">Products</h3>

        @if(count($products) > 0)
            @foreach($products as $product)
                <article class="col l3 m3 s6">
                    <div class="card">
                        <section class="card-content">
                            <span class="card-title">{{$product->name}}</span>
                            <p>This is some information about the product</p>
                            <p><a href="/products/{{$product->id}}" onclick="logProductView('{{$product->id}}')">Show
                                    product...</a></p>
                        </section>
                    </div>
                </article>
            @endforeach
        @else
            <p class="center">No products found</p>
        @endif
    </section>

    <script src="/scripts/productScript.js"></script>
@endsection
