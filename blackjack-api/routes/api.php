<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('auth:api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
});

// Open routes
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);


/*
POST /players : crea un jugador/a.
PUT /players/{id} : modifica el nom del jugador/a.
POST /players/{id}/games/ : un jugador/a específic realitza una tirada dels daus.
DELETE /players/{id}/games: elimina les tirades del jugador/a.
GET /players: retorna el llistat de tots els jugadors/es del sistema amb el seu percentatge mitjà d’èxits 
GET /players/{id}/games: retorna el llistat de jugades per un jugador/a.
GET /players/ranking: retorna el rànquing mitjà de tots els jugadors/es del sistema. És a dir, el percentatge mitjà d’èxits.
GET /players/ranking/loser: retorna el jugador/a amb pitjor percentatge d’èxit.
GET /players/ranking/winner: retorna el jugador/a amb millor percentatge d’èxit.
*/