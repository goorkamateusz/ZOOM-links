<?php
/**
 * \file mailbox_list.php
 * \brief Wyświetla listę skrzynek na poczcie i sprawdza poprawność konfiugracji poczty.
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
<body>

	Lista folderów/skrzynek:
	<br/>
	<ul>

	<?php
		// Dołącza dane konfiguracyjne
		require "../config.php";

		/// Próbuje połączyć się ze skrzynką
		$imapResource = imap_open( MAIL_MAILBOX, MAIL_ADDRESS, MAIL_PASSWORD );

		/// Jeśli błąd wyrzuca wyjątek z błędem połączenia.
		if( $imapResource === false )
		throw new Exception( imap_last_error() );

		/// Pobiera i wyświetla listę skrzynek
		$mailboxes = imap_list( $imapResource, MAIL_MAILBOX, '*' );

		foreach( $mailboxes as $box )
			echo "<li>" . str_replace( MAIL_MAILBOX, "", $box ) . "</li><br/>";
	?>

	<ul>
	<br/>

	Wybraną skrzynkę należy wpisać do stałej.

</body>
</html