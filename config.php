<?php
/**
 * \brief Plik konfiguracyjny aplikacji
 *
 * UWAGA: Plik nie śledzony w repozytorium!
 * aby wyłączyć: git update-index --no-assume-changed config.php
 */

////----------------------------------------------------------------
/**
 * Adres serwera Twojej skrzynki mailowej
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
define( "FILTR_ADRESAT", "~(edu.pl)+~" );

/**
 * Z ilu ostatnich dni przetwarzać wiadomości
 */
define( "LAST_DAYS", "7" );

/**
 * Domena adresów zoom
 */
define( "ZOOM_ADDRESS_DOMAIN", "zoom.us" );

////----------------------------------------------------------------
/**
 * Określa co aplikacja ma zrobić z przeczytanymi wiadomościami zawierającymi zaproszenia.
 * - Aplikacja przetwarza tylko wiadomości zakwalifikowane, jako poprawne zaproszenia ZOOM.
 * \see Invitation::isOK()
 * \see MAIL_TARGET_FOLDER
 *
 * *Możliwe wartości:*
 *	0	- Pozostaw wiadomości w skrzynce obiorczej;
 *	1	- Przenieś wiadomości do folderu MAIL_TARGET_FOLDER;
 *	2 	- Usuń pernamentnie wiadomości;
 *
 * Domyślnie: 0
 */
define( "MAIL_DO_AFTER_READ", 0 );

/**
 * Określa co aplikacja ma zrobić z wiadomościami zwierającymi duplikujące się zaproszenia.
 * \see Invitation::save()
 * \see MAIL_DO_AFTER_READ
 *
 * Obowiązują te same możliwe wartośći co w MAIL_DO_AFTER_READ.
 *
 * Domyślnie: 0
 */
define( "MAIL_DO_FOR_DUPLICATE", 0 );

/**
 * Skrzynka do którego mają zostać przenoszone wiadomości zawierające zaproszenia.
 * Jeżeli nie wiesz jak skonfigurować tą stałą, skorzystaj ze skryptu `admin/mailbox_list.php`,
 * który zwraca listę skrzynek (folderów) dla skonfigurowanej skrzynki mailowej.
 * \see MAIL_AFTER_READ
 *
 * Domyślnie: INBOX.Trash
 */
define( "MAIL_TARGET_FOLDER", "INBOX.Trash" );

/**
 * Czy usuwać zaproszenia z pliku danych po ich przedawnieniu.
 * Domyślnie: true
 */
define( "REMOVE_PASSED", true );

////----------------------------------------------------------------
/**
 * Domyślny kanał na discordzie.
 * Pusty string - brak domyślnego kanału, zaproszenia poza filtrem są nigdzie wysyłane.
 */
define( "DISCORD_DEFAULT", "" );

?>