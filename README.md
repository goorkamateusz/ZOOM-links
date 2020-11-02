# ZOOM-links
**Aplikacja do prywatnego użytku w fazie rozwoju i testów.**

Aplikacja ZOOM-links, której zadaniem jest gromadzenie linków do spotkań w serwisie telekomunikacyjnym ZOOM ze wskazanej skrzynki mailowej.

## Autor
>   **Górka Mateusz**\
>   **@maatiug**

## Spis treści
- [ZOOM-links](#zoom-links)
	- [Autor](#autor)
	- [Spis treści](#spis-treści)
	- [Funkcjonalności](#funkcjonalności)
	- [Uruchomienie](#uruchomienie)
		- [Przygotownie i uruchomienie](#przygotownie-i-uruchomienie)
		- [Wykorzystanie](#wykorzystanie)
		- [Dodatkowe funkcjonalności](#dodatkowe-funkcjonalności)
	- [Uwaga](#uwaga)
	- [Wyjątki / Częste błędy](#wyjątki--częste-błędy)
	- [Dokumentacja](#dokumentacja)
	- [Specyfikacja](#specyfikacja)
	- [Do dalszego rozwoju:](#do-dalszego-rozwoju)
	- [Licencja](#licencja)


## Funkcjonalności
- Gromadzenie zaproszeń na spotkania na ZOOM'ie ze skrzynki mailowej;
- Wysyłanie zaproszeń na wskazane kanały Discorda;
- Usuwanie zaproszeń na spotkania na ZOOM ze skrzynki mailowej;


## Uruchomienie
### Przygotownie i uruchomienie
1. Odpowiednio ustawić stałe w pliku konfiguracyjnym `config.php`.
```
	- adres serwera skrzynki mailowej;
	- adres e-mail;
	- hasło do skrzynki mailowej;
	- maskę filtru adresata wiadomości;
	- z ilu ostatnich dni przetwarzać wiadomości;
	...
```
2. Umieścić aplikację na komputerze obsługującym wykonywanie skrytów PHP.
3. Zadbać o odpowiednie ustawienie uprawnień plików i folderów, można posiłkować się skryptem: `init/init.sh`.
4. Dodać wykonywanie skryptu `check-mailbox.php` do wywołania w cyklicznego w CRON.

Poprzez dodanie rekordu w `crontab -e`, które powoduje, że skrypt będzie wykonywane co 30min:
```
*/30 * * * * /<ścieżka/do/pliku>/init/update.sh
```

Warto wykorzystać skrypt `init/update.sh`:
```
cd `dirname "$0"`
cd ..

php check-mailbox.php > last_update.html
```
Aby ułatwić zadanie oraz mieć podgląd na wynik ostatniego wywołania.


### Wykorzystanie
Podgląd listy zgromadzonych zaproszeń dostępny jest na stronie generowanej przez `index.php`.


### Dodatkowe funkcjonalności
1. Konfiguracja automatycznego wysyłania zaproszeń na kanały Discorda. Zobacz: [Jak skonfigurować Discorda](doc/HOWTO_Discord_Config.md).


## Uwaga
- Skrypt zależnie od configuracji `config.php` może usuwać pernamentnie przetworzone wiadomości ze skrzynki!


## Wyjątki / Częste błędy
- Brak dostępu do odczytu lub zapisu pliku `data/invitation-list.json` lub `data/invitation-list-write.json`;
- Błąd połączenia ze skrzynką mailową;


## Dokumentacja
Pliki PHP, JS, Python zawierają dokumentację według standardu programu [Doxygen](http://doxygen.nl/).
Dokumentację można wygenerować poprzez polecenie `doxygen dox/Doxyfile `, wykorzystując plik konfiguracyjne `Doxyfile`.


## Specyfikacja
- Język: PHP, JS, CSS, HTML;
- Preferowany system Linux (obsługa skryptów bash i modyfikacja praw dostępu przez `chmod`);


## Do dalszego rozwoju:
- [ ] Tekstowe dodawanie zaproszeń. (inna treść, okno tekstowe copy-paste)
- [x] Wysyłanie wiadomości na Discordzie
- [x] Wyświetla dzień tygodnia
- [x] Wyświetla datę wygenerowania check-mailbox.php
- [ ] Interface w index.php ()
- [ ] Usuwanie minionych spotkań z plików .json
- [ ] Przenoszenie maili do kosza, zamaist usuwania
- [ ] Skrypt do testowania konfiguracji skrzynki

Całość listy ToDo do wygenerowania z komentarzy kodu.


## Licencja
Zobacz w pliku [LICENSE.md](LICENSE.md).
