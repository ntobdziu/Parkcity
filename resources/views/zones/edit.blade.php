@extends("layouts.app")
@section("title", "Izmeni zonu")
@section("content")
<h3 class="mb-3">Izmeni parking zonu</h3>
<form method="POST" action="{{ route("zones.update", $zone) }}" class="card p-4 bg-white">
    @csrf @method("PUT")
    @include("zones._form")
    <button class="btn btn-primary">Sačuvaj izmene</button>
</form>
@endsection
