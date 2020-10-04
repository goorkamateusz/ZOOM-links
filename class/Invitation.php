<?php

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
	 * \brief Konstruktor parametryczny; przetwarza tekst wiadomości lub konwertuje z stdClass na class Invitation.
	 * \param[in] message - treść wiadomości
	 */
	function __construct( $message ){

		/// 1. Przetwarza treść wiadomości
		if( gettype($message) == "string" ){

			/// Znajduje link do spotkania
			$pattern = '~[a-z]+://pwr-edu.zoom.us\S+~';
			preg_match( $pattern, $message, $matches );
			$this->link = $matches[0];

			/// Znajduje imie nazwisko prowadzącego
			$pattern = '~(CN="){1}.*("){1}~';
			preg_match( $pattern, $message, $matches );
			$this->lecturer = str_replace( ["CN=\"","\""], ['',''], $matches[0] );

			/// Znajduje haslo do spotkania
			$pattern = '~(Password: )\S+~';
			preg_match( $pattern, $message, $matches );
			$this->password = str_replace( ["Password: "], [''], $matches[0] );

			/// Znajduje tytuł spotkania
			$pattern = '~(Classes on ).*(scheduled on)+~';
			preg_match( $pattern, $message, $matches );
			$this->title = str_replace( ["Classes on "," scheduled on"], ['',''], $matches[0] );

			/// Znajduje datę spotkania (po polsku)
			$pattern = '~(terminie ).*(odbędą)+~';
			preg_match( $pattern, $message, $matches );
			$this->date_pl = str_replace( ["terminie "," odbędą"], ['',''], $matches[0] );

			/// Znajduje datę spotkania (po angielsku)
			$pattern = '~(scheduled on ).*(will be held)+~';
			preg_match( $pattern, $message, $matches );
			$this->date_ang = str_replace( ["scheduled on "," will be held"], ['',''], $matches[0] );

			/// Tworzy datę spotkania w type danych DataTime
			$this->date = new DateTime( $this->date_ang );

		}
		/// 2. Lub konwertuje z stdClass na Invitation
		else {
			foreach( $message as $key => $val ) $this->{$key} = $val;
		}
	}

	/**
	 * Wyświetla dane zaproaszenia
	 */
	public function display(){
		echo "Spotkanie $this->title | $this->date_pl<br/>";
		echo "| Prowadzący: $this->lecturer<br/>";
		echo "| Link: <a href=\"$this->link\">$this->link</a></br>";
		echo "| Hasło: $this->password<br/>";
	}

	/**
	 * Zwraca div html zaproszenia
	 */
	public function html(){
		return	 "<div>"
					."<a class=\"date\">$this->date_pl</a>"
					."<a class=\"title\">$this->title</a>"
				."</div>"
				."Prowadzący: <a>$this->lecturer</a><br/>"
				."Link: <a href=\"$this->link\">$this->link</a><br/>"
				."Hasło: <a class=\"passwd\">$this->password</a>";
	}

	/**
	 * Zwraca informację, czy zaproszenie jest kompletne.
	 */
	public function isOK(){
		if( $this->link == "" ) return false;
		else return true;
	}

	/**
	 * Zapisuje do pliku, jeśli spotkanie jeszcze nie zostało zapisane
	 * \retval true  - zakończone sukcesem
	 * \retval false - nie powodzenie lub zaproszenie istnieje już w pliku
	 */
	public function save(){
		///- Sprawdza czy wpis już nie istnieje
		// Czy udało się otworzyć plik do czytania
		$invlist = NULL;

		/// Jeśli plik istnieje, sprawdzamy czy link jest już na liście
		if( $file = @fopen( "data/invitation-list.json", "r" ) ){

			$json = '';
			while( ! feof( $file ) ) $json .= fgets( $file );

			$invlist = json_decode( $json );

			foreach( $invlist as $inv )
				if( $inv->link == $this->link ) return false;

			fclose( $file );
		}

		///- Dodaje nowy wpis do pliku (lub tworzy go)
		$file = fopen( "data/invitation-list.json", "w" ) or die("Nie mozna otworzyc pliku do zapisu.");

		if( $invlist == NULL ) $invlist = array( $this );
		else array_push( $invlist, $this );

		fwrite( $file, json_encode( $invlist ) );

		fclose( $file );

		return true;
	}

	/**
	 * Pobiera z pliku danych zaproszenia
	 * \return Tablicę spotkań z pliku
	 */
	public static function load(){
		///- Otwiera plik do odczytu i odczytuje JSON'a
		$file = fopen( "data/invitation-list.json", "r" ) or die ("Nie można otworzyć pliku do odczytu!");

		$json = "";
		while( ! feof( $file ) ) $json .= fgets( $file );

		fclose( $file );
		///- Zwraca tabelę zaproszeń z pliku
		return json_decode( $json );
	}

};


?>