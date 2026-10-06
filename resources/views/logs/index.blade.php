@extends('layouts.app')
@section('title', 'Log aktivnosti')
@section('content')
<h3 class="mb-3">Log aktivnosti korisnika</h3>

<table class="table bg-white">
    <thead><tr><th>Vreme</th><th>Korisnik</th><th>Rola</th><th>Akcija</th></tr></thead>
    <tbody>
    @forelse ($logs as $log)
        <tr>
            <td>{{ $log->timestamp->format('d.m.Y H:i:s') }}</td>
            <td>{{ $log->user->name ?? '—' }}</td>
            <td>{{ $log->user->role ?? '—' }}</td>
            <td>{{ $log->action }}</td>
        </tr>
    @empty
        <tr><td colspan="4" class="text-muted">Nema zabeleženih aktivnosti.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $logs->links() }}
@endsection
