# Sprint 5 - Laravel API REST
Utilitzat `docker compose` command amb el fitxer `docker-compose.yml` i un `Dockerfile` personalitzat per crear els contenidors de PHP i Laravel i el de MySQL amb les conexions corresponents. Instal·lat Laravel amb PHP composer: `composer create-project laravel/laravel test-app`.

### Laravel MVC
## Nivell 1 - Funcionalitats bàsiques + Seguretat + Testing
1) Instal·lat passport amb `php artisan install:api --passport`i Spatie amb `composer require spatie/laravel-permission`, per ùltim he publicat la migració amb `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`. Com he escollit treballar amb UUID, s'han de fer viares modificacions, ver la documentació de Spatie de com s'ha de fer. 

2) ... Per fer: Testing

## Nivell 2 - Documentació + deploy
1) ... Per fer: Documentació API.
2) ... Per fer: Deploy API.

## Nivell 3 - Desplegar projecte + client front-end
1) ...


## Possibilitat de fer Blackjack (o per projecte futur)
#### Basic rules of Blackjack:
- The game is played between a dealer and one or more players. Each player plays individually against the dealer.

- At the start of the game, the dealer deals two cards to each player and two cards to themselves. One of the dealer's cards is dealt face up, and the other is dealt face down.

- The goal of the game is to have the total value of your cards be as close to 21 as possible without going over. The value of a hand is the sum of the values of the individual cards. Numbered cards (2-10) are worth their face value, face cards (Jack, Queen, King) are worth 10, and Aces can be worth either 1 or 11, whichever is more beneficial to the player.

- After the initial deal, each player has the option to "hit" (take another card) or "stand" (take no more cards). Players can hit as many times as they want until they either stand or "bust" (go over 21).

- Once all players have finished their turns, the dealer reveals their hidden card and must hit until their total is 17 or higher.

- If a player's total is higher than the dealer's (or if the dealer busts), the player wins. If the dealer's total is higher, the player loses. In case of a tie, the player neither wins nor loses (it's a "push").

Models: Deck (Ace can be 1 or 11), Game, User.
Endpoints: start, hit, stand. (GameController).