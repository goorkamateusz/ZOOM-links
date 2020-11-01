<?php
/*
 * UWAGA: Plik nie śledzony w repozytorium!
 * aby włączyć śledzenie:
 * git update-index --no-assume-unchanged config.php
 */

////----------------------------------------------------------------
/**
 * Twoja skrzynka mailowa
 */
define( "MAIL_MAILBOX", "{adres_twojej_skrzynki_mailowej:143/notls}" );

/**
 * Twój adres e-mail
 */
define( "MAIL_ADDRESS", "twoj_adres@email.pl" );

/**
 * Twoje hasło do skrzynki
 */
define( "MAIL_PASSWORD", "Twoje_haslo1234");

////----------------------------------------------------------------
/**
 * Maska adresata wiadomości do filtracji
 */
define( "FILTR_ADRESAT", "~(pwr.edu.pl)+~" );

/**
 * Z ilu ostatnich dni przetwarzać wiadomości
 */
define( "LAST_DAYS", "7" );

/**
 * Domena adresów zoom
 */
define( "ZOOM_ADDRESS_DOMAIN", "pwr-edu.zoom.us" );

////----------------------------------------------------------------
/**
 * Czy usuwać wiadomości z zaproszeniami po ich przetworzeniu.
 */
// define( "REMOVE_MAIL", true );
define( "REMOVE_MAIL", false );

////----------------------------------------------------------------
/**
 * Domyślny kanał na discordzie.
 * Pusty string "", jeśli nie chcemy wysyłać wiadmości na domyślny kanał.
 */
define( "DISCORD_DEFAULT", "" );

?>
