@extends("layouts.app")
@section("title", "Moj nalog — Dashboard")
@section("content")
<h3 class="mb-4">Dobrodošli, {{ auth()->user()->name }}</h3>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center p-3"><h2>{{ $vehicles }}</h2><small>Moja vozila</small></div></div>
    <div class="col-md-3"><div class="card text-center p-3"><h2>{{ $activeTickets->count() }}</h2><small>Aktivne parking karte</small></div></div>
    <div class="col-md-3"><div class="card text-center p-3"><h2>{{ $permits }}</h2><small>Rezidentske dozvole</small></div></div>
    <div class="col-md-3"><div class="card text-center p-3 border-danger"><h2>{{ $fines }}</h2><small>Neplaćene kazne</small></div></div>
</div>

@if ($activeTickets->count())
    <h5>Aktivne parking karte</h5>
    <table class="table table-sm bg-white mb-4">
        <thead><tr><th>Zona</th><th>Ističe</th><th></th></tr></thead>
        <tbody>
        @foreach ($activeTickets as $t)
            <tr>
                <td>{{ $t->zone->name }}</td>
                <td>{{ $t->end_time->format("d.m.Y H:i") }}</td>
                <td><a href="{{ route("tickets.show", $t) }}">Detalji</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

<a href="{{ route("tickets.create") }}" class="btn btn-primary">+ Kupi parking kartu</a>
<a href="{{ route("vehicles.index") }}" class="btn btn-outline-secondary">Moja vozila</a>
<a href="{{ route("reservations.create") }}" class="btn btn-outline-secondary">Rezerviši mesto</a>
@endsection
