<?php

	namespace MicroPos\Core;

	use MicroPos\Core\Exception\Handler;
	use MicroPos\Core\Helpers\Config;
	use MicroPos\Core\Helpers\Flash;
	use MicroPos\Core\Http\Request;
	use MicroPos\Core\Http\Router;
	use MicroPos\Core\Helpers\Session;

	/**
	 * The core class for the MicroPos application
	 *
	 * This class holds all the different pieces of the application together
	 *
	 * @package \MicroPos\Core
	 */
	class App
	{
		/**
		 * Instance of the application
		 * @var \MicroPos\Core\App
		 */
		private static $instance;

		/**
		 * Instance of the application's router
		 * @var \MicroPos\Core\Http\Router
		 */
		private $router;

		/**
		 * Application's database manager
		 * @var Database
		 */
		private $eloquent;

		/**
		 * Request object
		 * @var Request
		 */
		private $request;

		/**
		 * Authentication class
		 * @var Authentication
		 */
		private $auth;

		/**
		 * Configuration instance
		 * @var Config
		 */
		private $config;

		protected static $bootComplete = false;

		/**
		 * @var \MicroPos\Core\Helpers\Flash
		 */
		protected $messages;

		/**
		 * Ips addresses that can access the site even when it's in maintenance mode
		 * @var array
		 */
		protected $maintenanceIps = [];

		protected $validRequest = false;

		public function __construct()
		{
			$this->router = new Router();

			$this->eloquent = new Database();

			$this->config = new Config();
		}

		/**
		 * Boots up the application and gets it ready to respond to the current request
		 */
		public function boot()
		{
					$this->setDefaults();

					if($this->isMaintenance()) {
						$this->evaluateMaintenance();
					}

					$this->startSession();

					$this->readFlashMessages();

					$this->captureRequest();

					$this->initializeDatabase();

					$this->initializeAuthentication();

					$this->registerRoutes();

					static::$instance = $this;

					static::$bootComplete = true;
		}

		/**
		 * Starts a new session, if one is not already started
		 *
		 * @return void
		 */
		protected function startSession()
		{
			Session::init();
		}

		/**
		 * Reads flash messages from the session
		 * @return void
		 */
		protected function readFlashMessages()
		{
			/* TODO: Implement functionality to read flash messages from the session and save them  */
			$this->messages = new Flash();
		}

		/**
		 * Capture the current request
		 * @return void
		 */
		protected function captureRequest()
		{
			$this->request = Request::capture();
		}

		/**
		 * Initialize the authentication class
		 * @return void
		 */
		protected function initializeAuthentication()
		{
			$this->auth = Authentication::create();
		}

		/**
		 * Get an instance of the application
		 * @return \MicroPos\Core\App
		 */
		public static function getInstance()
		{
			if (static::isBooted()) {
				return static::$instance;
			}
			else {
				static::$instance->boot();

				return static::$instance;
			}
		}

		/**
		 * Collects messages if the Session the clears them from the session
		 * @return Flash
		 */
		public function collectMessageBag()
		{
			$data = Session::pull( 'messageBag' );

			if (is_null( $data ) || empty($data)) {
				$data = [];
			}
			else {
				$data = unserialize( $data );
			}

			return $this->messages = new Flash( $data );
		}

		/**
		 * Create a database connection and initializes the Eloquent ORM
		 */
		protected function initializeDatabase()
		{
			$this->eloquent->initialize();
		}

		/**
		 * Sets up some of the application's default settings`
		 */
		public function setDefaults()
		{
			set_exception_handler( ["\\MicroPos\\Core\\Exception\\Handler" , "handle"] );
			date_default_timezone_set( "Africa/Harare" );

			$this->setDefaultErrorReportingMode();


			$this->maintenanceIps = $this->getMaintenanceAddresses();
		}

		protected function showApplicationErrors()
		{
			return strtolower(getenv('APP_ERRORS')) == 'all';
		}

		protected function setDefaultErrorReportingMode()
		{
			if($this->showApplicationErrors() === true) {
				return ini_set('error_reporting', 'On');
			} 

			ini_set('error_reporting', 'Off');
		}

		protected function getMaintenanceAddresses()
		{
				$addresses = (array)require_once configRoot() . DIRECTORY_SEPARATOR . "ips.php";

				return $addresses;
		}
		/**
		 * Gets the routes defined by the user
		 *
		 * @return void
		 */
		protected function registerRoutes()
		{
			$routes = require_once userDir() . DIRECTORY_SEPARATOR . "routes.php";

			$this->router->setRoutes( $routes );
		}

		/**
		 * Run the application
		 *
		 * @throws \MicroPos\Core\Exception\NotFoundHttpException
		 * @return void
		 */
		public function run()
		{
			if($this->isMaintenance()) {
				$this->handleMaintenance();
			}

			if ($this->isBooted()) {

				$this->matchRoute();

				$this->shutdown();
			}
			else {
				$this->boot();

				$this->run();
			}
		}

		protected function evaluateMaintenance()
		{
			$this->validRequest = in_array(
				$_SERVER['REMOTE_ADDR'], $this->maintenanceIps
			);
		}


		protected function handleMaintenance()
		{
			if(!$this->validRequest) {
					handleMaintenanceMode();

					$this->shutdown();
			}
		}

		/**
		 * Gets the action for the request route and executes
		 *
		 * @return void
		 */
		public function matchRoute()
		{
			$this->router->match();

			$this->router->dispatch();
		}

		/**
		 * Check if application has completed boot up process
		 *
		 * @return boolean
		 */
		public static function isBooted()
		{
			return static::$bootComplete;
		}

		/**
		 * Shutdown the application
		 *
		 * Perform any final tasks/checks/logs etc.. before terminating the script
		 *
		 * @return void
		 */
		public function shutdown()
		{
			exit(0);
		}

		protected function getApplicationMode()
		{
			return getenv("APP_MODE");
		}

		protected function isMaintenance()
		{
			return $this->getApplicationMode() == "maintenance";
		}

	}
