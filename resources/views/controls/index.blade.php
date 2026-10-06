@extends("layouts.app")
@section("title", "Moje kontrole")
@section("content")
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Moje kontrole</h3>
    <a href="{{ route("controls.create") }}" class="btn btn-primary">+ Nova kontrola</a>
</div>
<table class="table bg-white">
    <thead><tr><th>Vreme</th><th>Registracija</th><th>Lokacija</th><th>Rezultat</th><th>Kazna</th></tr></thead>
    <tbody>
    @forelse ($controls as $c)
        <tr>
            <td>{{ $c->timestamp->format("d.m.Y H:i") }}</td>
            <td>{{ $c->vehicle->plate }}</td>
            <td>{{ $c->location }}</td>
            <td>
                <span class="badge bg-{{ $c->result === "ispravno" ? "success" : "danger" }}">{{ $c->result }}</span>
            </td>
            <td>{{ $c->fine ? $c->fine->amount." din (".$c->fine->status.")" : "—" }}</td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-muted">Nema evidentiranih kontrola.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $controls->links() }}
@endsection
