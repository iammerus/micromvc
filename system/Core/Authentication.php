<?php

	namespace MicroPos\Core;

	use MicroPos\Core\Helpers\Cookie;
	use PHPAuth\Auth;
	use PHPAuth\Config;

	/**
	 * Class Authentication
	 *
	 * @package \MicroPos\Core
	 */
	class Authentication
	{
		/**
		 * @var \PHPAuth\Auth
		 */
		protected static $instance;

		/**
		 * Authentication constructor.
		 */
		public function __construct()
		{
			static::$instance = new Auth( Database::getPDOInstance() ,
				new Config( Database::getPDOInstance() , config_table() )
			);
		}

		/**
		 * @return Authentication
		 */
		public static function create()
		{
			return new Authentication();
		}

		/**
		 * Get the instance of the authentication object
		 * @return \PHPAuth\Auth
		 */
		public static function getInstance()
		{
			return static::$instance;
		}

		public static function isUserAgent($email)
		{
			$auth = static::getInstance();

			$uid = $auth->getUID($email);

			if($uid == false) {
				return null;
			}

			return static::isUserAdmin($uid) != true;

		}

		public static function isUserDeathRegistrar()
		{
			$auth = static::getInstance();

			$uid = $auth->getSessionUID($auth->getSessionHash());

			$user = $auth->getUser($uid);

			return $user['type'] == 'death';
		}

		public static function isUserMarriageRegistrar()
		{
			$auth = static::getInstance();

			$uid = $auth->getSessionUID($auth->getSessionHash());

			$user = $auth->getUser($uid);

			return $user['type'] == 'marriage';
		}

		public static function isUserBirthRegistrar()
		{
			$auth = static::getInstance();

			$uid = $auth->getSessionUID($auth->getSessionHash());

			$user = $auth->getUser($uid);

			return $user['type'] == 'birth';
		}

		public static function isUserAdmin($uid = null)
		{
			$auth = static::getInstance();

			if(is_null($uid)) {
				$uid = $auth->getSessionUID($auth->getSessionHash());
			}

			$user = \User::where('id', '=', $uid)->get()->first();

			return $user->isAdmin == 1;
		}

	}
