<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ControlController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\ParkingSpotController;
use App\Http\Controllers\ParkingTicketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ResidentPermitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ZoneController;
use Illuminate\Support\Facades\Route;

Route::get("/", fn () => redirect()->route("login"));

// ---- Gosti (bez prijave) ----
Route::middleware("guest")->group(function () {
    Route::get("/login", [AuthController::class, "showLogin"])->name("login");
    Route::post("/login", [AuthController::class, "login"]);
    Route::get("/register", [AuthController::class, "showRegister"])->name("register");
    Route::post("/register", [AuthController::class, "register"]);
});

Route::post("/logout", [AuthController::class, "logout"])->middleware("auth")->name("logout");

// ---- Prijavljeni korisnici (svi aktivni nalozi) ----
Route::middleware(["auth", "active"])->group(function () {

    Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");

    Route::get("/tickets/{ticket}", [ParkingTicketController::class, "show"])->whereNumber("ticket")->name("tickets.show");
    // ---- Građanin ----
    Route::middleware("role:gradjanin")->group(function () {
        Route::resource("vehicles", VehicleController::class)->except(["show"]);

        Route::get("/tickets", [ParkingTicketController::class, "index"])->name("tickets.index");
        Route::get("/tickets/create", [ParkingTicketController::class, "create"])->name("tickets.create");
        Route::post("/tickets", [ParkingTicketController::class, "store"])->name("tickets.store");
        Route::post("/tickets/{ticket}/extend", [ParkingTicketController::class, "extend"])->name("tickets.extend");
        Route::post("/tickets/{ticket}/cancel", [ParkingTicketController::class, "cancel"])->name("tickets.cancel");

        Route::get("/permits/create", [ResidentPermitController::class, "create"])->name("permits.create");
        Route::post("/permits", [ResidentPermitController::class, "store"])->name("permits.store");

        Route::get("/reservations", [ReservationController::class, "index"])->name("reservations.index");
        Route::get("/reservations/create", [ReservationController::class, "create"])->name("reservations.create");
        Route::post("/reservations", [ReservationController::class, "store"])->name("reservations.store");
        Route::post("/reservations/{reservation}/cancel", [ReservationController::class, "cancel"])->name("reservations.cancel");
    });

    // Deljeno: pregled sopstvenih / svih dozvola (kontroler odlučuje unutar sebe)
    Route::get("/permits", [ResidentPermitController::class, "index"])
        ->middleware("role:gradjanin,operater,admin")->name("permits.index");

    // ---- Operater ----
    Route::middleware("role:operater,admin")->group(function () {
        Route::resource("zones", ZoneController::class)->except(["show"]);
        Route::resource("spots", ParkingSpotController::class)->except(["show"]);

        Route::post("/permits/{permit}/approve", [ResidentPermitController::class, "approve"])->name("permits.approve");
        Route::post("/permits/{permit}/reject", [ResidentPermitController::class, "reject"])->name("permits.reject");

        Route::get("/reports", [ReportController::class, "index"])->name("reports.index");
    });

    // ---- Kontrolor ----
    Route::middleware("role:kontrolor")->group(function () {
        Route::get("/controls", [ControlController::class, "index"])->name("controls.index");
        Route::get("/controls/create", [ControlController::class, "create"])->name("controls.create");
        Route::post("/controls/lookup", [ControlController::class, "lookup"])->name("controls.lookup");
        Route::post("/controls", [ControlController::class, "store"])->name("controls.store");
    });

    // Deljeno: kazne (kontroler filtrira prema roli unutar sebe)
    Route::get("/fines", [FineController::class, "index"])
        ->middleware("role:gradjanin,kontrolor,admin")->name("fines.index");
    Route::post("/fines/{fine}/pay", [FineController::class, "pay"])
        ->middleware("role:kontrolor,admin")->name("fines.pay");

    // ---- Administrator ----
    Route::middleware("role:admin")->group(function () {
        Route::get("/users", [UserController::class, "index"])->name("users.index");
        Route::post("/users/{user}/approve", [UserController::class, "approve"])->name("users.approve");
        Route::post("/users/{user}/deactivate", [UserController::class, "deactivate"])->name("users.deactivate");
        Route::post("/users/{user}/role", [UserController::class, "updateRole"])->name("users.role");

        Route::get("/logs", [ActivityLogController::class, "index"])->name("logs.index");
    });
});
