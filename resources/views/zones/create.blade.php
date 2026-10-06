@extends("layouts.app")
@section("title", "Nova zona")
@section("content")
<h3 class="mb-3">Nova parking zona</h3>
<form method="POST" action="{{ route("zones.store") }}" class="card p-4 bg-white">
    @csrf
    @include("zones._form")
    <button class="btn btn-primary">Sačuvaj</button>
</form>
@endsection
