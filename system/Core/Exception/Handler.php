<?php

	namespace MicroPos\Core\Exception;

	use MicroPos\Core\View;
	use MicroPos\Core\Helpers\Url;
	use MicroPos\Core\Http\Response;
	use Symfony\Component\Config\Definition\Exception\Exception;

	/**
	 * Default exception handler for the application
	 *
	 * @package \MicroPos\Core\Exception
	 */
	class Handler
	{
		public static function handle($ex)
		{
			if ($ex instanceof NotFoundHttpException) {
				handleNotFoundError();
				return;
			}
			echo "An exception occurred: " . $ex->getMessage() . " in " . $ex->getFile() . " on line " . $ex->getLine();
		}

	}
