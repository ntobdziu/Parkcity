@extends("layouts.app")
@section("title", "Dodaj vozilo")
@section("content")
<h3 class="mb-3">Dodaj vozilo</h3>
<form method="POST" action="{{ route("vehicles.store") }}" class="card p-4 bg-white">
    @csrf
    @include("vehicles._form")
    <button class="btn btn-primary">Sačuvaj</button>
</form>
@endsection
