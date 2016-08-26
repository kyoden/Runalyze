<?php
/**
 * This file contains class::SessionAccountHandler
 * @package Runalyze\System
 */

use Runalyze\Language;

/**
 * Class: SessionAccountHandler
 * 
 * @author Michael Pohl
 * @author Hannes Christiansen
 * @package Runalyze\System
 */
class SessionAccountHandler {
	/**
	 * Array containing userrow from database
	 * @var array
	 */
	private static $Account = array();

	/**
	 * Error type
	 * @var int
	 */
	public static $ErrorType = 0;

	/**
	 * Construct a new SessionAccountHandler
	 * ATTENTION:
	 * - all used methods from constructor must not use any consts (except PREFIX)
	 * - all these consts will be defined after setting Account-ID,
	 *   because some of them need database-connection
	 */
	public function __construct() {

	}

	/**
	 * Is anyone logged in?
	 * @return boolean 
	 */
	public static function isLoggedIn() {
	    //TODO use a symfony security method
		if (isset($_SESSION['accountid']))
			return true;

		return false;
	}

	/**
	 * Try to use current session
	 * @return boolean 
	 */
	private function tryToUseSession() {
		Language::setLanguage($Account['language'], false);

		return false;
	}

	/**
	 * Update last action to current account 
	 */
	private function updateLastAction() {
		DB::getInstance()->update('account', self::getId(), 'lastaction', time());
	}

	/**
	 * Update language of current account
	 */
	private function updateLanguage() {
		DB::getInstance()->update('account', self::getId(), 'language', Language::getCurrentLanguage());
	}  

	/**
	 * Set internal account-data
	 * @param array $Account 
	 */
	public static function setAccount($Account = array()) {
		self::$Account = $Account;
	}

	/**
	 * Try to set account from request 
	 */
	public static function setAccountFromRequest() {
		if (empty(self::$Account)) {
			self::$Account = AccountHandler::getDataForId( SharedLinker::getUserId() );
		}
	}

	/**
	 * Set up session 
	 */
	private function setSession() {
		$this->setSessionValues();
	}

	/**
	 * Set account-values to session 
	 */
	private function setSessionValues() {
		session_regenerate_id();

	}
        

	/**
	 * Set session to database 
	 */
	private function setSessionToDatabase() {
	    //remove autologin hash from database
	}

	/**
	 * Get ID of current user
	 * @return int 
	 */
	public static function getId() {
		// Dirty hack for 'global.cleanup.php'
		if (defined('GLOBAL_CLEANUP') && class_exists('GlobalCleanupAccount')) {
			return GlobalCleanupAccount::$ID;
		}

		if (SharedLinker::isOnSharedPage()) {
			return SharedLinker::getUserId();
		}

		return isset(self::$Account['id']) ? self::$Account['id'] : -1;
	}

	/**
	 * Get mail of current user
	 * @return string 
	 */
	public static function getMail() {
		if (!isset(self::$Account['mail'])) {
			return '';
		}

		return self::$Account['mail'];
	}

	/**
	 * Get name of current user
	 * @return string 
	 */
	public static function getName() {
		if (!isset(self::$Account['name'])) {
			return '';
		}

		return self::$Account['name'];
	}

	/**
	 * Get if mails are allowed
	 * @return string 
	 */
	public static function getAllowMails() {
		if (!isset(self::$Account['allow_mails'])) {
			return '';
		}

		return self::$Account['allow_mails'];
	}
	
	/**
	 * Get if user allows access to account
	 * @return string 
	 */
	public static function getAllowSupport() {
		if (!isset(self::$Account['allow_support'])) {
			return '';
		}

		return self::$Account['allow_support'];
	}

	/**
	 * Get language of current user
	 * @return string 
	 */
	public static function getLanguage() {
		if (!isset(self::$Account['language'])) {
			return '';
		}

		return self::$Account['language'];
	}

	/**
	 * Get timezone of current user
	 * @return string 
	 */
	public static function getTimezone() {
		if (!isset(self::$Account['timezone'])) {
			return '';
		}

		return self::$Account['timezone'];
	}
	
	/**
	 * Get username of current user
	 * @return string 
	 */
	public static function getUsername() {
		if (!isset(self::$Account['username'])) {
			return '';
		}

		return self::$Account['username'];
	}

}
