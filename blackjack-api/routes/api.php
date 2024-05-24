<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\RegisteredUserController;

// PROTECTED ROUTES
Route::middleware('auth:api')->group(function () {
    // PUT /players/{id} : modifica el nom del jugador/a.
    Route::put('/players/{id}', [UserController::class, 'update']);
    // GET /players: retorna el llistat de tots els jugadors/es del sistema amb el seu percentatge mitjà d’èxits 
    Route::get('/players', [UserController::class, 'index']);
    // GET /players/{id}/games: retorna el llistat de jugades per un jugador/a.
    Route::get('/players/{id}/games', [UserController::class, 'show']);
    // POST /players/{id}/games/ : un jugador/a específic realitza una tirada dels daus.
    Route::post('/players/{id}/games', [GameController::class, 'store']);
});

// PUBLIC ROUTES
// POST /players : crea un jugador/a.
Route::post('/players', [RegisteredUserController::class, 'register']);
// POST /login : autentica un jugador/a.
Route::post('/login', [AuthenticatedUserController::class, 'login']);


/* ENDPOINTS PENDENTS:

DELETE /players/{id}/games: elimina les tirades del jugador/a.
GET /players/ranking: retorna el rànquing mitjà de tots els jugadors/es del sistema. És a dir, el percentatge mitjà d’èxits.
GET /players/ranking/loser: retorna el jugador/a amb pitjor percentatge d’èxit.
GET /players/ranking/winner: retorna el jugador/a amb millor percentatge d’èxit.
*/

// ENDPOINTS PER MILLORAR JOC:
// Route::post('/players/{id}/games/{game_id}/hit', [GameController::class, 'hit']);
// // Maybe not necessary
// Route::post('/players/{id}/games/{game_id}/stand', [GameController::class, 'stand']);
// // Dealer's turn (if he shows only one card, at the end of turn shows the other one and gets more cards if his score is less than 17)
// Route::post('/players/{id}/games/{game_id}/dealer', [GameController::class, 'dealerTurn']);