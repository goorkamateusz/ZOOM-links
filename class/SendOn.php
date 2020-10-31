<?php

/**
 * \file SendOn.php
 * \brief Plik zajmuje się wysyłaniem zaproszeń na następne kanały
 *
 * Obsługuje:
 * 	- wysyłka na wybrane kanały Discorda
 *
 */

/// Nazwa pliku zawierająca tablicę filtrów i kanałów wysyłki
define( "DISCORD_CHANNEL_JSON", "discord-channel.json" );


class SendOn {

	/**
	 * \brief Struktura zawierająca adresy kanałów zaimportowane z DISCORD_CHANNEL_JSON
	 *
	 */
	private $discord_channels = null;

	/**
	 * \brief Importuje plik DISCORD_CHANNEL_FILE
	 */
	public function import_once(){
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

			//todo test
			print_r( $this->discord_channels );
		}
	}


	/**
	 * \brief Funkcja zarządza wysyłką wiadomości
	 *
	 * todo:
	 * - return ?
	 *
	 */
	public function send( $invitation ){

		echo "<br/>";
		echo "link:|" . $this->find_channel( $invitation ) . "|";
		echo "<br/>";

	}


	/**
	 * \brief Funkcja znajduje kanał na podstawie zaproszenia
	 * \return string
	 * \retval "" pusty string, jeśli nie znaleziono kanału
	 * \retval link do kanału (kodownaie?)
	 */
	private function find_channel( $invitation ){

		foreach( $this->discord_channels as $lect )
			if( $lect->lect == $invitation->lecturer ){

				//todo jeden link dla kazdego ze spotkan prowadzacego
				echo $lect->lect . "<br/>";

				foreach( $lect->term as $term )
					if( $this->same_term( $term, $invitation->date->date ) ){
						return $term->disc;
					}
			}
		return "";
	}

	/**
	 * \brief Czy termin z term i date są te same?
	 * \retval true  - tak
	 * \retval false - nie
	 */
	private function same_term( $term, $st ){

		// Wczytanie daty ze stringu
		$date = mktime($st[11].$st[12], $st[14].$st[15], $st[17].$st[18], $st[5].$st[6], $st[8].$st[9], $st[0].$st[1].$st[2].$st[3] );

		// Sprawdzenie warunkow
		if(  (int) date("N", $date ) 						!= $term->day  ) return false;
		if(  ( ((int) date("W", $date ) )%2 ? "N" : "P" )	!= $term->week ) return false;
		if(  date("h:i", $date ) 							!= $term->hour ) return false;

		return true;
	}



};




?>
