@extends("layouts.app")
@section("title", "Rezervacije")
@section("content")
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Moje rezervacije</h3>
    <a href="{{ route("reservations.create") }}" class="btn btn-primary">+ Nova rezervacija</a>
</div>
<table class="table bg-white">
    <thead><tr><th>Mesto</th><th>Zona</th><th>Od</th><th>Do</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @forelse ($reservations as $r)
        <tr>
            <td>{{ $r->spot->code }}</td>
            <td>{{ $r->spot->zone->name }}</td>
            <td>{{ $r->start_time->format("d.m. H:i") }}</td>
            <td>{{ $r->end_time->format("d.m. H:i") }}</td>
            <td>
                @php($colors=["aktivna"=>"success","zavrsena"=>"secondary","otkazana"=>"danger"])
                <span class="badge bg-{{ $colors[$r->status] }}">{{ $r->status }}</span>
            </td>
            <td>
                @if ($r->status === "aktivna")
                <form method="POST" action="{{ route("reservations.cancel", $r) }}" onsubmit="return confirm('Otkazati rezervaciju?')">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger">Otkaži</button>
                </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-muted">Nemate rezervacija.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
