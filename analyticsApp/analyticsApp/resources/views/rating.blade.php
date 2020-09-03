@extends('layouts.app')

@section('title', 'Home')

@section('sidebar')
    @parent

    <p></p> <!--This is appended to the master sidebar.-->
@endsection

@section('content')
    <h1 class="row center">Rate us</h1>
    <section class="row" id="productField">
        <article class="center col l12 m12 s12">
            <a href="#" id="rateBad" class="ratingFace"><i class="far fa-frown"></i></a>
            <a href="#" id="rateMeh" class="ratingFace"><i class="far fa-meh"></i></a>
            <a href="#" id="rateGood" class="ratingFace"><i class="far fa-smile"></i></a>
        </article>
    </section>
    <p class="row center" id="thanksMessage"></p>

    <script src="/scripts/ratingScript.js"></script>
@endsection