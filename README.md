# ZOOM-links

**Aplikacja do prywatnego użytku**

Kod aplikacji ZOOM-links, której zadaniem jest gromadzenie linków do spotkań w serwisie telekomunikacyjnym ZOOM ze wskazanej skrzynki mailowej.

<!-- [English README version](README.eng.md) -->

## Autor
>   **Górka Mateusz**\
>   **@maatiug**

## Spis treści
- [ZOOM-links](#zoom-links)
	- [Autor](#autor)
	- [Spis treści](#spis-treści)
	- [Uruchomienie](#uruchomienie)
		- [Przygotownie i uruchomienie](#przygotownie-i-uruchomienie)
		- [Wykorzystanie](#wykorzystanie)
	- [Uwaga](#uwaga)
	- [Dokumentacja](#dokumentacja)
	- [Funkcjonalności](#funkcjonalności)
	- [Wyjątki](#wyjątki)
	- [Specyfikacja](#specyfikacja)
	- [TODO List](#todo-list)
	- [Licencja](#licencja)

## Uruchomienie
### Przygotownie i uruchomienie
1. Odpowiednio ustawić stałe w pliku konfiguracyjnym `config.php`.
```
	- adres serwera skrzynki mailowej;
	- adres e-mail;
	- hasło do skrzynki mailowej;
	- maskę filtru adresata wiadomości;
	- z ilu ostatnich dni przetwarzać wiadomości
	...
```
2. Umieścić aplikację na komputerze obsługującym wykonywanie skrytów PHP.
3. Zadbać o odpowiednie ustawienie uprawnień plików i folderów, można posiłkować się skryptem: `init/init.sh`.
4. Dodać wykonywanie skryptu `check-mailbox.php` do wywołania w CRON.

np. poprzez dodanie rekordu w `crontab -e`:
```
*/30 * * * * /<ścieżka/do/pliku>/init/update.sh
```

Warto wykorzystać skrypt `init/update.sh`:
```
cd `dirname "$0"`
cd ..

php check-mailbox.php > last_update.html
```

### Wykorzystanie
Podgląd listy zgromadzonych zaproszeń dostępny jest na stronie generowanej przez `index.php`.

## Uwaga
- Skrypt zależnie od configuracji `config.php` może usuwać pernamentnie przetworzone wiadomości ze skrzynki!

## Dokumentacja
Pliki PHP zawierają dokumentację według standardu programu [Doxygen](http://doxygen.nl/).
Dokumentację można wygenerować poprzez polecenie `doxygen dox/Doxyfile `, wykorzystując plik konfiguracyjne `Doxyfile`.

## Funkcjonalności
Aplikacja przegląda

## Wyjątki
- Brak dostępu do odczytu lub zapisu pliku `data/invitation-list.json` lub `data/invitation-list-write.json`;
- Błąd połączenia ze skrzynką mailową;

## Specyfikacja
- Język: PHP, JS, CSS, HTML;
- Preferowany system Linux (obsługa skryptów bash i modyfikacja praw dostępu przez `chmod`);

## TODO List
- [ ] Tekstowe dodawanie zaproszeń. (inna treść, okno tekstowe copy-paste)
- [ ] Wysyłanie na discorda
- [x] Wyświetla dzień tygodnia
- [x] Wyświetla datę wygenerowania check-mailbox.php
- [ ] Interface w index.php ()
- [ ] Usuwanie minionych z pliku .json

## Licencja
Zobacz w pliku [LICENSE.md](LICENSE.md).
