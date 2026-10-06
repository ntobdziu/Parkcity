<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield("title", "ParkCity")</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

@auth
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route("dashboard") }}">🅿️ ParkCity</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                @php($role = auth()->user()->role)

                @if ($role === "gradjanin")
                    <li class="nav-item"><a class="nav-link" href="{{ route("vehicles.index") }}">Moja vozila</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("tickets.index") }}">Parking karte</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("reservations.index") }}">Rezervacije</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("permits.index") }}">Rezidentske dozvole</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("fines.index") }}">Moje kazne</a></li>
                @elseif ($role === "operater")
                    <li class="nav-item"><a class="nav-link" href="{{ route("zones.index") }}">Zone</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("spots.index") }}">Parking mesta</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("permits.index") }}">Rezidentske dozvole</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("reports.index") }}">Izveštaji</a></li>
                @elseif ($role === "kontrolor")
                    <li class="nav-item"><a class="nav-link" href="{{ route("controls.create") }}">Nova kontrola</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("controls.index") }}">Moje kontrole</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("fines.index") }}">Kazne</a></li>
                @elseif ($role === "admin")
                    <li class="nav-item"><a class="nav-link" href="{{ route("users.index") }}">Korisnici</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("zones.index") }}">Zone</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("spots.index") }}">Parking mesta</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("permits.index") }}">Dozvole</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("fines.index") }}">Kazne</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("reports.index") }}">Izveštaji</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("logs.index") }}">Log aktivnosti</a></li>
                @endif
            </ul>
            <span class="navbar-text text-light me-3">
                {{ auth()->user()->name }} <span class="badge bg-secondary">{{ $role }}</span>
            </span>
            <form method="POST" action="{{ route("logout") }}">
                @csrf
                <button class="btn btn-outline-light btn-sm">Odjava</button>
            </form>
        </div>
    </div>
</nav>
@endauth

<div class="container pb-5">
    @if (session("status"))
        <div class="alert alert-success">{{ session("status") }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield("content")
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
