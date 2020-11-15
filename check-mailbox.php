<?php
/**
 * \file check-mailbox.php
 * \brief Sprawdza i przetwarza wiadomości ze skrzynki mailowej.
 */
?>

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
	// Dodanie czasu wygenerowania pliku
	echo "<b>" . date("Y-m-d l h:i") . "</b><br/>";

	// Dołącza klasę obsługującą wiadomości
	require "class/Mailbox.php";

	///- 1. Próbuje zalogować się do skrzynki mailowej
	$mailbox = new Mailbox();

	///- 2. Filtruje, czyta wiadomości
	$emails = $mailbox->fetch_maillist();

	require "class/Invitation.php";
	require "class/SendOn.php";

	$cnt_correct = 0; 			///< Licznik poprawnych
	$cnt_saved = 0;				///< Licznik zapisanych
	$sendmgr = new SendOn();	///< Zarządca wiadomości

	if( ! empty( $emails ) ){

		// Komunikat o ilości przetowrzonych wiadomości
		echo "Przeczytano " . count( $emails ) . " wiadomości.<br/>";

		///- Czyta wiadomości i próbuje stworzyć zaproszenia
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
				///- inkrementuje licznik poprawnych
				$cnt_correct++;

				///- 3. Zapisuje wiadomości do pliku danych
				switch( $invitation->save() ){
				case 0: // Poprawnie zapisane
					///- inkrementuje licznik zapisanych
					++$cnt_saved;

					///- wysyłanie wiadomości na kanał discorda
					$sendmgr->send( $invitation );

					///- Wyświetla zapisaną wiadomość
					echo "Zapisano nowe spotkanie do pliku.<br/>";
					$invitation->display();
					break;

				case 1: //błąd
					break;

				case 2: // Akcje dla doplikatów
					$mailbox->do_action( MAIL_DO_FOR_DUPLICATE, $email );
					break;
				}

				///- 4. Postępowanie po przeczytaniu wiadomości
				$mailbox->do_action( MAIL_DO_AFTER_READ, $email );

				echo "<hr>"; // linia horyzontalna po kazdej wiadomosci
			}
		}
	}
	///- Komunikat o pustej skrzynce
	else
		echo "Pusta skrzynka.<br/>";

	///- Komunikat o zapisanych i poprawnych zaproszeniach
	echo "Zapisano $cnt_saved z $cnt_correct porawnych zaproszeń.<br/>";

	///- Usuwanie przedawnionych zaproszeń
	if( $cnt_saved > 0 )
		echo "Usunięto przedawnionych: " . Invitation::remove_passed() . "<br/>";

	/// 5. Zamyka skrzynkę
	$mailbox->expunge();
	$mailbox->close();

?>

</html>