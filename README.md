# Sprint 5 - Laravel API REST
Utilitzat `docker compose` command amb el fitxer `docker-compose.yml` i un `Dockerfile` personalitzat per crear els contenidors de PHP i Laravel i el de MySQL amb les conexions corresponents. Instal·lat Laravel amb PHP composer: `composer create-project laravel/laravel test-app`.

### Laravel MVC
## Nivell 1 - Funcionalitats bàsiques + Seguretat + Testing
1) Instal·lat passport amb `php artisan install:api --passport`i Spatie amb `composer require spatie/laravel-permission`, per ùltim he publicat la migració amb `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`. Com he escollit treballar amb UUID, s'han de fer viares modificacions, veure la documentació de Spatie.
La estructura de dades compta amb les seves migracions i seeds per poder fer una prova del funcionament de la API.
S'han definit els rols i permissos per cada endpoint. Hi ha un primer control general d'autenticació mitjançant el `middleware` i després un més específic amb `Policies`, en les quals es verifica si el usuari que vol accedir a l'endpoint té les acreditacions necessàries.

2) Testing: Modificat `phpunit.xml` per utilitzar `SQLite` per fer els tests. També s'han afegit algunes dades en `TestCase.php` per fer servir el trait `RefreshDatabase` que farà les migracions, el `$seed` i `accessToken`. La estructura dels tests serà la mateixa que els controllers (Controllers, Controllers/Auth, etc.).      

## Nivell 2 - Documentació + deploy
1) ... Per fer: Documentació API.
2) ... Per fer: Deploy API.

## Nivell 3 - Desplegar projecte + client front-end
1) ...


## Fet amb Blackjack enlloc de daus
#### Basic rules of Blackjack:
- The game is played between a dealer and one or more players. Each player plays individually against the dealer. For now, we allow only one player.

- At the start of the game, the dealer deals two cards to each player and two cards to themselves. 

- The goal of the game is to have the total value of your cards be as close to 21 as possible without going over. The value of a hand is the sum of the values of the individual cards. Numbered cards (2-10) are worth their face value, face cards (Jack, Queen, King) are worth 10, and Aces can be worth either 1 or 11, whichever is more beneficial to the player.

- If a player's total is higher than the dealer's (or if the dealer busts), the player wins. If the dealer's total is higher, the player loses. In case of a tie, the player neither wins nor loses (it's a "push").


Possible features: 
- One of the dealer's cards is dealt face up, and the other is dealt face down.
- After the initial deal, each player has the option to "hit" (take another card) or "stand" (take no more cards). Players can hit as many times as they want until they either stand or "bust" (go over 21).
- Once all players have finished their turns, the dealer reveals their hidden card and must hit until their total is 17 or higher.