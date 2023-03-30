# Dominik Dziewulski
## _Aplikacja do wyliczania składek AC/OC_

Komendy do uruchomienia aplikacji

```sh
composer install
./vendor/bin/sail up -d
./vendor/bin/sail shell
npm install
npx run watch
```

## Opis aplikacji

Aplikacja służy do obliczania składek OC/AC dla przedsiębiorców oraz klientów mobilnych, narzędzie zostało 
napisane dla firmy VEHICS z wykorzystaniem frameworka Laravel 10 Oraz VueJS w wersji 3. Całość obliczeń
jest wykonywana po stronie back-endowej, komunikacja z serwerem odbywa się poprzez komunikację z REST API (po
stronie front-endowej wykorzystywana jest biblioteka AXIOS).

## Struktura aplikacji

Pliki odpowiedzialne za warstwę Front-endową znajdują się w resources/js (główny rdzeń odpowiadający za rendering)
aplikacji VueJS, komponent głównego widoku kalkulatora składek OC/AC znajduje się w resources/vueApp/components.
Z kolei warstwa back-endowa - praktycznie prawie cała logika obliczeniowa została wyniesiona z kontrolera do folderu
Services/AcOcCalcService.php, w tym miejscu znajduje się cały algorytm obliczeń, 
z kolei Controllers/CalculationsController.php odpowiada tylko i wyłącznie za przekazywanie danych do serwisów
oraz zwracania danych do warstwy front-endowej. Kontroler korzysta także z CalculateAcOcRequest.php czyli klasy
walidacyjnej odpowiadającej za sprawdzanie poprawności jak i integralności danych związanymi z wymaganymi założeniami.