@extends("layouts.app")
@section("title", "Kontrolor — Dashboard")
@section("content")
<h3 class="mb-4">Kontrolna tabla — Kontrolor</h3>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card text-center p-3"><h2>{{ $myControlsToday }}</h2><small>Kontrola danas</small></div></div>
</div>

<a href="{{ route("controls.create") }}" class="btn btn-primary mb-3">+ Nova kontrola vozila</a>

<h5>Poslednje kontrole</h5>
<table class="table table-sm bg-white">
    <thead><tr><th>Vreme</th><th>Registracija</th><th>Rezultat</th></tr></thead>
    <tbody>
    @forelse ($recentControls as $c)
        <tr>
            <td>{{ $c->timestamp->format("d.m.Y H:i") }}</td>
            <td>{{ $c->vehicle->plate }}</td>
            <td>
                <span class="badge bg-{{ $c->result === "ispravno" ? "success" : "danger" }}">{{ $c->result }}</span>
            </td>
        </tr>
    @empty
        <tr><td colspan="3" class="text-muted">Nema evidentiranih kontrola.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
