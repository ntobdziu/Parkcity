@extends("layouts.app")
@section("title", "Novo parking mesto")
@section("content")
<h3 class="mb-3">Novo parking mesto</h3>
<form method="POST" action="{{ route("spots.store") }}" class="card p-4 bg-white">
    @csrf
    <div class="mb-3">
        <label class="form-label">Zona</label>
        <select name="zone_id" class="form-select" required>
            @foreach ($zones as $z)
                <option value="{{ $z->id }}">{{ $z->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Oznaka mesta</label>
        <input type="text" name="code" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Poseban tip (opciono)</label>
        <select name="special_type" class="form-select">
            <option value="">— Standardno —</option>
            <option value="invalidsko">Invalidsko</option>
            <option value="elektro">Električna vozila</option>
            <option value="dostava">Dostava</option>
        </select>
    </div>
    <button class="btn btn-primary">Sačuvaj</button>
</form>
@endsection
