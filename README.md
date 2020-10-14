# ZOOM-links

**Aplikacja do prywatnego użytku**

Kod aplikacji ZOOM-links, której zadaniem jest gromadzenie linków do spotkań w serwisie telekomunikacyjnym ZOOM ze wskazanej skrzynki mailowej.

<!-- [English README version](README.eng.md) -->

## Autor
>   **Górka Mateusz**\
>   **@maatiug**

## Spis treści
- [ZOOM-links](#ZOOM-links)
- [Autor](#Autor)
- [Uruchomienie](#Uruchomienie)
- [Dokumentacja](#Dokumentacja)
- [Zawartość](#Zawartość)
- [Funkcjonalności](#Funkcjonalności)
- [Wyjątki](#Wyjątki)
- [Specyfikacja](#Specyfikacja)
- [Licencja](#Licencja)

## Uruchomienie
### Przygotownie i uruchomienie
1. Odpowiednio ustawić stałe w pliku konfiguracyjnym `config.php`.
2. Umieścić aplikację na komputerze obsługującym wykonywanie skrytów PHP.
3. Zadbać o odpowiednie ustawienie uprawnień plików, można posiłkować się skryptem: `init/file-premission.sh`.
4. Dodać skrypt `check-mailbox.php` do wywołania w CRON.

np. poprzez dodanie rekordu w `crontab -e`:
```
*/30 * * * * /ścieżka_do_pliku/update.sh
```
oraz poprawne skonfigurowanie `ADRES_SERWERA` w skrypcie `update.sh`.

### Wykorzystanie
Podgląd listy zgromadzonych zaproszeń dostępny jest w skrypcie `index.php`.

## Uwaga
- Skrypt domyślnie usuwa przetworzone wiadomości ze skrzynki mailowej (możliwość konfiguracji w `config.php`).

## Dokumentacja
TODO

## Funkcjonalności
Aplikacja przegląda

## Wyjątki
- Brak dostępu do odczytu lub zapisu pliku `data/invitation-list.json`;
- Błąd połączenia ze skrzynką mailową;

## Specyfikacja
- Język: PHP, JS, CSS, HTML
- Preferowany system Linux

## TODO
- [ ] Tekstowe dodawanie zaproszeń. (inna treść, okno tekstowe copy-paste)
- [ ] Wysyłanie na discorda



## Licencja
Zobacz w pliku [LICENSE.md](LICENSE.md).

