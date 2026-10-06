@extends("layouts.app")
@section("title", "Izmeni parking mesto")
@section("content")
<h3 class="mb-3">Izmeni parking mesto</h3>
<form method="POST" action="{{ route("spots.update", $spot) }}" class="card p-4 bg-white">
    @csrf @method("PUT")
    <div class="mb-3">
        <label class="form-label">Zona</label>
        <select name="zone_id" class="form-select" required>
            @foreach ($zones as $z)
                <option value="{{ $z->id }}" @selected($spot->zone_id === $z->id)>{{ $z->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Oznaka mesta</label>
        <input type="text" name="code" class="form-control" value="{{ $spot->code }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            @foreach (["slobodno","zauzeto","rezervisano","nedostupno"] as $st)
                <option value="{{ $st }}" @selected($spot->status === $st)>{{ $st }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Poseban tip (opciono)</label>
        <select name="special_type" class="form-select">
            <option value="">— Standardno —</option>
            @foreach (["invalidsko","elektro","dostava"] as $st)
                <option value="{{ $st }}" @selected($spot->special_type === $st)>{{ $st }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Sačuvaj izmene</button>
</form>
@endsection
