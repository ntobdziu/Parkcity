@extends("layouts.app")
@section("title", "Parking mesta")
@section("content")
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Parking mesta</h3>
    <a href="{{ route("spots.create") }}" class="btn btn-primary">+ Novo mesto</a>
</div>
<table class="table bg-white">
    <thead><tr><th>Oznaka</th><th>Zona</th><th>Status</th><th>Poseban tip</th><th></th></tr></thead>
    <tbody>
    @foreach ($spots as $s)
        <tr>
            <td>{{ $s->code }}</td>
            <td>{{ $s->zone->name }}</td>
            <td>
                @php($colors = ["slobodno"=>"success","zauzeto"=>"danger","rezervisano"=>"warning","nedostupno"=>"secondary"])
                <span class="badge bg-{{ $colors[$s->status] }}">{{ $s->status }}</span>
            </td>
            <td>{{ $s->special_type ?? "—" }}</td>
            <td>
                <a href="{{ route("spots.edit", $s) }}" class="btn btn-sm btn-outline-secondary">Izmeni</a>
                <form action="{{ route("spots.destroy", $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Obrisati mesto?')">
                    @csrf @method("DELETE")
                    <button class="btn btn-sm btn-outline-danger">Obriši</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $spots->links() }}
@endsection
