# Konfiguracja wysyłania na Discord

## Plik konfiguracyjny
W pliku `config.php` istnieje możliwość skonfigurowania domyślnego kanału `DEAFULT_DISCORD` na, który mają zostać wysłane wiadomości.

- W przypadku, gdy dane zaproszenie nie spełnia żadnych z kryteriów w pliku `discord-channel.json` lub ten plik nie istnieje zostanie wysłane na domyślny kanał.
- Jeżeli `DEFAULT_DISCORD` zdefiniujemy na `""`, pusty string, takie wiadomości nie będą wysyłane nigdzie.


## Plik zawierający webhooki kanałów
W głównym katalogu projektu musi być utworzony plik `discord-channel.json`.


### Szybkie tworzenie pliku
**Do tworzenia pliku został przygotowany skrypt `admin/discord-json.py`.**

1. Znajdując się w katalogu `admin` należy wykonać polecenie:
```
	python discord-json.py;
```

Skrypt na podstawie już istniejących zaproszeń w `data/invitation-list.json` tworzy plik do konfiguracji adresów webhook.

2. Uzupełnić utworzony plik według poniższych zasad.


### Budowa pliku
```json
[
	{  
		"lect":"imie nazwisko",
		"term": [
			{
				"hour":"godzina_spotkania",
				"day":"nr_dnia_tygodnia",
				"week":"parzystosc_tygodnia",
				"disc":"webhook_terminu"
			}
			(...)
		],
		"disc":"wekbook_prowadzacego"
	},
	(...)
]
```

| Format danych			||
|:---------------------:|:-|
| imie i nazwisko		| Imie i nazwisko prowadzącego, dokładnie tak zapisane jak adresat wiadomości
| godzina_spotkania		| HH:MM - H w formei 24 godzinnej
| nr_dnia_tygodnia		| N - poniedziałek - 1, niedziela - 7
| parzystosc_tygodnia	| "P" - parzysty, "N" - nie parzysty
| webhook_terminu 		| cały link lub pusty string ""
| webhook_prowadzacego	| cały link lub pusty string ""

- Elementy w tablicy "term" są opcjonalne;
- Podanie pustego stringu spowoduje informację o braku linku, ale nie błąd;
- Dublowanie terminów powoduje wzięcie 1. w kolejce rekordu;


### Przykład
```json
[
{  "lect":"Miss Randi Grimes",
   "term":[
     {"hour":"10:00" ,"day":"2", "week":"N", "disc":"https://discord.com/api/webhooks/...kod..."}
    ],
   "disc":""
},
{  "lect":"Caroline Nitzsche",
   "term":[
     {"hour":"15:15" ,"day":"3", "week":"N", "disc":"https://discord.com/api/webhooks/...kod..."},
     {"hour":"11:15" ,"day":"5", "week":"N", "disc":"https://discord.com/...resztaadresu..."},
     {"hour":"11:15" ,"day":"5", "week":"P", "disc":"https://discord.com/...resztaadresu..."}
    ],
   "disc":""
}
]
```

Wróć do [README](../README.md)