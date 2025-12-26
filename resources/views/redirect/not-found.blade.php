@extends('layouts.master')

@section('content')
<div class="container text-center py-5">
    <h1 class="fw-bold mt-3">cuma.click</h1>

    <img src="{{ asset('undraw_eating-pasta_96tb.svg') }}" class="img-fluid my-4" style="max-width:300px">

    <h1>Something's wrong here.</h1>
    <p>
        This is a 404 error, which means you've clicked on a bad link.
        <br>
        <a href="/" class="fw-bold">Make new!</a>
    </p>
</div>
@endsection
