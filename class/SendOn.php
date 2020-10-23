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
	private $discord_channel = null;

	/**
	 * \brief Importuje plik DISCORD_CHANNEL_FILE
	 */
	function imprort_once(){
		///- Wykonje sie tylko jeśli $discord_channel == null
		if( $discord_channel == null ){

			///- Wczytywanie zawartości pliku
			if( ! file_exists( DISCORD_CHANNEL_JSON ) )
				throw Exception("Konfiguracja kanałów discorda nie istnieje");

			$file = fopen( DISCORD_CHANNEL_JSON, "r" )
				or die("Błąd otwierania pliku DISCORD_CHANNEL_JSON");

			$json = '';
			while( ! feof( $file ) ) $json .= fgets( $file );

			fclose( $file );

			///- Dekodowanie JSON'a
			$this->discord_channel = json_decode( utf8_encode($json), JSON_OBJECT_AS_ARRA );

			if( json_last_error() != JSON_ERROR_NONE )
				throw Exception( json_last_error_msg() );

			//todo test
			print_r( $this->discord_channel );

		}
	}


	/**
	 * \brief Funkcja zarządza wysyłką wiadomości
	 *
	 * todo:
	 * - return ?
	 *
	 */
	function send( $invitation ){


	}



};




?>
