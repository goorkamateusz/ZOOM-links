<?php
header('Content-type: text/html; charset=utf-8');

/// Tablica nazw dni tygodnia po polsku według ISO-8601 (poniedziałek - 1, niedziela - 7)
define( "WEEK_DAY_NAME", [ "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela" ] );

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
	// public $date_ang;	// Data spotkania po angielsku. [Usunięto po e51af85]
	public $date;		///< Data spotkania w typie DateTime

	/**
	 * \brief Przetwarza tekst wiadomości lub konwertuje z stdClass na class Invitation.
	 * \param[in] $message  - treść wiadomości
	 * \param[in] $overview - informacje na temat wiadomości
	 *
	 */
	public function __construct( $message, $overview = NULL ){

		//idea Rozpatruje jedynie linki z jednej domeny
		//idea mozliwość odczytu wielu domen (const w config.php, rozdzielone | i explode())

		/// 1. Przetwarza treść wiadomości
		if( gettype($message) == "string" ){

			///- Znajduje link do spotkania
			$pattern = '~[a-z]+://' . ZOOM_ADDRESS_DOMAIN . '\S+~';
			preg_match( $pattern, $message, $matches );
			$this->link = urlencode( $matches[0] );

			///- Znajduje imie nazwisko prowadzącego z treści wiadomości
			$pattern = '~(CN="){1}.*("){1}~';
			preg_match( $pattern, $message, $matches );
			$this->lecturer = str_replace( ["CN=\"","\""], ['',''], $matches[0] );

			///- Odczytywanie imienia i nazwiska z pola "from"
			if( $this->lectuere == "" && $overview != NULL )
				$this->lecturer = $overview->from;

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
			$date_ang = str_replace( ["scheduled on "," will be held"], ['',''], $matches[0] );

			///- Tworzy datę spotkania w type danych DataTime
			$this->date = new DateTime( $date_ang );

		}
		/// 2. lub konwertuje z stdClass na klasę Invitation
		else{
			$this->convert( $message );
		}

	}

	/**
	 * \brief Przekształca stdClass na klasę Invitation
	 * \param[in] $std - klasa w formacie stdClass
	 */
	public function convert( $std ){
		foreach( $std as $key => $val ) $this->{$key} = $val;
	}

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
					."<a class=\"date\">$this->date_pl (". WEEK_DAY_NAME[ (int) date("N",  strtotime($this->date->date) )-1 ] . ")</a>"
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

		/// - Jeśli plik istnieje wczytuje dane
		if( file_exists( "data/invitation-list.json" ) ){

			// otwiera i wczytuje plik
			$file = fopen( "data/invitation-list.json", "r" ) or die("Błąd otwierania pliku do odczytu!");

			$json = '';
			while( ! feof( $file ) ) $json .= fgets( $file );

			fclose( $file );

			// dekoduje z JSON'a do tabeli
			$invlist = json_decode( utf8_encode($json) );

			if( json_last_error() != JSON_ERROR_NONE ){
				echo "Błąd czytania: " . json_last_error() . "<br/>" . json_last_error_msg() . "<br/>";
				return false;
			}

			foreach( $invlist as $inv )
				if( $inv->link == $this->link ) return false;
		}

		///- Tworzy nowy plik danych
		$file = fopen( "data/invitation-list-write.json", "x" ) or die("Nie mozna otworzyc pliku do zapisu. Prawdopodobnie nie został usunięty.");

		/// Do istniejących dopisuje nowe dane
		if( $invlist == NULL ) $invlist = array( $this );
		else array_push( $invlist, $this );

		$json = json_encode( $invlist, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE );

		if( json_last_error() != JSON_ERROR_NONE ){
			echo "Błąd zapisywania: " . json_last_error() . "<br/>" . json_last_error_msg() . "<br/>";
			fclose( $file );
			unlink( "data/invitation-list-write.json" );
			return false;
		}
		else
			fwrite( $file, $json );

		fclose( $file );

		///- Nadpisuje stary plik danych nowym
		rename( "data/invitation-list-write.json", "data/invitation-list.json" );

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