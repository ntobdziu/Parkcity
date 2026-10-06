@extends("layouts.app")
@section("title", "Parking karte")
@section("content")
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Moje parking karte</h3>
    <a href="{{ route("tickets.create") }}" class="btn btn-primary">+ Kupi kartu</a>
</div>
<table class="table bg-white">
    <thead><tr><th>Vozilo</th><th>Zona</th><th>Od</th><th>Do</th><th>Cena</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @forelse ($tickets as $t)
        <tr>
            <td>{{ $t->vehicle->plate }}</td>
            <td>{{ $t->zone->name }}</td>
            <td>{{ $t->start_time->format("d.m. H:i") }}</td>
            <td>{{ $t->end_time->format("d.m. H:i") }}</td>
            <td>{{ $t->price }} din</td>
            <td>
                @php($colors=["aktivna"=>"success","istekla"=>"secondary","otkazana"=>"danger"])
                <span class="badge bg-{{ $colors[$t->status] }}">{{ $t->status }}</span>
            </td>
            <td><a href="{{ route("tickets.show", $t) }}">Detalji</a></td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-muted">Nemate kupljenih karata.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $tickets->links() }}
@endsection
