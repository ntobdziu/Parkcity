@extends("layouts.app")
@section("title", "Kazne")
@section("content")
<h3 class="mb-3">Kazne</h3>
<table class="table bg-white">
    <thead><tr><th>Vozilo</th>
    @if (auth()->user()->role !== "gradjanin")<th>Vlasnik</th>@endif
    <th>Iznos</th><th>Izdata</th><th>Status</th><th>Način plaćanja</th>
    @if (in_array(auth()->user()->role, ["kontrolor","admin"]))<th></th>@endif
    </tr></thead>
    <tbody>
    @forelse ($fines as $f)
        <tr>
            <td>{{ $f->vehicle->plate }}</td>
            @if (auth()->user()->role !== "gradjanin")<td>{{ $f->vehicle->owner->name ?? "—" }}</td>@endif
            <td>{{ $f->amount }} din</td>
            <td>{{ $f->issued_at->format("d.m.Y H:i") }}</td>
            <td>
                @php($colors=["neplacena"=>"danger","placena"=>"success","u_postupku"=>"warning"])
                <span class="badge bg-{{ $colors[$f->status] }}">{{ $f->status }}</span>
            </td>
            <td>{{ $f->payment_method ?? "—" }}</td>
            @if (in_array(auth()->user()->role, ["kontrolor","admin"]))
            <td>
                @if ($f->status !== "placena")
                <form method="POST" action="{{ route("fines.pay", $f) }}" class="d-flex gap-1">
                    @csrf
                    <select name="payment_method" class="form-select form-select-sm" required>
                        <option value="gotovina">Gotovina</option>
                        <option value="kartica">Kartica</option>
                        <option value="online">Online</option>
                    </select>
                    <button class="btn btn-sm btn-success">Naplati</button>
                </form>
                @endif
            </td>
            @endif
        </tr>
    @empty
        <tr><td colspan="6" class="text-muted">Nema kazni.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $fines->links() }}
@endsection
