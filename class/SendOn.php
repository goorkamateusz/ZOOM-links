<?php
/**
 * \file SendOn.php
 * \brief Plik zajmuje się wysyłaniem zaproszeń na następne kanały
 *
 * Obsługuje:
 * 	- wysyłka na wybrane kanały Discorda
 *
 */

/// Załadowanie agorlov/discordmsg
require_once 'discordmsg/Msg.php';
require_once 'discordmsg/DiscordMsg.php';

/// Załadowanie konfiguracji
require_once 'config.php';

/// Nazwa pliku zawierająca tablicę filtrów i kanałów wysyłki
define( "DISCORD_CHANNEL_JSON", "discord-channel.json" );

/**
 * \brief Klasa zarządzająca wysyłaniem wiadomości
 * Obsługuje:
 * 	- kanały na Discord
 *
 * Wykorzystuje:
 * 	agorlov/discordmsg
 * 	Autorstwa: Alexandr Gorlov
 * 	[https://github.com/agorlov/discordmsg](https://github.com/agorlov/discordmsg)
 *
 */
class SendOn {

	/**
	 * \brief Struktura zawierająca adresy kanałów zaimportowane z DISCORD_CHANNEL_JSON
	 * Inicjowana przez import_once() wywoływanym przez konstruktor.
	 */
	private $discord_channels = null;

	/**
	 * \brief Konstruktor SendOn
	 */
	public function __construct(){
		///- Inicjuje $discord_channels
		$this->import_once();
	}

	/**
	 * \brief Wysyla wiadomosc na kanal zgodny z zaproszeniem.
	 *
	 * Do generowania pliku discord-channel.json służy skrypt admin/discord-json.py
	 *
	 * \post Wymaga inicjalizacji $discord_channels przez konstruktor
	 * \param $invitation - zaproszenie, klasy Invitation
	 */
	public function send( $invitation ){

		// Znajduje link
		$link = $this->find_channel( $invitation ) ?? DISCORD_DEFAULT ;

		if( $link != "" ){

			// Wysyla wiadomosc
			try {
				(new \AG\DiscordMsg(
					$invitation->message(),					// wiadomosc
					$link,									// discord webhook link
					$invitation->lecturer,					// bot name
					'' 										// avatar url
				))->send();

			}
			catch( Exception $e ) {
				echo "Błąd disdord:<br><b>$e</b><br>";
			}
		}
		else {
			echo "Brak linku do kanału.<br>";
		}

	}

	/**
	 * \brief Importuje plik DISCORD_CHANNEL_FILE.
	 * Inicjuje $discord_channels
	 */
	private function import_once(){
		///- Wykonje sie tylko jeśli $discord_channels == null
		if( $this->discord_channels == null ){

			///- Wczytywanie zawartości pliku
			if( ! file_exists( DISCORD_CHANNEL_JSON ) )
				throw new Exception("Konfiguracja kanałów discorda nie istnieje");

			$file = fopen( DISCORD_CHANNEL_JSON, "r" )
				or die("Błąd otwierania pliku DISCORD_CHANNEL_JSON");

			$json = '';
			while( ! feof( $file ) ) $json .= fgets( $file );

			fclose( $file );

			///- Dekodowanie JSON'a
			$this->discord_channels = json_decode( utf8_encode($json) );

			if( json_last_error() != JSON_ERROR_NONE )
				throw new Exception( json_last_error_msg() );
		}
	}

	/**
	 * \brief Funkcja znajduje kanał na podstawie zaproszenia
	 * \return string|null
	 * \retval null   - jeśli nie znaleziono kanału
	 * \retval string - link do kanału (kodownaie?)
	 */
	private function find_channel( $invitation ){

		foreach( $this->discord_channels as $lect )
			if( $lect->lect == $invitation->lecturer ){

				//todo jeden kanał dla kazdego ze spotkan prowadzacego

				foreach( $lect->term as $term )
					if( $this->same_term( $term, $invitation->date->date ) ){
						return $term->disc == "" ? null : $term->disc;
					}
			}
		return null;
	}

	/**
	 * \brief Czy termin z date zawiera się w filtrze term?
	 * \retval true  - tak
	 * \retval false - nie
	 */
	private function same_term( $term, $st ){

		// Wczytanie daty ze stringu
		$date = mktime($st[11].$st[12], $st[14].$st[15], $st[17].$st[18], $st[5].$st[6], $st[8].$st[9], $st[0].$st[1].$st[2].$st[3] );

		// echo "<br>$term->week, $term->day, $term->hour    ???     " . date("Y:m:d h:i",$date) . "    ->     ";
		// echo  (int) date("N", $date ) 						!= $term->day  ? 'F' : 'T' ;
		// echo  ( ((int) date("W", $date ) )%2 ? "N" : "P" )	!= $term->week ? 'F' : 'T' ;
		// echo  date("H:i", $date ) 							!= $term->hour ? 'F' : 'T' ;

		// Sprawdzenie warunkow
		if(  (int) date("N", $date ) 						!= $term->day  ) return false;
		if(  ( ((int) date("W", $date ) )%2 ? "N" : "P" )	!= $term->week ) return false;
		if(  date("H:i", $date ) 							!= $term->hour ) return false;

		return true;
	}

};

?>