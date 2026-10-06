@extends('layouts.app')
@section('title', 'Izveštaji i analitika')
@section('content')
<h3 class="mb-3">Izveštaji i analitika</h3>

<div class="card mb-4">
    <div class="card-body">
        <h6 class="text-muted">Ukupan prihod od parking karata</h6>
        <h2 class="mb-0">{{ number_format($totalRevenue, 2) }} din</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header fw-semibold">Prodate karte i prihod po zoni</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Zona</th><th>Broj karata</th><th>Prihod</th></tr></thead>
                    <tbody>
                    @forelse ($ticketsByZone as $row)
                        <tr>
                            <td>{{ $row->zone->name ?? '—' }}</td>
                            <td>{{ $row->broj }}</td>
                            <td>{{ number_format($row->prihod, 2) }} din</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted p-3">Nema podataka.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header fw-semibold">Kazne po statusu</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Status</th><th>Broj</th><th>Iznos</th></tr></thead>
                    <tbody>
                    @forelse ($finesStats as $row)
                        <tr>
                            <td>{{ $row->status }}</td>
                            <td>{{ $row->broj }}</td>
                            <td>{{ number_format($row->iznos, 2) }} din</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted p-3">Nema podataka.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header fw-semibold">Popunjenost parking mesta po zonama</div>
    <div class="card-body">
        @forelse ($occupancy as $zone)
            @php
                $pct = $zone->ukupno_mesta > 0 ? round(($zone->zauzeta_mesta / $zone->ukupno_mesta) * 100) : 0;
            @endphp
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <span>{{ $zone->name }}</span>
                    <span class="text-muted">{{ $zone->zauzeta_mesta }} / {{ $zone->ukupno_mesta }} zauzeto ({{ $zone->slobodna_mesta }} slobodno)</span>
                </div>
                <div class="progress" role="progressbar">
                    <div class="progress-bar bg-danger" style="width: {{ $pct }}%">{{ $pct }}%</div>
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">Nema definisanih zona.</p>
        @endforelse
    </div>
</div>
@endsection
