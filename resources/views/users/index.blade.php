@extends('layouts.app')
@section('title', 'Korisnici')
@section('content')
<h3 class="mb-3">Korisnici sistema</h3>

<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Sve role</option>
            @foreach (['admin','operater','kontrolor','gradjanin'] as $r)
                <option value="{{ $r }}" @selected(request('role') === $r)>{{ $r }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Svi statusi</option>
            @foreach (['pending','active','inactive'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
            @endforeach
        </select>
    </div>
</form>

<table class="table bg-white align-middle">
    <thead><tr><th>Ime</th><th>Email</th><th>Telefon</th><th>Rola</th><th>Status</th><th>Registrovan</th><th></th></tr></thead>
    <tbody>
    @forelse ($users as $u)
        <tr>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->phone ?? '—' }}</td>
            <td>
                <form method="POST" action="{{ route('users.role', $u) }}" class="d-flex gap-1">
                    @csrf
                    <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach (['admin','operater','kontrolor','gradjanin'] as $r)
                            <option value="{{ $r }}" @selected($u->role === $r)>{{ $r }}</option>
                        @endforeach
                    </select>
                </form>
            </td>
            <td>
                @php($colors = ['pending' => 'warning', 'active' => 'success', 'inactive' => 'secondary'])
                <span class="badge bg-{{ $colors[$u->status] }}">{{ $u->status }}</span>
            </td>
            <td>{{ $u->created_at->format('d.m.Y') }}</td>
            <td class="d-flex gap-1">
                @if ($u->status !== 'active')
                    <form method="POST" action="{{ route('users.approve', $u) }}">
                        @csrf
                        <button class="btn btn-sm btn-success">Odobri</button>
                    </form>
                @endif
                @if ($u->status !== 'inactive')
                    <form method="POST" action="{{ route('users.deactivate', $u) }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Deaktiviraj</button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-muted">Nema korisnika.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $users->links() }}
@endsection
