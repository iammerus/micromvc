<?php
	/**
	 * Created by PhpStorm.
	 * User: Melvin
	 * Date: 7/10/2016
	 * Time: 4:41 AM
	 */

	namespace MicroPos\Core\Helpers;


	class Paginator
	{

		protected static $links = null;

		/**
		 * Default configurations
		 * @var array
		 * @access public
		 */
		public $_defaults = [
				'page'    => 1 ,
				'perPage' => 10 ,
		];
		/**
		 * Properties array
		 * @var array
		 * @access private
		 */
		private $_properties = [];

		/**
		 * Constructor
		 *
		 * @param array $array Array of results to be paginated
		 * @param int   $curPage The current page integer that should used
		 * @param int   $perPage The amount of items that should be show per page
		 */
		public function __construct($array , $curPage = null , $perPage = null)
		{
			$this->array = $array;
			$this->curPage = ($curPage == null ? $this->defaults['page'] : $curPage);
			$this->perPage = ($perPage == null ? $this->defaults['perPage'] : $perPage);

			static::setPaginationLinks($this->getLinks());
		}
		/**
		 * Global getter
		 *
		 * Takes a param from the properties array if it exists
		 *
		 * @param string $name The name of the property to get
		 * @return mixed Either the property from the internal
		 *                     properties array or false if isn't set
		 * @access public
		 */
		public function __get($name)
		{
			if (array_key_exists( $name , $this->_properties )) {
				return $this->_properties[$name];
			}

			return false;
		}

		/**
		 * Global setter
		 *
		 * Utilises the properties array
		 *
		 * @param string $name The name of the property to set
		 * @param string $value The value that the property is assigned
		 * @return void
		 * @access public
		 */
		public function __set($name , $value)
		{
			$this->_properties[$name] = $value;
		}

		public static function getPaginationLinks()
		{
			if(is_null(static::$links)) {
				return false;
			}

			return static::$links;
		}

		/**
		 * Set the HTML pagination links
		 * @param $links
		 */
		protected static function setPaginationLinks($links)
		{
			static::$links = $links;
		}

		/**
		 * Get the html links for the generated page offset
		 *
		 * @param array $params A list of parameters (probably get/post) to
		 *                      pass around with each request
		 * @return mixed  Return description (if any) ...
		 * @access public
		 */
		public function getLinks($params = [])
		{
			// Initiate the links array
			$plinks = [];
			$links = [];
			$slinks = [];

			if(empty($params)) {
				$params = $_GET;
			}

			// Concatenate the get variables to add to the page numbering string
			$queryUrl = '';
			if (!empty($params) === true) {
				unset($params['page']);
				$queryUrl = '&amp;' . http_build_query( $params );
			}

			// If we have more then one pages
			if (($this->pages) > 1) {
				// Assign the 'previous page' link into the array if we are not on the first page
				if ($this->page != 1) {
					if ($this->_showFirstAndLast) {
						$plinks[] = ' <li><a href="?page=1' . $queryUrl . '">&laquo;&laquo; First </a></li> ';
					}
					$plinks[] = ' <li><a href="?page=' . ($this->page - 1) . $queryUrl . '">&laquo; Prev</a></li> ';
				}


				// Assign all the page numbers & links to the array
				for ($j = 1; $j < ($this->pages + 1); $j++) {
					if ($this->page == $j) {
						$links[] = ' <li class="active"><a href="javascript:void(0)">' . $j . '</a></li> '; // If we are on the same page as the current item
					}
					else {
						$links[] = '<li><a href="?page=' . $j . $queryUrl . '">' . $j . '</a></li>'; // add the link to the array
					}
				}

				// Assign the 'next page' if we are not on the last page
				if ($this->page < $this->pages) {
					$slinks[] = ' <li><a href="?page=' . ($this->page + 1) . $queryUrl . '"> Next &raquo; </a></li> ';
					if ($this->_showFirstAndLast) {
						$slinks[] = ' <li><a href="?page=' . ($this->pages) . $queryUrl . '">Last &raquo;&raquo; </a></li> ';
					}
				}

				// Push the array into a string using any some glue
				return implode( ' ' , $plinks ) . implode( $this->mainSeperator , $links ) . implode( ' ' , $slinks );
			}

			return;
		}

		/**
		 * Get the result portion from the provided array
		 *
		 * @return array Reduced array with correct calculated offset
		 * @access public
		 */
		public function getResults()
		{
			// Assign the page variable
			if (empty($this->curPage) !== false) {
				$this->page = $this->curPage; // using the get method
			}
			else {
				$this->page = 1; // if we don't have a page number then assume we are on the first page
			}

			// Take the length of the array
			$this->length = count( $this->array );

			// Get the number of pages
			$this->pages = ceil( $this->length / $this->perPage );

			// Calculate the starting point
			$this->start = ceil( ($this->page - 1) * $this->perPage );

			// return the portion of results
			return array_slice( $this->array , $this->start , $this->perPage );
		}

		/**
		 * Set the main seperator character
		 *
		 * By default this will implode an empty string
		 *
		 * @param string $mainSeperator The seperator between the page numbers
		 * @return void
		 * @access public
		 */
		public function setMainSeperator($mainSeperator)
		{
			$this->mainSeperator = $mainSeperator;
		}

		/**
		 * Set the show first and last configuration
		 *
		 * This will enable the "<< first" and "last >>" style
		 * links
		 *
		 * @param boolean $showFirstAndLast True to show, false to hide.
		 * @return void
		 * @access public
		 */
		public function setShowFirstAndLast($showFirstAndLast)
		{
			$this->_showFirstAndLast = $showFirstAndLast;
		}
	}
