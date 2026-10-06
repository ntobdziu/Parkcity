@extends("layouts.app")
@section("title", "Moja vozila")
@section("content")
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Moja vozila</h3>
    <a href="{{ route("vehicles.create") }}" class="btn btn-primary">+ Dodaj vozilo</a>
</div>
<table class="table bg-white">
    <thead><tr><th>Registracija</th><th>Marka</th><th>Model</th><th>Tip</th><th>Godište</th><th></th></tr></thead>
    <tbody>
    @forelse ($vehicles as $v)
        <tr>
            <td>{{ $v->plate }}</td>
            <td>{{ $v->brand }}</td>
            <td>{{ $v->model }}</td>
            <td>{{ $v->type }}</td>
            <td>{{ $v->year }}</td>
            <td>
                <a href="{{ route("vehicles.edit", $v) }}" class="btn btn-sm btn-outline-secondary">Izmeni</a>
                <form action="{{ route("vehicles.destroy", $v) }}" method="POST" class="d-inline" onsubmit="return confirm('Obrisati vozilo?')">
                    @csrf @method("DELETE")
                    <button class="btn btn-sm btn-outline-danger">Obriši</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-muted">Nemate registrovanih vozila.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
