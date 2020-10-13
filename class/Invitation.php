<?php
header('Content-type: text/html; charset=utf-8');

/**
 * \class Invitation
 * \brief Nadzoruje danymi zaproszenia
 */
class Invitation {
	public $link;		///< Link do spotkania
	public $password;	///< Hasło do spotkania
	public $title;		///< Tytuł spotkania
	public $lecturer;	///< Prowadzący
	public $date_pl; 	///< Data spotkania po polsku
	public $date_ang;	///< Data spotkania po angielsku
	public $date;		///< Data spotkania w typie DateTime

	/**
	 * \brief Przetwarza tekst wiadomości lub konwertuje z stdClass na class Invitation.
	 * \param[in] message - treść wiadomości
	 *
	 * Rozpatruje jedynie linki z domeny pwr-edu.zoom.us
	 */
	function __construct( $message ){

		/// 1. Przetwarza treść wiadomości
		if( gettype($message) == "string" ){

			///- Znajduje link do spotkania
			$pattern = '~[a-z]+://pwr-edu.zoom.us\S+~';
			preg_match( $pattern, $message, $matches );
			$this->link = urlencode( $matches[0] );

			///- Znajduje imie nazwisko prowadzącego
			$pattern = '~(CN="){1}.*("){1}~';
			preg_match( $pattern, $message, $matches );
			$this->lecturer = str_replace( ["CN=\"","\""], ['',''], $matches[0] );

			//todo odczytywanie prowadzącego z adresu mail, jeśli nie ma CN

			///- Znajduje haslo do spotkania
			$pattern = '~(Password: )\S+~';
			preg_match( $pattern, $message, $matches );
			$this->password = str_replace( ["Password: "], [''], $matches[0] );

			///- Znajduje tytuł spotkania
			$pattern = '~(Classes on ).*(scheduled on)+~';
			preg_match( $pattern, $message, $matches );
			$this->title = str_replace( ["Classes on "," scheduled on"], ['',''], $matches[0] );

			///- Znajduje datę spotkania (po polsku)
			$pattern = '~(terminie ).*(odbędą)+~';
			preg_match( $pattern, $message, $matches );
			$this->date_pl = str_replace( ["terminie "," odbędą"], ['',''], $matches[0] );

			///- Znajduje datę spotkania (po angielsku)
			$pattern = '~(scheduled on ).*(will be held)+~';
			preg_match( $pattern, $message, $matches );
			$this->date_ang = str_replace( ["scheduled on "," will be held"], ['',''], $matches[0] );

			///- Tworzy datę spotkania w type danych DataTime
			$this->date = new DateTime( $this->date_ang );

		}
		/// 2. Lub konwertuje z stdClass na Invitation
		else {
			foreach( $message as $key => $val ) $this->{$key} = $val;
		}
	}

	/**
	 * \brief
	 */
	//todo Konstruktor biorący pod uwagę nagłówek wiadomości



	/**
	 * \brief Wyświetla podgląd danych zaproszenia
	 */
	public function display(){
		echo "Spotkanie $this->title | $this->date_pl<br/>";
		echo "| Prowadzący: $this->lecturer<br/>";
		echo "| Link: <a href=\"". urldecode($this->link) ."\">". urldecode($this->link) ."</a></br>";
		echo "| Hasło: $this->password<br/>";
	}

	/**
	 * \brief Zwraca zawartość div'a z zaproszeniem.
	 * \return html
	 */
	public function html(){
		return	 "<div>"
					."<a class=\"date\">$this->date_pl</a>"
					."<a class=\"title\">$this->title</a>"
				."</div>"
				."<span>Prowadzący: <a class=\"lect\">$this->lecturer</a></span>"
				."<span>Link: <a href=\"". urldecode($this->link) ."\">". urldecode($this->link) ."</a></span>"
				."<span>Hasło: <a class=\"passwd\">$this->password</a></span>";
	}

	/**
	 * \brief Zwraca informację, czy zaproszenie jest kompletne.
	 * \retval true  - Zaproszenie zawiera link
	 * \retval false - Zaproszenie nie jest kompletne
	 */
	public function isOK(){
		if( $this->link == "" ) return false;
		else return true;
	}

	/**
	 * \brief Zapisuje do pliku, jeśli spotkanie jeszcze nie zostało zapisane.
	 * Dane trzymane są w pliku `dane/invitation-list.json`.
	 *
	 * \retval true  - zakończone sukcesem
	 * \retval false - nie powodzenie lub zaproszenie istnieje już w pliku
	 */
	public function save(){

		$invlist = NULL;

		/// Czy plik istnieje, jesli nie tworzy go
		if( file_exists( "data/invitation-list.json" ) ){

			/// Jeśli plik danych istnieje, sprawdzamy czy link jest już na liście
			$file = fopen( "data/invitation-list.json", "r" );

			$json = '';
			while( ! feof( $file ) ) $json .= fgets( $file );

			fclose( $file );

			$invlist = json_decode( utf8_encode($json) );

			if( json_last_error() != JSON_ERROR_NONE ){
				echo "Błąd czytania: " . json_last_error() . "<br/>" . json_last_error_msg() . "<br/>";
				return false;
			}

			foreach( $invlist as $inv )
				if( $inv->link == $this->link ) return false;
		}

		///- Dodaje nowy wpis do pliku danych (lub tworzy go)
		$file = fopen( "data/invitation-list-2.json", "x" ) or die("Nie mozna otworzyc pliku do zapisu.");

		if( $invlist == NULL ) $invlist = array( $this );
		else array_push( $invlist, $this );

		$json = json_encode( $invlist, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE );

		if( json_last_error() != JSON_ERROR_NONE ){
			echo "Błąd zapisywania: " . json_last_error() . "<br/>" . json_last_error_msg() . "<br/>";
			fclose( $file );
			unlink( "data/invitation-list-2.json" );
			return false;
		}
		else
			fwrite( $file, $json );

		fclose( $file );

		rename( "data/invitation-list-2.json", "data/invitation-list.json" );

		//fixme Zdażyło się, że skrypt usunął bazę danych...
		return true;
	}

	/**
	 * \brief Pobiera z pliku danych zaproszenia.
	 * \return Tablicę spotkań z pliku
	 *
	 * \exception
	 * 	- Nie można otrzymać pliku do odczytu!
	 */
	public static function load(){
		///- Otwiera plik do odczytu i odczytuje JSON'a
		$file = fopen( "data/invitation-list.json", "r" ) or die ("Nie można otworzyć pliku do odczytu!");

		$json = "";
		while( ! feof( $file ) ) $json .= fgets( $file );

		fclose( $file );
		///- Zwraca tabelę zaproszeń z pliku
		return json_decode( utf8_encode($json) );
	}

};

?>