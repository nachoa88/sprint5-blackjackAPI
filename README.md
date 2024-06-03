# Sprint 5 - Laravel API REST
Utilitzat `docker compose` command amb el fitxer `docker-compose.yml` i un `Dockerfile` personalitzat per crear els contenidors de PHP i Laravel i el de MySQL amb les conexions corresponents. Instal·lat Laravel amb PHP composer: `composer create-project laravel/laravel test-app`.

### Laravel MVC
## Nivell 1 - Funcionalitats bàsiques + Seguretat + Testing
1) Instal·lat passport amb `php artisan install:api --passport`i Spatie amb `composer require spatie/laravel-permission`, per ùltim he publicat la migració amb `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`. Com he escollit treballar amb UUID, s'han de fer viares modificacions, veure la documentació de Spatie.
La estructura de dades compta amb les seves migracions i seeds per poder fer una prova del funcionament de la API.
S'han definit els rols i permissos per cada endpoint. Hi ha un primer control general d'autenticació mitjançant el `middleware` i després un més específic amb `Policies`, en les quals es verifica si el usuari que vol accedir a l'endpoint té les acreditacions necessàries.

2) Testing: Modificat `phpunit.xml` per utilitzar `SQLite` per fer els tests. També s'han afegit algunes dades en `TestCase.php` per fer servir el trait `RefreshDatabase` que farà les migracions, el `$seed` i `accessToken`. La estructura dels tests serà la mateixa que els controllers (Controllers, Controllers/Auth, etc.).      

## Nivell 2 - Documentació + deploy
1) Documentada l'API amb `Swagger`, feta una configuració bàsica per definir info, server, security schema i tags. També he afegit els schemas pels models d'usuaris i games.
2) Pendent: Deploy API.

## Nivell 3 - Desplegar projecte + client front-end
1) Pendent: Producció.
2) Pendent: Front-End.


## Fet amb Blackjack enlloc de daus
#### Regles bàsiques de Blackjack:
- El joc es juga entre un crupier i un o més jugadors. Cada jugador juga individualment contra el crupier. De moment, només permetem un jugador.

- Al començament del joc, el crupier reparteix dues cartes a cada jugador i dues cartes a ell mateix (ara mateix, només es pot jugar 1 vs crupier).

- L'objectiu del joc és que el valor total de les teves cartes sigui el més proper possible a 21 sense passar-se. El valor d'una mà és la suma dels valors de les cartes individuals. Les cartes numerades (2-10) valen el seu valor facial, les cartes de figura (Sota, Reina, Rei) valen 10, i els Asos poden valer 1 o 11, el que sigui més beneficiós per al jugador.

- Si el total d'un jugador és més alt que el del crupier (o si el crupier es passa), el jugador guanya. Si el total del crupier és més alt, el jugador perd. En cas d'empat, el jugador ni guanya ni perd (és un "empat").


Característiques possibles:
- Una de les cartes del crupier es reparteix boca amunt, i l'altra es reparteix boca avall.
- Després de la repartició inicial, cada jugador té l'opció de "pedir" (agafar una altra carta) o "plantar-se" (no agafar més cartes). Els jugadors poden pedir tantes vegades com vulguin fins que es planten o es "passen" (superen 21).
- Un cop tots els jugadors han acabat els seus torns, el crupier revela la seva carta oculta i ha de pedir fins que el seu total sigui 17 o més alt.