@extends("layouts.app")
@section("title", "Izmeni vozilo")
@section("content")
<h3 class="mb-3">Izmeni vozilo</h3>
<form method="POST" action="{{ route("vehicles.update", $vehicle) }}" class="card p-4 bg-white">
    @csrf @method("PUT")
    @include("vehicles._form")
    <button class="btn btn-primary">Sačuvaj izmene</button>
</form>
@endsection
