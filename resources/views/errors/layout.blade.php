@extends('layouts.master')

@section('content')
<div class="container text-center py-5">
    <h1 class="fw-bold mt-3">cuma.click</h1>

    <img src="{{ asset('nyasar.svg') }}"
         class="img-fluid my-4"
         style="max-width:300px">

    <h1>@yield('title')</h1>
    <p>
        @yield('message')
        <br>
        <a href="javascript:history.back()" class="fw-bold">Go back</a>
    </p>
</div>
@endsection
