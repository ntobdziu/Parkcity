@extends("layouts.app")
@section("title", "Admin — Dashboard")
@section("content")
<h3 class="mb-4">Kontrolna tabla — Administrator</h3>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center p-3"><h2>{{ $usersTotal }}</h2><small>Ukupno korisnika</small></div></div>
    <div class="col-md-3"><div class="card text-center p-3 border-warning"><h2>{{ $usersPending }}</h2><small>Nalozi na čekanju</small></div></div>
    <div class="col-md-3"><div class="card text-center p-3"><h2>{{ $zones }}</h2><small>Zona</small></div></div>
    <div class="col-md-3"><div class="card text-center p-3"><h2>{{ $spots }}</h2><small>Parking mesta</small></div></div>
    <div class="col-md-3"><div class="card text-center p-3"><h2>{{ $activeTickets }}</h2><small>Aktivne karte</small></div></div>
    <div class="col-md-3"><div class="card text-center p-3 border-danger"><h2>{{ $unpaidFines }}</h2><small>Neplaćene kazne</small></div></div>
</div>

@if ($usersPending > 0)
    <div class="alert alert-warning">
        Imate <strong>{{ $usersPending }}</strong> nalog(a) koji čekaju odobrenje.
        <a href="{{ route("users.index", ["status" => "pending"]) }}">Pregledajte ih ovde.</a>
    </div>
@endif

<h5>Poslednje aktivnosti u sistemu</h5>
<table class="table table-sm bg-white">
    <thead><tr><th>Vreme</th><th>Korisnik</th><th>Akcija</th></tr></thead>
    <tbody>
    @forelse ($recentLogs as $log)
        <tr>
            <td>{{ $log->timestamp->format("d.m.Y H:i") }}</td>
            <td>{{ $log->user->name ?? "—" }}</td>
            <td>{{ $log->action }}</td>
        </tr>
    @empty
        <tr><td colspan="3" class="text-muted">Nema zabeleženih aktivnosti.</td></tr>
    @endforelse
    </tbody>
</table>
<a href="{{ route("logs.index") }}">Ceo log aktivnosti →</a>
@endsection
