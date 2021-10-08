<?php

/**
 * \file Mailbox.php
 * \brief Opisuje klasę Mailbox
 */

// Dołącza dane konfiguracyjne
require_once "config.php";

/**
 * \brief Zarządza komunikacją ze skrzyną pocztową.
 */
class Mailbox
{

	private $imapResource = null; 	///< Połączenie ze skrzyną mailową

	/**
	 * \brief Konstruktor
	 * \param $mailbox - adres serwera skrzynki mailowej
	 * \param $address - adres skrzynki mailowej
	 * \param $password - hasło do skrzynki
	 * \post Łączy się ze skrzynką
	 */
	public function __construct(
		$mailbox  = MAIL_MAILBOX,
		$address  = MAIL_ADDRESS,
		$password = MAIL_PASSWORD
	) {
		/// Próbuje połączyć się ze skrzynką
		$this->imapResource = imap_open($mailbox, $address, $password);

		/// Jeśli błąd wyrzuca wyjątek z błędem połączenia.
		if ($this->imapResource === false)
			throw new Exception(imap_last_error());
	}

	/**
	 * \brief Pobiera listę ostatnich wiadomości ze skrzynki
	 * \param $lastdays - ile dni wstecz pobiera wiadomosci
	 */
	public function fetch_maillist($lastdays = LAST_DAYS)
	{
		/// Filtr przeszukiwania wiadomości, rozpatruje tylko wiadomości z ostatniego tygodnia
		$search = 'SINCE "' . date("j F Y", strtotime("-" . $lastdays . " days")) . '"';

		/// Ładuje i zwraca przefiltrowane wiadmości
		return imap_search($this->imapResource, $search);
	}

	/**
	 * \brief Pobiera nagłówek wiadomosći
	 */
	public function fetch_overview($email)
	{
		return imap_fetch_overview($this->imapResource, $email)[0];
	}

	/**
	 * \brief Pobiera treść wiadomości
	 * i przetwarza treść wiadomości
	 */
	public function fetch_message($email)
	{
		return quoted_printable_decode(imap_fetchbody($this->imapResource, $email, 1));
	}

	/**
	 * \brief Wykonuje akcje na wiadomościach
	 * \param $code - kod akcji, \see MAIL_DO_AFTER_READ, MAIL_DO_FOR_DUPLICATE
	 * \param $email - wiadomość
	 *
	 * Kody akcji:
	 */
	public function do_action($code, $email)
	{
		switch ($code) {
				/// 2: Usuwa wiadomość pernamentie
			case 2:
				imap_delete($this->imapResource, $email);
				echo "Usunięto wiadomość<br/>";
				break;

				/// 1: Przenosi wiadomość do skrzynki
			case 1:
				if (!imap_mail_move($this->imapResource, "$email", MAIL_TARGET_FOLDER))
					echo "Błąd przenoszenia wiadomości do skrzynki " . MAIL_TARGET_FOLDER . "<br/>";

				echo "Przeniesiono wiadomosć do " . MAIL_TARGET_FOLDER . "<br/>";
				break;

				/// 0: Pozostawia bez zmian
			case 0:
				break;

				// Wyświetla komunikat o nieznanej opcji
			default:
				echo "Nie znany kod akcji!<br/>";
				break;
		}
	}

	/**
	 * \brief Zamyka skrzynkę.
	 */
	public function close()
	{
		imap_close($this->imapResource);
	}

	/**
	 * \brief Usuwa wszystkie zaznaczone do usunięcia.
	 * \warning Usuwa pernamentnie!
	 */
	public function expunge()
	{
		imap_expunge($this->imapResource);
	}
};
