@extends("layouts.app")
@section("title", "Rezidentske dozvole — pregled")
@section("content")
<h3 class="mb-3">Zahtevi za rezidentske dozvole</h3>
<table class="table bg-white">
    <thead><tr><th>Podnosilac</th><th>Zona</th><th>Registracija</th><th>Važi od</th><th>Važi do</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach ($permits as $p)
        <tr>
            <td>{{ $p->user->name }}</td>
            <td>{{ $p->zone->name }}</td>
            <td>{{ $p->plate }}</td>
            <td>{{ $p->valid_from->format("d.m.Y") }}</td>
            <td>{{ $p->valid_until->format("d.m.Y") }}</td>
            <td>
                @php($colors=["na_cekanju"=>"warning","aktivna"=>"success","istekla"=>"secondary","odbijena"=>"danger"])
                <span class="badge bg-{{ $colors[$p->status] }}">{{ $p->status }}</span>
            </td>
            <td>
                @if ($p->status === "na_cekanju")
                    <form action="{{ route("permits.approve", $p) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success">Odobri</button>
                    </form>
                    <form action="{{ route("permits.reject", $p) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-danger">Odbij</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $permits->links() }}
@endsection
