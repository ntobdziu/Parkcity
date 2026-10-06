@extends("layouts.app")
@section("title", "Nova kontrola")
@section("content")
<h3 class="mb-3">Provera vozila</h3>
<form method="POST" action="{{ route("controls.lookup") }}" class="card p-4 bg-white" style="max-width:500px">
    @csrf
    <div class="mb-3">
        <label class="form-label">Registarski broj vozila</label>
        <input type="text" name="plate" class="form-control" placeholder="npr. NP-123-AA" required autofocus>
    </div>
    <button class="btn btn-primary">Proveri status parkiranja</button>
</form>
@endsection
