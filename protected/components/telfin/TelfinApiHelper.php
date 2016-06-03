<?php
//The constant JSON_PRETTY_PRINT was replaced by its numerical value 128 in order to use this code from the command line.
//For some reason the JSON_PRETTY_PRINT is not defined by the shell on the server
defined('JSON_PRETTY_PRINT') or define('JSON_PRETTY_PRINT',128);
	class TelfinApiHelper {
		/**
		 * @const handlerURI - where to send access token answer.
		 */
		const handlerURI = 'http://f.mrimaster.ru/storeData';
		/**
		 * @const IVR_KEY - name of the parameter that contains ivr.
		 */
		const IVR_KEY = 'doctornum';
		/**
		 * @const string TOKEN_FILE - where to store token info.
		 */
		const TOKEN_FILE = 'tokenfile.json';
		private $client_id;
		private $client_secret;
		/**
		 * @arg string clientId
		 * @arg string clientSecret
		 */
		public function __construct($clientId = false, $clientSecret = false){
			$this -> client_id = $clientId;
			$this -> client_secret = $clientSecret;
		}
		/**
		 * @arg array input - POST/GET array that is given by Telfin Api.
		 * @return iCall - the iCall object created from accepted data.
		 */
		public function giveCallObject(array $input) {
			//Проверку на CallStatus убрали.
			//if ($input["CallStatus"]=='ANSWER') {
			//Вернее, ставим ошибку, если статус не тот.
			$error = false;
			if ($input["CallStatus"]!='ANSWER') {
				$error = CallType::model() -> getNumber('another_call_status');
				echo "bad call status<br/>";
			}
			$caller = $input['CallerIDNum'];
			//Нашли телефон, на который звнили.
			$phone = $this -> givePhoneObject($input);
			if (!$phone) {
				$phone = new UserPhone;
				//Если телефона нет в базе, то сохраняем ошибку.
				//ошибка автоматически выставится
			}
			//Получаем TelfinCall, соответсвующий пришедшему звонку.
			$tCall = $this -> registerCall($input, $phone, $error);
			$caller = self::giveNumberFromNumString($caller);
			/*$matches = array();
			preg_match('/\d{11}/',$caller, $matches);*/
			//vardump($matches);
			echo 'callerNum:'.$caller.'<br/>';
			
			if ($caller) {
				//echo "<br/>ok!";
				if (!(is_a($tCall,'TelfinCall'))) {
					$call = new FastCall($caller, $phone);
				} else {
					$call = new FastCall($caller, $phone, $tCall);
				}
				
				return $call;
			} else {
				if (is_a($tCall,'TelfinCall')) {
					$tCall -> id_error = CallError::model() -> giveText('incorrect_caller');
				}
				echo "incorrect caller number";
			}
			/*} else {
				$this -> registerCall($input, NULL, CallType::model() -> getNumber('another_call_status'));
				echo "bad call status<br/>";
			}*/
		}
		/**
		 * @arg array input - data from telfin api
		 * @arg bool register - whether to save call info to the database or not
		 * @return UserPhone - the called phone object
		 */
		public function givePhoneObject($input, $register = false){
			//Прежде всего проверяем, нет ли звонка в базе
			$tCall = TelfinCall::model() -> findByPk($input['CallAPIID']);
			if ($tCall) {
				$phone = UserPhone::model() -> findByPk($tCall -> id_phone);
				echo "the call is already present in database.<br/>".PHP_EOL;
				//Если ID звонка найден в базе, то возвращаем то, что записано, а не пытаеся его найти.
				//if ($phone) {
				return $phone;
				//}
			}
			$number = $input['CalledDID'];
			//Если нет номера, то и говорить не о чем.
			if (!$number) {
				echo "No number is specified<br/>".PHP_EOL;
				return null;
			}
			$ivr = $input[self::IVR_KEY];
			echo "Number: ".$number."<br/>";
			echo "IVR: ".$ivr."<br/>";
			$criteria = new CDbCriteria();
			$criteria -> compare('number',$number);
			$mistaken = false;
			if ($ivr) {
				$criteria -> compare('ivr',$ivr);
			} else {
				/**
				 * Добавляем проверку на наличие у набранного номера хотя бы одного добавочного.
				 * Если таковой имеется, то понимаем, что юзер ошибся и телефон не будет определен правильно.
				 */
				$temp_crit = new CDbCriteria();
				$temp_crit -> compare('number',$number);
				$temp_crit -> addCondition('ivr IS NOT NULL');
				if (UserPhone::model() -> find($temp_crit)) {
					echo "This number has at least one IVR, but none specified => mistake!<br/>";
					$mistaken = true;
				}
				$criteria -> addCondition('ivr is NULL');
			}
			if (!$mistaken) {
				$phone = UserPhone::model() -> find($criteria);
			} else {
				$phone = NULL;
			}
			//Если нужно создать запись, то сохраняем ID звонка и номер, на который звонили.
			//В случае если найден IVR но не найден номер тоже сохраняем.
			if ($register) {
				$this -> registerCall($input, $phone);
				//$this -> registerCall($input, $phone);
			}
			/*if (($register)&&(strlen($ivr) > 0)) {
				$this -> registerCall($input, $phone);
			}*/
			return $phone;
		}
		/**
		 * Saves informaton about a call caought by telfin api
		 * @arg array input - data from telfin api
		 * @arg UserPhone phone - called phone
		 * @arg integer error - id of the error occured
		 * @return TelfinCall - the saved telfin call instance
		 */
		public function registerCall($input, UserPhone $phone = NULL, $error = NULL){
			//Прежде всего проверяем, нет ли уже этого звонка в базе.
			$tCall = TelfinCall::model() -> findByPk($input['CallAPIID']);
			if ($tCall) {
				return $tCall;
			}
			unset($tCall);
			//Ищем телефон, на который звонили, если он не задан.
			if ($phone) {
				$phone = $this -> givePhoneObject($input, false);
			}
			//Создаем запись, сохраняем Id звонка
			$tCall = new TelfinCall();
			$tCall -> ApiId = $input['CallAPIID'];
			//Сохраняем номер звонившего.
			$tCall -> caller = self::giveNumberFromNumString($input["CallerIDNum"]);
			$tCall -> called = $input['CalledDID'];
			$tCall -> ivr = $input[self::IVR_KEY];
			//Если телефон не найден, то записываем ошибку
			if (!is_a($phone,'UserPhone')) {
				$error = CallError::model() -> giveText('no_number');
			} else {
				$tCall -> id_phone = $phone -> id;
			}
			$tCall -> id_error = $error;
			if ($tCall -> save()) {
				return $tCall;
			}
			return false;
		}
		/**
		 * @arg callable handler - the function to be used on each TelfinCall Object.
		 * @arg integer from - the beginning of time period
		 * @arg integer to - the end of the time period
		 * @return TelfinCall[] - an array of calls for time period. If nothing given then the period is assumed to be the current day.
		 */
		public function giveAllCalls($handler = false, $from = false, $to = false) {
			
			$obj = $this -> giveCallsResponse($from, $to);
			$rez = array();
			if (is_array($obj)) {
				//Перебираем все возвращенные звонки. Ключ - id звонка, значение - объект звонка.
				foreach($obj -> entry as $apiId => $btCall){
					//Создаем TelfinCall
					$tCall = TelfinCall::createFromTelfinStat($btCall);
					//Применяем к ним функцию.
					if (is_callable($handler)) {
						$tCall = $handler($tCall);
						//Если назад ничего не вернулось, то не сохраняем этот звонок.
						if (!$tCall) {
							continue;
						}
					}
					$rez [] = $tCall;
				}
			}
			return $rez;
		}
		/**
		 * @arg integer from - the beginning of time period
		 * @arg integer to - the end of the time period
		 * @return stdClass - the response of api.
		 */
		public function giveCallsResponse($from = false, $to = false) {
			$obj = false;
			$ch = curl_init();
			//Если с токеном все в порядке, делаем, что нужно
			if ($token = $this -> getToken()) {
				$headers = array(
					//'HOST' => '',
					'Authorization' => 'Bearer ' . $token,
					//'User-Agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:43.0) Gecko/20100101 Firefox/43.0'
				);
				echo $token;
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				/**
				 * Разбираемся со временем.
				 */
				/**
				 * $from
				 */
				$time = new DateTime();
				if ($from == -1) {
					$fromFilter = '';
				} elseif ((is_int($from))&&($from > 0)) {
					$time -> setTimeStamp($from);
					$fromFilter = '&startDate='.urlencode($time -> format(DateTime::W3C));
				} else {
					//Если будем искать звонки за текущий день, обнуляем время.
					$time -> setTime(0,0,0);
					$fromFilter = '&startDate='.urlencode($time -> format(DateTime::W3C));
				}
				/**
				 * $to
				 */
				$time = new DateTime();
				if ($to == -1) {
					$toFilter = '';
				} elseif ((is_int($to))&&($to > 0)) {
					$time -> setTimeStamp($to);
					$toFilter = '&endDate='.urlencode($time -> format(DateTime::W3C));
				} else {
					//Если конец не указан, ищем до настоящего момента
					//$time -> setTime(23,59,59);
					$toFilter = '';
					//$toFilter = '&endDate='.$time -> format(DateTime::W3C);
				}
				echo '<br/>'.$fromFilter.'<br/>';
				//Нам интересны только удачные звонки. disposition = 1 <=> status = ANSWERED. Но этой проверки НЕТ!
				//$filterString='&filterOp=match&filterBy=disposition&filterValue=1';
				$filterString='';
				curl_setopt($ch, CURLOPT_URL, "https://hosted.telphin.ru/uapi/cdr?accessRequestToken={$token}{$filterString}{$toFilter}{$fromFilter}");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				//Получили ответ сервера.
				$rez = curl_exec($ch);
				$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
				if ($httpCode == '200') {
					/**
					 * Производим дешифровку из Json'а. Наст интересует $obj -> entry, который содержит звонки.
					 * Кроме того, из интересного, есть PhoneCallStat с некоторой статистикой и еще есть ссылка на следующую страницу.
					 */
					$obj = json_decode($rez);
					echo json_encode(json_decode($rez), JSON_PRETTY_PRINT);
					
					
				} else {
					echo "A mistake occured. Code {$httpCode}<br/>";
					echo "Response: ".$rez;
					$obj = false;
				}
			} else {
				echo "no token obtained!<br/>";
				$obj = false;
			}
			curl_close($ch);
			//Возвращаем весь объект ответа, включая статистику. Или false.
			return $obj;
		}
		/**
		 * gives the access token.
		 * @return string - the access token.
		 */
		private function getToken(){
			//Получаем информацию по токену
			$text = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR  .self::TOKEN_FILE);
			$data = json_decode($text);
			$update = false;
			//Если время получения кода некорректное, то получаем заново
			if ($data -> obtained < 24*3600*365*10) {
				$update = true;
			}
			if (!$update) {
				if (time() > $data -> update_after) {
					$update = true;
				}
			}
			//$update = true;
			//обновляем, если нужно.
			if ($update) {
				if ($this -> obtainToken()) {
					return $this -> token;
				} else {
					return false;
				}
			} else { 
				echo "not updated<br/>";
			}
			$this -> token = $data -> access_token;
			return $this -> token;
		}
		/**
		 * Gets the token using the client secret given while constructing telfin api object.
		 * the token is stored to self::TOKEN_FILE.
		 * @return whether the request was sent.
		 */
		public function obtainToken(){
			if (($this -> client_id)&&($this -> client_secret)) {
				//echo "go";
				$ch = curl_init();
				//Чтобы ловил Fiddler
				$headers = array(
					'User-Agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:43.0) Gecko/20100101 Firefox/43.0'
				);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_URL, "https://hosted.telphin.ru/oauth/token.php");
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				
				$parameters = array(
					'grant_type' => 'client_credentials',
					//'redirect_uri' => self::handlerURI,
					'client_id' => $this -> client_id,
					'client_secret' => $this -> client_secret,
					'state' => 'ObtainToken'
				);
				curl_setopt($ch, CURLOPT_POSTFIELDS, MakeRequestString($parameters));
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				$rez = curl_exec($ch);
				curl_close($ch);
				$tokendata = json_decode($rez);
				//var_dump($tokendata);
				$tokendata -> obtained = time();
				$tokendata -> update_after = $tokendata -> obtained + $tokendata -> expires_in - 15*60;
				$this -> token = $tokendata -> access_token;
				//The constant JSON_PRETTY_PRINT was replaced by its numerical value 128 in order to use this code from the command line.
				//For some reason the JSON_PRETTY_PRINT is not defined by the shell on the server
				file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . self::TOKEN_FILE,json_encode($tokendata, JSON_PRETTY_PRINT));
				if ($this -> token) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		/**
		 * @arg string numString - the string that contains a telephone number
		 * @return sting - 11 digits of a telephone number.
		 */
		public static function giveNumberFromNumString($numString){
			$matches = array();
			//Тупо берем 11 подряд идущих цифр. Включается также первая цифра, отвечающая за страну. Например, 79523660187
			preg_match('/\d{11}/',$numString, $matches);
			return $matches[0];
		}
	}
?>