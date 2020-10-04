<!doctype html>
<html lang="pl">
<head>
   <meta charset="utf-8">
   <title>ZOOM-links-update</title>
   <meta name="Author" content="Gorka Mateusz">
   <meta name="robots" content="noindex,nofollow">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="Short icon" href=""/>
   <link rel="Stylesheet" type="text/css" href="css/main.css"/>
</head>

<?php
	/**
	 * 1. Próbuje zalogować się do skrzynki mailowej
	 */

	///- Dołacza dane konfiguracyjne
	include("config.php");

	///- Próbuje połączyć się ze skrzynką
	$imapResource = imap_open( MAIL_MAILBOX, MAIL_ADDRESS, MAIL_PASSWORD );

	///- Jeśli błąd wyrzuca wyjątek
	if( $imapResource === false )
		throw new Exception( imap_last_error() );

?>

<?php
	/**
	 * 2. Filtruje, czyta wiadomości
	 */

	/// Filtr przeszukiwania wiadomości
	$search = 'SINCE "' . date("j F Y", strtotime("-7 days")) . '"';

	///- Ładuje przefiltrowane wiadmości
	$emails = imap_search( $imapResource, $search );

	///- Czyta wiadomości i próbuje stworzyć zaproszenia
	include("class/Invitation.php");


	if( ! empty( $emails ) ){

		echo "Przetworzono " . count( $emails ) . " wiadomości.<br/>";

		foreach( $emails as $email ){

			// Pobiera nagłówek wiadomosci
			$overview = imap_fetch_overview( $imapResource, $email );
			$overview = $overview[0];

			// Filtr nagłówka
			if( preg_match( '~(@student.pwr.edu.pl)+~', $overview->from ) == 0 )
				continue;

			// Przetwarza treść wiadomości
			$message = imap_fetchbody( $imapResource, $email, 1 );
			$message = quoted_printable_decode( $message );

			// Próbuje stworzyć zaproszenie
			$invitation = new Invitation( $message );

			if( $invitation->isOK() ){

				/**
				 * 3. Wyświetla zaproszenia
				 */
				echo "<hr/>";
				$invitation->display();
				echo "<br/>";
				echo "<p>" . $message . "</p>";
				echo "<br/>";
			}
		}
	}
	else
		echo "Pusta skrzynka.<br/>";

	///- Zamyka skrzynkę
	imap_close( $imapResource );

?>

</html>