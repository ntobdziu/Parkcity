@extends("layouts.app")
@section("title", "Zahtev za rezidentsku dozvolu")
@section("content")
<h3 class="mb-3">Zahtev za rezidentsku dozvolu</h3>
<form method="POST" action="{{ route("permits.store") }}" class="card p-4 bg-white">
    @csrf
    <div class="mb-3">
        <label class="form-label">Zona</label>
        <select name="zone_id" class="form-select" required>
            @foreach ($zones as $z)
                <option value="{{ $z->id }}">{{ $z->name }} ({{ $z->location }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Registarski broj vozila</label>
        <input type="text" name="plate" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Važi od</label>
        <input type="date" name="valid_from" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Važi do</label>
        <input type="date" name="valid_until" class="form-control" required>
    </div>
    <button class="btn btn-primary">Pošalji zahtev</button>
</form>
@endsection
