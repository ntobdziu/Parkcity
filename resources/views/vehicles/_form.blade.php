<div class="mb-3">
    <label class="form-label">Registarski broj</label>
    <input type="text" name="plate" class="form-control" value="{{ old("plate", $vehicle->plate ?? "") }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Marka</label>
    <input type="text" name="brand" class="form-control" value="{{ old("brand", $vehicle->brand ?? "") }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Model</label>
    <input type="text" name="model" class="form-control" value="{{ old("model", $vehicle->model ?? "") }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Tip vozila</label>
    <select name="type" class="form-select" required>
        @foreach (["putnicko" => "Putničko", "dostavno" => "Dostavno", "motocikl" => "Motocikl"] as $val => $label)
            <option value="{{ $val }}" @selected(old("type", $vehicle->type ?? "putnicko") === $val)>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Godište</label>
    <input type="number" name="year" class="form-control" value="{{ old("year", $vehicle->year ?? "") }}">
</div>
