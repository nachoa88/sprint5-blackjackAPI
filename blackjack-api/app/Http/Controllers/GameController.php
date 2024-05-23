<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request...

        // // Create a new game.
        // $game = new Game;

        // // Deal two cards to the player and the dealer.
        // $game->player_hand = $this->dealCards(2);
        // $game->dealer_hand = $this->dealCards(2);

        // // Calculate the scores.
        // $game->player_score = $this->calculateScore($game->player_hand);
        // $game->dealer_score = $this->calculateScore($game->dealer_hand);

        // // Determine the result of the game.
        // $game->result = $this->determineResult($game->player_score, $game->dealer_score);

        // // Save the game.
        // $game->save();

        // return response()->json($game, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
