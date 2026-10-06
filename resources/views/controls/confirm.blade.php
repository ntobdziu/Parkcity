@extends("layouts.app")
@section("title", "Potvrda kontrole")
@section("content")
<h3 class="mb-3">Rezultat provere — {{ $vehicle->plate }}</h3>
<div class="card p-4 bg-white" style="max-width:600px">
    <p><strong>Vlasnik:</strong> {{ $vehicle->owner->name ?? "nepoznat" }}</p>
    <p><strong>Vozilo:</strong> {{ $vehicle->brand }} {{ $vehicle->model }}</p>

    @if ($activeTicket)
        <div class="alert alert-success">Vozilo ima aktivnu parking kartu do {{ $activeTicket->end_time->format("H:i") }}.</div>
    @elseif ($activePermit)
        <div class="alert alert-success">Vozilo ima važeću rezidentsku dozvolu za ovu zonu.</div>
    @else
        <div class="alert alert-danger">Vozilo NEMA aktivnu parking kartu niti rezidentsku dozvolu.</div>
    @endif

    <form method="POST" action="{{ route("controls.store") }}">
        @csrf
        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
        <div class="mb-3">
            <label class="form-label">Lokacija kontrole</label>
            <input type="text" name="location" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Rezultat kontrole</label>
            <select name="result" class="form-select" required>
                <option value="ispravno" @selected($suggestedResult==="ispravno")>Ispravno</option>
                <option value="neplaceno" @selected($suggestedResult==="neplaceno")>Neplaćeno</option>
                <option value="isteklo" @selected($suggestedResult==="isteklo")>Isteklo</option>
            </select>
            <small class="text-muted">Ako rezultat nije "ispravno", automatski se generiše kazna.</small>
        </div>
        <button class="btn btn-primary">Evidentiraj kontrolu</button>
    </form>
</div>
@endsection
