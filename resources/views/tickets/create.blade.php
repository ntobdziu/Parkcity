@extends("layouts.app")
@section("title", "Kupi parking kartu")
@section("content")
<h3 class="mb-3">Kupovina parking karte</h3>

@if ($vehicles->isEmpty())
    <div class="alert alert-warning">
        Nemate registrovano vozilo. <a href="{{ route("vehicles.create") }}">Dodajte vozilo</a> pre kupovine karte.
    </div>
@else
<form method="POST" action="{{ route("tickets.store") }}" class="card p-4 bg-white">
    @csrf
    <div class="mb-3">
        <label class="form-label">Vozilo</label>
        <select name="vehicle_id" class="form-select" required>
            @foreach ($vehicles as $v)
                <option value="{{ $v->id }}">{{ $v->plate }} ({{ $v->brand }} {{ $v->model }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Zona</label>
        <select name="zone_id" class="form-select" required>
            @foreach ($zones as $z)
                <option value="{{ $z->id }}">{{ $z->name }} — {{ $z->tariff_per_hour }} din/h</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Trajanje (minuta)</label>
        <input type="number" name="duration_minutes" class="form-control" min="15" step="15" value="60" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Kanal kupovine</label>
        <select name="channel" class="form-select" required>
            <option value="app">Mobilna aplikacija</option>
            <option value="sms">SMS</option>
            <option value="automat">Parking automat</option>
        </select>
    </div>
    <button class="btn btn-primary">Kupi kartu</button>
</form>
@endif
@endsection
