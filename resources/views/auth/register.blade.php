@extends("layouts.app")
@section("title", "Registracija")
@section("content")
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <h3 class="mb-3 text-center">🅿️ ParkCity — Registracija</h3>

                <form method="POST" action="{{ route("register") }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Ime i prezime</label>
                        <input type="text" name="name" class="form-control" value="{{ old("name") }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old("email") }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old("phone") }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tip naloga</label>
                        <select name="role" class="form-select" required>
                            <option value="gradjanin" selected>Građanin</option>
                            <option value="operater">Operater (zahteva odobrenje administratora)</option>
                            <option value="kontrolor">Kontrolor (zahteva odobrenje administratora)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lozinka</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Potvrda lozinke</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100">Registruj se</button>
                </form>
                <p class="text-center mt-3 mb-0">
                    Već imate nalog? <a href="{{ route("login") }}">Prijavite se</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
