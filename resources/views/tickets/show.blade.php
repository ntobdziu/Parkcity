@extends("layouts.app")
@section("title", "Parking karta #".$ticket->id)
@section("content")
<h3 class="mb-3">Parking karta #{{ $ticket->id }}</h3>
<div class="card p-4 bg-white" style="max-width:500px">
    <p><strong>Vozilo:</strong> {{ $ticket->vehicle->plate }}</p>
    <p><strong>Zona:</strong> {{ $ticket->zone->name }}</p>
    <p><strong>Od:</strong> {{ $ticket->start_time->format("d.m.Y H:i") }}</p>
    <p><strong>Do:</strong> {{ $ticket->end_time->format("d.m.Y H:i") }}</p>
    <p><strong>Cena:</strong> {{ $ticket->price }} din</p>
    <p><strong>QR kod:</strong> <code>{{ $ticket->qr_code }}</code></p>
    <p><strong>Status:</strong> {{ $ticket->status }}</p>

    @if ($ticket->status === "aktivna" && $ticket->user_id === auth()->id())
        <form method="POST" action="{{ route("tickets.extend", $ticket) }}" class="d-flex gap-2 mb-2">
            @csrf
            <input type="number" name="extra_minutes" class="form-control" placeholder="Dodatnih minuta" min="15" required>
            <button class="btn btn-outline-primary">Produži</button>
        </form>
        <form method="POST" action="{{ route("tickets.cancel", $ticket) }}" onsubmit="return confirm('Otkazati kartu?')">
            @csrf
            <button class="btn btn-outline-danger w-100">Otkaži kartu</button>
        </form>
    @endif
</div>
<a href="{{ route("tickets.index") }}" class="d-inline-block mt-3">← Nazad na moje karte</a>
@endsection
