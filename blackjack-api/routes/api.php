<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RankingController;

// PROTECTED ROUTES
Route::middleware('auth:api')->group(function () {
    // ACCESSED BY PLAYER:
    // PUT /players/{id} : modifica el nom del jugador/a.
    Route::put('/players/{id}', [UserController::class, 'update']);
    // GET /players/{id}/games: retorna el llistat de jugades per un jugador/a.
    Route::get('/players/{id}/games', [GameController::class, 'show']);
    // POST /players/{id}/games/ : un jugador/a específic comença una partida.
    Route::post('/players/{id}/games', [GameController::class, 'store']);
    // DELETE /players/{id}/games: elimina les tirades del jugador/a.
    Route::delete('/players/{id}/games', [GameController::class, 'destroyAll']);

    // ACCESSED BY MODERATOR & SUPER-ADMIN:
    // GET /players: retorna el llistat de tots els jugadors/es del sistema amb el seu percentatge mitjà d’èxits 
    Route::get('/players', [UserController::class, 'getAll']);
    // DELETE /players/{id}: elimina un jugador/a del sistema.
    Route::delete('/players/{id}', [UserController::class, 'destroy']);
});

// PUBLIC ROUTES
// POST /players : crea un jugador/a.
Route::post('/players', [RegisteredUserController::class, 'register']);
// POST /login : autentica un jugador/a.
Route::post('/login', [AuthenticatedUserController::class, 'login']);
// GET /players/ranking: retorna el rànquing mitjà de tots els jugadors/es del sistema. És a dir, el percentatge mitjà d’èxits.
Route::get('/players/ranking', [RankingController::class, 'ranking']);
// GET /players/ranking/loser: retorna el jugador/a amb pitjor percentatge d’èxit.
Route::get('/players/ranking/loser', [RankingController::class, 'worstPlayer']);
// GET /players/ranking/winner: retorna el jugador/a amb millor percentatge d’èxit.
Route::get('/players/ranking/winner', [RankingController::class, 'bestPlayer']);


// ENDPOINTS PER MILLORAR JOC:
// Route::post('/players/{id}/games/{game_id}/hit', [GameController::class, 'hit']);
// // Maybe not necessary
// Route::post('/players/{id}/games/{game_id}/stand', [GameController::class, 'stand']);
// // Dealer's turn (if he shows only one card, at the end of turn shows the other one and gets more cards if his score is less than 17)
// Route::post('/players/{id}/games/{game_id}/dealer', [GameController::class, 'dealerTurn']);