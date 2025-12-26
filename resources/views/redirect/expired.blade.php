@extends('layouts.master')

@section('content')
<div class="container text-center py-5">
    <h1 class="fw-bold mt-3">cuma.click</h1>

    <img src="{{ asset('undraw_season-change_ohe6.svg') }}" class="img-fluid my-4" style="max-width:300px">

    <h1>Sorry, this link expired.</h1>
    <p>
        This link has passed its time limit.
        <br>
        <a href="/" class="fw-bold">Make new!</a>
    </p>
</div>
@endsection
