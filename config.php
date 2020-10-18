<?php
/**
 * \file config.php
 * \brief Plik konfiguracyjny.
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

////----------------------------------------------------------------
/**
 * Czy usuwać wiadomości z zaproszeniami po ich przetworzeniu.
 */
define( "REMOVE_MAIL", false );
// define( "REMOVE_MAIL", true );


?>
