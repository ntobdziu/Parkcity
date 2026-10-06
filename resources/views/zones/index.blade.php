@extends("layouts.app")
@section("title", "Zone")
@section("content")
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Parking zone</h3>
    <a href="{{ route("zones.create") }}" class="btn btn-primary">+ Nova zona</a>
</div>
<table class="table bg-white">
    <thead><tr><th>Naziv</th><th>Lokacija</th><th>Tarifa/h</th><th>Maks. trajanje</th><th>Radno vreme</th><th>Tip</th><th>Mesta</th><th></th></tr></thead>
    <tbody>
    @foreach ($zones as $z)
        <tr>
            <td>{{ $z->name }}</td>
            <td>{{ $z->location }}</td>
            <td>{{ $z->tariff_per_hour }} din</td>
            <td>{{ $z->max_duration_minutes ? $z->max_duration_minutes." min" : "—" }}</td>
            <td>{{ $z->work_hours_from }} – {{ $z->work_hours_to }}</td>
            <td><span class="badge bg-secondary">{{ $z->type }}</span></td>
            <td>{{ $z->spots_count }}</td>
            <td>
                <a href="{{ route("zones.edit", $z) }}" class="btn btn-sm btn-outline-secondary">Izmeni</a>
                <form action="{{ route("zones.destroy", $z) }}" method="POST" class="d-inline" onsubmit="return confirm('Obrisati zonu?')">
                    @csrf @method("DELETE")
                    <button class="btn btn-sm btn-outline-danger">Obriši</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
