@extends("layouts.app")
@section("title", "Rezidentske dozvole")
@section("content")
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Moje rezidentske dozvole</h3>
    <a href="{{ route("permits.create") }}" class="btn btn-primary">+ Zatraži dozvolu</a>
</div>
<table class="table bg-white">
    <thead><tr><th>Zona</th><th>Registracija</th><th>Važi od</th><th>Važi do</th><th>Status</th></tr></thead>
    <tbody>
    @forelse ($permits as $p)
        <tr>
            <td>{{ $p->zone->name }}</td>
            <td>{{ $p->plate }}</td>
            <td>{{ $p->valid_from->format("d.m.Y") }}</td>
            <td>{{ $p->valid_until->format("d.m.Y") }}</td>
            <td>
                @php($colors=["na_cekanju"=>"warning","aktivna"=>"success","istekla"=>"secondary","odbijena"=>"danger"])
                <span class="badge bg-{{ $colors[$p->status] }}">{{ $p->status }}</span>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-muted">Nemate zahteva za rezidentske dozvole.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
