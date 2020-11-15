<?php
/**
 * \file view-mailbox.php
 * \brief Wyświetla zawartość skrzynki mailowej.
 */
?>

<!doctype html>
<html lang="pl">
<head>
   <meta charset="utf-8">
   <title>ZOOM-links-view</title>
   <meta name="Author" content="Gorka Mateusz">
   <meta name="robots" content="noindex,nofollow">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="Short icon" href=""/>
   <link rel="Stylesheet" type="text/css" href="css/main.css"/>
</head>

<?php
	/**
	 * Dodanie czasu wygenerowania pliku
	 */
	echo "<b>" . date("Y-m-d l h:i") . "</b><br/>";

	// Dołącza klasę obsługującą wiadomości
	require "class/Mailbox.php";

	///- 1. Próbuje zalogować się do skrzynki mailowej
	$mailbox = new Mailbox();

	///- 2. Filtruje, czyta wiadomości
	$emails = $mailbox->fetch_maillist();

	///- Czyta wiadomości i próbuje stworzyć zaproszenia
	require "class/Invitation.php";


	if( ! empty( $emails ) ){

		echo "Przetworzono " . count( $emails ) . " wiadomości.<br/>";
		$correct = 0;

		foreach( $emails as $email ){

			// Pobiera nagłówek wiadomosci
			$overview = $mailbox->fetch_overview( $email );

			// Filtr nagłówka
			if( preg_match( FILTR_ADRESAT, $overview->from ) == 0 )
				continue;

			// Przetwarza treść wiadomości
			$message = $mailbox->fetch_message( $email );

			// Próbuje stworzyć zaproszenie
			$invitation = new Invitation( $message, $overview );

			if( $invitation->isOK() ){

				/**
				 * 3. Wyświetla zaproszenia
				 */
				echo "<hr/>";
				echo $overview->from . "<br/>"; //temp
				$invitation->display();
				echo "<br/>";
				echo "<p>" . $message . "</p>";
				echo "<br/>";

				$correct += 1;

				//idea przycisk do usuwania wiadomości (na pewno, bezpieczeństwo, inne osoby?)
			}
		}

		echo "Poprawnych $correct z " . count( $emails ) . " wiadomości.<br/>";
	}
	else
		echo "Pusta skrzynka.<br/>";

	///- Zamyka skrzynkę
	$mailbox->close( $email );

?>

</html>