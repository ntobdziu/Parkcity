@extends("layouts.app")
@section("title", "Nova rezervacija")
@section("content")
<h3 class="mb-3">Rezerviši parking mesto</h3>
@if ($spots->isEmpty())
    <div class="alert alert-warning">Trenutno nema slobodnih parking mesta za rezervaciju.</div>
@else
<form method="POST" action="{{ route("reservations.store") }}" class="card p-4 bg-white">
    @csrf
    <div class="mb-3">
        <label class="form-label">Parking mesto</label>
        <select name="spot_id" class="form-select" required>
            @foreach ($spots as $s)
                <option value="{{ $s->id }}">{{ $s->code }} — {{ $s->zone->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Početak</label>
        <input type="datetime-local" name="start_time" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Kraj</label>
        <input type="datetime-local" name="end_time" class="form-control" required>
    </div>
    <button class="btn btn-primary">Rezerviši</button>
</form>
@endif
@endsection
