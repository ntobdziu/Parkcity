<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Naziv zone</label>
        <input type="text" name="name" class="form-control" value="{{ old("name", $zone->name ?? "") }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Lokacija</label>
        <input type="text" name="location" class="form-control" value="{{ old("location", $zone->location ?? "") }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tarifa po satu (din)</label>
        <input type="number" step="0.01" name="tariff_per_hour" class="form-control" value="{{ old("tariff_per_hour", $zone->tariff_per_hour ?? "") }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Maks. trajanje (min, opciono)</label>
        <input type="number" name="max_duration_minutes" class="form-control" value="{{ old("max_duration_minutes", $zone->max_duration_minutes ?? "") }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tip zone</label>
        <select name="type" class="form-select" required>
            @foreach (["standardna" => "Standardna", "rezidentska" => "Rezidentska", "invalidska" => "Invalidska"] as $val => $label)
                <option value="{{ $val }}" @selected(old("type", $zone->type ?? "") === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Radno vreme od</label>
        <input type="time" name="work_hours_from" class="form-control" value="{{ old("work_hours_from", isset($zone) ? substr($zone->work_hours_from,0,5) : "07:00") }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Radno vreme do</label>
        <input type="time" name="work_hours_to" class="form-control" value="{{ old("work_hours_to", isset($zone) ? substr($zone->work_hours_to,0,5) : "21:00") }}" required>
    </div>
</div>
