@extends('layouts.app')

@section('title', 'Home')

@section('sidebar')
    @parent

    <p></p> <!--This is appended to the master sidebar.-->
@endsection

@section('content')
    <section class="row">
        <h2>Please select your language</h2>
        <ul class="langSelectList center langSelect">
            <li><a href="#" class="customButton" id="dutchButton">NL</a></li>
            <li><a href="#" class="customButton" id="frenchButton">FRA</a></li>
            <li><a href="#" class="customButton" id="englishButton">ENG</a></li>
        </ul>
    </section>
    <section class="hidden ageSelect row">
        <h2>Please enter your age</h2>
        <input type="number" min="1" max="130" id="ageInput">
        <a href="#" id="ageSubmit" class="customButton">Confirm</a>
    </section>

    <script src="/scripts/script.js"></script>
@endsection