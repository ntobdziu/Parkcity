@extends("layouts.app")
@section("title", "Prijava")
@section("content")
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <h3 class="mb-3 text-center">🅿️ ParkCity — Prijava</h3>

                @if (session("status"))
                    <div class="alert alert-info">{{ session("status") }}</div>
                @endif

                <form method="POST" action="{{ route("login") }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old("email") }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lozinka</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Zapamti me</label>
                    </div>
                    <button class="btn btn-primary w-100">Prijavi se</button>
                </form>
                <p class="text-center mt-3 mb-0">
                    Nemate nalog? <a href="{{ route("register") }}">Registrujte se</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
