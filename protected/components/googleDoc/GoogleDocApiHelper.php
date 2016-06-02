<?php
	require_once(Yii::getPathOfAlias('application').'/../vendor'. DIRECTORY_SEPARATOR .'autoload.php');
	//require_once(__DIR__ . '/../../vendor' . DIRECTORY_SEPARATOR .'autoload.php');
	require_once(__DIR__.'/oauth2/token.php');
	require_once(__DIR__.'/oauth2/googleapi.php');
	class GoogleDocApiHelper {
		
		public $success = true;
		/**
		 * @var array config - a config array that gives information about the authentication. It is needed only
		 * when attaining tokens
		 */
		protected $config;
		/**
		 * @var object[OAuth2\GoogleAPI] oAuth - google oAuth2 instance that can give tokens
		 */
		protected $oAuth;
		/**
		 * @var object[Google\Spreadsheet\SpreadsheetService] api - an object that makes all the work with googleDoc
		 */
		protected $api;
		/**
		 * @var cachedSpread, cachedWork - contain googleDocApi objects in order not to create them every time.
		 */
		protected $cachedSpread;
		protected $cachedWork;
		
		public function __construct($config = false) {
			//Это нужно мне. Там стоят мои данные, мне лень каждый раз их передавать в аргумент.
			if (empty($config)) {
				require_once("configFile.php");
				$config = giveConfig();
			}
			
			$this->config = $config;
			
			// load OAuth2 token data - exit if false
			if (($tokenData = $this->loadOAuth2TokenData()) === false) {
				$this -> success = false;
				return;
			}
			// setup Google OAuth2 handler
			$OAuth2GoogleAPI = $this->getOAuth2GoogleAPIInstance();

			$OAuth2GoogleAPI->setTokenData(
				$tokenData['accessToken'],
				$tokenData['tokenType'],
				$tokenData['expiresAt'],
				$tokenData['refreshToken']
			);

			$OAuth2GoogleAPI->setTokenRefreshHandler(function(array $tokenData) {

				// save updated OAuth2 token data back to file
				$this->saveOAuth2TokenData($tokenData);
			});
			
			$this -> oAuth = $OAuth2GoogleAPI;
			//Create a RequestFactory instance and save it for future needs
			$serviceRequest = new Google\Spreadsheet\DefaultServiceRequest($this -> getToken());
			//Устанавливаем requestFactory;
			Google\Spreadsheet\ServiceRequestFactory::setInstance($serviceRequest);
			//$this -> request = $serviceRequest;
			
			//create a spreadSheetService instance to use it later
			$this -> api = new Google\Spreadsheet\SpreadsheetService();
			
			
			/*$spreadsheetFeed = $this -> api ->getSpreadsheets();
			//$spreadsheet = $spreadsheetFeed ->getByTitle('СТАТИСТИКА СПб');
			$spreadsheet = $spreadsheetFeed ->getByTitle('googleCheck');
			$worksheetFeed = $spreadsheet->getWorksheets();
			//var_dump($worksheetFeed);
			$worksheet = $worksheetFeed->getByTitle('January 2016');
			vardump($worksheet -> getListFeed() -> getEntries());//*/
		}
		
		
		/**
		 * @arg array crit - criteria to the query. array('sq' => '<query condition>', 'reverse' => (true|false), 'orderby' => '<columnName>')
		 * <query condition> - a string that can contain "and", "or" operators concatenating simple queries like <columnName> (>|<|=|<=|>=|<>) <value>
		 * @arg string|Google\Spreadsheet\Spreadsheet spread - the spreadSheet object to get data from
		 * @arg string|Google\Spreadsheet\Worksheet work - the worksheet object to get data from. It must be the ancestor or $spread
		 * @return arrayIterator - array iterator for data rows sorted by <columnName>.
		 */
		public function giveData($crit = array(), $spread = false, $work = false){
			if ($this -> setWorkArea($spread, $work)) {
				if ($rez = $this -> cachedWork -> getListFeed($crit)) {
					return $rez;
				}
			}
		}
		/**
		 * @arg string|Google\Spreadsheet\Spreadsheet spread - the spreadSheet object to get data from
		 * @arg string|Google\Spreadsheet\Worksheet work - the worksheet object to get data from. It must be the ancestor or $spread
		 * @return boolean - whether setting is successful
		 */

		public function setWorkArea($spread = false, $work = false){
			//Если нам дали не объект в качестве таблицы, то пытаемся его получить сначала по строке.
			if ((!is_a($spread,'Google\Spreadsheet\Spreadsheet'))&&is_string($spread)&&(strlen($spread) > 0)) {
				//Пытаемся найти нужную таблицу
				$spreadsheetFeed = $this -> api ->getSpreadsheets();
				$spreadsheet = $spreadsheetFeed ->getByTitle($spread);
				if ($spreadsheet) {
					$spread = $spreadsheet;
				}
			}
			//Если получить не удалось, то берем из кеша.
			if (!is_a($spread,'Google\Spreadsheet\Spreadsheet')) {
				$spread = $this -> cachedSpread;
				if (!$spread) {
					return false;
				}
			}
			//Если нам дали не объект в качестве листа, то пытаемся его получить сначала по строке.
			if ((!is_a($work,'Google\Spreadsheet\Worksheet'))&&is_string($work)&&(strlen($work) > 0)) {
				//Пытаемся найти нужную таблицу
				$worksheetFeed = $spread ->getWorksheets();
				$worksheet = $worksheetFeed ->getByTitle($work);
				if ($worksheet) {
					$work = $worksheet;
				}
			}
			//Если получить не удалось, то берем из кеша.
			if (!is_a($work,'Google\Spreadsheet\Worksheet')) {
				$work = $this -> cachedWork;
				if (!$work) {
					return false;
				}
			}
			if (is_a($spread,'Google\Spreadsheet\Spreadsheet')&&is_a($work,'Google\Spreadsheet\Worksheet')) {
				$this -> cachedSpread = $spread;
				$this -> cachedWork = $work;
				return true;
			} else {
				return false;
			}
		}
		/**
		 * Searches the google doc file.
		 */
		public function searchEverywhere($queryArray, $workSheet){
			if ($entry = $this -> getLastEntry($queryArray,'Ремонт СПб', $workSheet)) {
				return $entry;
			}
			return false;
		}
		public function getLastEntry($queryArray, $spread, $work){
			$this -> setWorkArea($spread, $work);
			$data = $this -> giveData($queryArray);
			$entries = $data ? $data -> getEntries() : array();
			if (count($entries) > 0) {
				return end($entries);
			} else {
				return false;
			}
		}
		/**
		 * WARNING: BAD PRACTICE. Gives an up-to-date accessToken. I do no know how to do it correct. I need an access token to
		 * set up a "comfortable" api, but there is no way to get the updated token directly without changing the googleapi.php
		 * So I decided to make a workaround and parse the HTTP header that contains a renewed token. This is not a good idea,
		 * but it works for now.
		 * @return string - the token string!
		 */
		protected function getToken(){
			$arr = $this -> oAuth -> getAuthHTTPHeader();
			/*
				arr = array([0] => '<headerName>', [1] => '<TokenType> <token!>')
			*/
			//Получили строку с кодом
			$string = end($arr);
			//Получили массив
			$data = array_filter(array_map('trim',explode(' ',$string)));
			return end($data);
		}
		/**
		 * These Functions help to get access token
		 */
		protected function getOAuth2GoogleAPIInstance() {

			$OAuth2URLList = $this->config['OAuth2URL'];

			return new OAuth2\GoogleAPI(
				$OAuth2URLList['base'] . '/' . $OAuth2URLList['token'],
				$OAuth2URLList['redirect'],
				$this->config['clientID'],
				$this->config['clientSecret']
			);
		}

		protected function saveOAuth2TokenData(array $data) {

			file_put_contents(
				$this->config['tokenDataFile'],
				serialize($data)
			);
		}
		
		
		private function loadOAuth2TokenData() {

			//$tokenDataFile = $this->config['tokenDataFile'];
			$tokenDataFile = $this->config['tokenDataFile'];
			if (!is_file($tokenDataFile)) {
				$tokenDataFile = __DIR__.'/'.$tokenDataFile;
			}
			if (!is_file($tokenDataFile)) {
				echo(sprintf(
					"Error: unable to locate token file [%s]\n",
					$tokenDataFile
				));

				return false;
			}

			// load file, return data as PHP array
			return unserialize(file_get_contents($tokenDataFile));
		}
	}
?>