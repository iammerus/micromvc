<?php
	use MicroPos\Core\View;
	use MicroPos\Core\Helpers\Url;
	use MicroPos\Core\Http\Request;
	use MicroPos\Core\Helpers\Csrf;
	use MicroPos\Core\Helpers\Flash;
	use MicroPos\Core\Helpers\Config;
	use MicroPos\Core\Helpers\Paginator;
	use MicroPos\Core\Authentication;
    use MicroPos\Core\Http\Response;

	if (!function_exists( 'asset' )) {
		function asset($relativePath)
		{
			return Url::resourcePath( $relativePath );
		}
	}

	if (!function_exists( 'url' )) {
		function url($path)
		{
			return getenv( "SITE_URL" ) . $path;
		}
	}

	if (!function_exists( 'csrf_token' )) {
		function csrf_token()
		{
			return Csrf::makeToken();
		}

	}

	if (!function_exists( 'config' )) {
		/**
		 * Returns an instance of the Config class
		 * @return Config|null
		 */
		function config($key = null)
		{
			if (is_null( $key )) {
				return Config::getInstance();
			}
			else {
				return Config::getInstance()->get( $key );
			}
		}
	}

	if (!function_exists( 'request' )) {
		function request()
		{
			return \MicroPos\Core\Http\Request::getInstance();
		}
	}

	if (!function_exists( 'getFlashMessages' )) {
		function getFlashMessages()
		{
			return Flash::getInstance()->display( null , false );
		}
	}

	if (!function_exists( 'flash' )) {
		function flash($title , $message , $type)
		{
			$flash = Flash::getInstance();

			$flash->add( "<strong>$title</strong><br>$message" , $type );
		}
	}

	if (!function_exists( 'view' )) {
		/**
		 * Render the specified view
		 * @param  string $name The name of the view to render
		 * @param array   $params Pass the given data to the view
		 * @return void
		 */
		function view($name , $params = [])
		{
			$viewDir = viewRoot();
			$cacheDir = viewRoot() . "/cache";

			$view = new View( [$viewDir] , $cacheDir );

			echo $view->view()->make( $name , $params );
		}
	}

	if (!function_exists( 'paginator' )) {
		/**
		 * Return a new instance of the paginator class
		 * @param array $data
		 * @return Paginator
		 */
		function paginator(array $data)
		{
			return new Paginator(
				$data ,
				Request::getInstance()->get( 'page' ) == null ? 1 : (int)Request::getInstance()->get( 'page' ) ,
				10
			);
		}
	}

	if (!function_exists( 'paginationLinks' )) {
		/**
		 * Get pagination links
		 *
		 * @return bool|string
		 */
		function paginationLinks()
		{
			return Paginator::getPaginationLinks();
		}
	}

	if (!function_exists( 'auth' )) {
		/**
		 * Returns an instance of the authentication class
		 * @return \PHPAuth\Auth
		 */
		function auth()
		{
			return Authentication::getInstance();
		}
	}


	if(!function_exists('config_table'))
    {
        /**
         * Returns the name of the configuration table
         * @return string
         */
        function config_table()
        {
            return getenv("CONFIG_TABLE");
        }
    }

	function appRoot()
    {
        return getenv( 'APP_ROOT' );
    }

	function viewRoot()
    {
        return getenv( "VIEW_DIR" );
    }

	function modelsRoot()
    {
        return getenv( "MODELS_DIR" );
    }

    function configRoot()
    {
        return realpath( getenv( "CONFIG_DIR" ) );
    }

	function userDir()
    {
        return realpath( getenv( "USER_DIR" ) );
    }

	function avatarDir()
    {
        return realpath( getenv( "AVATAR_DIR" ) );
    }

	function assetsDir()
    {
        return realpath( getenv( 'ASSETS_DIR' ) );
    }

	function uploadDir()
    {
        return realpath( assetsDir() . "/uploads" );
    }


	function baseUrl()
    {
        return getenv( 'SITE_URL' );
    }

    function printWelcomeFoundPage()
    {
        return print "<html><head><title>MicroPos</title><link rel='stylesheet' href='".  asset('vendor/emoji-css/emoji.css')  . "'></head><body><h1 style=\"font-family: sans-serif; font-weight: 100; font-size: 50px; text-align: center;position: relative;top: 50%;transform: translateY(-50%)\">Micro<strong>POS</strong><br><span style=\" font-size: 20px; \">MicroPOS has been set up successfully. Let's make something great. <i class=\"em em-v\"></i></span></h1></body></html>";
    }

	function printNotFoundPage($page)
    {
        return print "<html><head><title>Page Not Found | MicroPos</title></head><body><h1 style=\"font-family: sans-serif; font-weight: 100; font-size: 50px; text-align: center;position: relative;top: 50%;transform: translateY(-50%)\">Page not found<br><span style=\" font-size: 20px; \">The request page '{$page}' was not found on this server.</span></h1></body></html>";
    }

	function printInternalServerErrorPage()
    {
        return print "<html><head><title>Internal Server Error | MicroPos</title></head><body><h1 style=\"font-family: sans-serif; font-weight: 100; font-size: 50px; text-align: center;position: relative;top: 50%;transform: translateY(-50%)\">Internal Server Error<br><span style=\" font-size: 20px; \">Something went wrong. Contact support</span></h1></body></html>";
    }

	function printMaintenancePage()
    {
        return print "<html><head><title>Website Under Maintenance | MicroPos</title></head><body><h1 style=\"font-family: sans-serif; font-weight: 100; font-size: 50px; text-align: center;position: relative;top: 50%;transform: translateY(-50%)\">Our website is currently under maintenance. <br><br /><span style=\" font-size: 20px; \">Our apologies for any inconveniences caused</span></h1></body></html>";
    }

		function handleInternalServerError()
        {
            Response::addStatus( 500 );
            Response::sendHeaders();

            if (file_exists( viewRoot() . "/500.blade.php" )) {
                include_once viewRoot() . "/500.blade.php";
            }
            else {
                printInternalServerErrorPage();
            }
        }

		function handleMaintenanceMode()
        {
            Response::addStatus( 503 );
            Response::sendHeaders();

            if (file_exists( viewRoot() . "/503.blade.php" )) {
                include_once viewRoot() . "/503.blade.php";
            }
            else {
                printMaintenancePage();
            }
        }

		function handleNotFoundError()
        {
            Response::addStatus( 404 );
            Response::sendHeaders();

            if (file_exists( viewRoot() . "/404.blade.php" )) {
                include_once viewRoot() . "/404.blade.php";
            }
            else {
                printNotFoundPage( Url::detectUri() );
            }
        }
