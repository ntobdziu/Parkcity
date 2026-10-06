@extends("layouts.app")
@section("title", "Operater — Dashboard")
@section("content")
<h3 class="mb-4">Kontrolna tabla — Operater</h3>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center p-3"><h2>{{ $free }}</h2><small>Slobodna mesta</small></div></div>
    <div class="col-md-3"><div class="card text-center p-3"><h2>{{ $occupied }}</h2><small>Zauzeta mesta</small></div></div>
    <div class="col-md-3"><div class="card text-center p-3 border-warning"><h2>{{ $pendingPermits }}</h2><small>Dozvole na čekanju</small></div></div>
</div>

<h5>Pregled zona</h5>
<table class="table table-sm bg-white">
    <thead><tr><th>Zona</th><th>Lokacija</th><th>Tarifa/h</th><th>Broj mesta</th></tr></thead>
    <tbody>
    @foreach ($zones as $zone)
        <tr>
            <td>{{ $zone->name }}</td>
            <td>{{ $zone->location }}</td>
            <td>{{ $zone->tariff_per_hour }} din</td>
            <td>{{ $zone->spots_count }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<a href="{{ route("zones.index") }}" class="btn btn-primary btn-sm">Upravljaj zonama</a>
<a href="{{ route("spots.index") }}" class="btn btn-outline-primary btn-sm">Upravljaj parking mestima</a>
@endsection
