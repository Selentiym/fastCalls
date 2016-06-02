<?php
	/**
	 * This is a class of a call that takes information from the telfin API and Google Doc API
	 */
	class FastCall extends aCall {
		/**
		 * @property string mangoTalker - the number of the caller
		 */
		public $mangoTalker;
		/**
		 * @property bool inited - thether the call object was successfully inited or not.
		 */
		public $inited;
		/**
		 * @var string caller - number of the caller.
		 */
		public $caller;
		/**
		 * @var BaseCall bCall - corresponding database record object.
		 */
		public $bCall;
		/**
		 * @var UserPhone called - object of the phone that was called.
		 */
		public $called;
		/**
		 * @var TelfinCall tCall - corresponding telfin call object. It links telfin 
		 * with this system. One FastCall => BaseCall object can have more than one TelfinCall
		 */
		public $tCall;
		/**
		 * @var integer - UNIX time of the call
		 */
		private $unix;
		
		/**
		 * @arg string caller - number of the caller
		 * @arg UserPhone called - the called number. May contain IVR number.
		 * @arg TelfinCall tCall - corresponding telfin call object
		 */
		public function __construct($caller, UserPhone $called, TelfinCall $tCall = NULL){
			echo "ok";
			$this -> caller = $caller;
			$this -> called = $called;
			
			if ($tCall) {
				$this -> tCall = $tCall;
				$time = strtotime($tCall -> time);
				if (abs($time - time()) < 45 * 24 * 3600 ) {
					$this -> unix = $time;
				}
			}
			
			//Если не вышло задать время из tCall, то берем настоящий момент.
			if (!$this -> unix) {
				$this -> unix = time();
			}
			if ($this -> storeData()){
				$this -> inited = true;
				//Ищем, нет ли этой строки гугл дока уже в базе данных
				$rec = $this -> record();
				//vardump($rec);
				if ($rec) {
					echo "Найдена соответствующая запись в базе: ".$rec -> id."<br/>";
					$this -> bCall = $rec;
					if ($tCall) {
						//Сохраняем путь телфиновского звонка.
						$tCall -> id_call = $rec -> id;
						echo "save data to telfinCall<br/>";
						if (!$tCall -> save()){
							vardump($tCall -> getErrors());
						}
					}
					echo "not new";
					return;
				} else {
					//Создаем новый праобраз строки в БД и заносим в нее данные
					$this -> bCall = new BaseCall();
					$this -> bCall -> storeData($this);
					return;
				}
			} else {
				//Сохраняем данные о том, что запись гугл дока не найдена.
				if (is_a($this -> tCall, 'TelfinCall')) {
					$this -> tCall -> id_error = CallError::model() -> giveText('no_record');
					$this -> tCall -> save();
				}
				//Говорим, что установка параметров неудачна.
				$this -> inited = false;
				return;
			}
		}
		/**
		 * This function searches the Google Doc file for the line that is needed and initializes the fields by the found line.
		 */
		/**
		 * google doc output:
		 * array(14) { ["дата"]=> string(4) "3.01" ["типописаниеремонта"]=> string(0) "" ["н"]=> string(0) "" ["пожеланияклиента"]=> string(0) ""
		 * ["фио"]=> string(0) "" ["датарождения"]=> string(0) "" ["контактныйтелефон"]=> string(0) "" ["компания"]=> string(0) ""
		 * ["цена"]=> string(0) "" ["отчетпозвонку"]=> string(30) "записан, уточнял" ["mangotalkerномер"]=> string(11) "79217999294" 
		 * ["комментарий"]=> string(0) "" ["направление"]=> string(0) "" ["sa"]=> string(0) "" } }
		 */
		private function storeData () {
			$api = new GoogleDocApiHelper();
			//Ищем сначала только сегодня
			$entry = $this -> findGoogleRecord($api, true);
			if (!$entry) {
				//Ищем за весь текущий месяц
				echo "not today";
				$this -> id_error = CallError::model() -> giveText('not_today');
				$entry = $this -> findGoogleRecord($api, false);
				
				if (!$entry) {
					//Ищем за предыдущий месяц
					echo "not even currrent month";
					$this -> id_error = CallError::model() -> giveText('not_this_month');
					$entry = $this -> findGoogleRecord($api, false, -1);
					
					if (!$entry) {
						//Если вообще не найдено, то ничего не делаем, возвращаем false.
						$this -> id_error = CallError::model() -> giveText('gline_not_found');
						echo "not found at all!";
						return false;
					}
				}
			}
			//Копируем данные с зиписи гугл дока в объект.
			$entry = $entry -> getValues();
			$this -> State = $entry["sa"];
			$this -> dateString = $entry["дата"];
			$this -> report = $entry["отчетпозвонку"];
			$this -> i = $this -> called -> id;
			$this -> mangoTalker = $entry["mangotalkerномер"];
			$this -> H = $entry["н"];
			$this -> wishes = $entry["пожеланияклиента"];
			$this -> fio = $entry["фио"];
			$this -> birth = $entry["датарождения"];
			$this -> number = $entry["контактныйтелефон"];
			$this -> company = $entry["компания"];
			$this -> price = $entry["цена"];
			$this -> repair_type = $entry["типописаниеремонта"];
			$this -> comment = $entry["комментарий"];
			//vardump($entry);
			//vardump($this);

			//Теперь устанавливаем атрибуты "тип звонка" и "дата" ,тк она может отличаться от unixTime
			// и "владелец", которой ищется по номеру.
			//Задали тип звонка
			$this -> id_call_type = $this -> ClassifyId();
			//Теперь разбираемся с датой
			$this -> setCallTime();
			//И ищем владельца.
			$this -> id_user = $this -> giveOwner() -> id;
			return true;
		}
		/**
		 * @arg GoogleDocApiHelper api - the api object to be used.
		 * @arg bool today - whether to search only today or for the whole month too.
		 * @arg integer month - which month to search. -1 means the previous month. 0 - the current month.
		 * can take only negative values
		 * @return Google\Spreadsheet\ListEntry - the entry that corresponds 
		 * to the call and to the given search time 
		 */
		private function findGoogleRecord(GoogleDocApiHelper $api, $today = true, $month = 0){
			//По очереди перебираем все варианты области поиска и формата для mangoTalker.
			if ($entry = $this -> findGoogleRecord_lowLevel($api, $today, $month, true, true)) {
				return $entry;
			}
			if ($entry = $this -> findGoogleRecord_lowLevel($api, $today, $month, false, true)) {
				return $entry;
			}
			if ($entry = $this -> findGoogleRecord_lowLevel($api, $today, $month, true, false)) {
				return $entry;
			}
			if ($entry = $this -> findGoogleRecord_lowLevel($api, $today, $month, false, false)) {
				return $entry;
			}
		}
		/**
		 * @arg GoogleDocApiHelper api - the api object to be used.
		 * @arg bool today - whether to search only today or for the whole month too.
		 * @arg integer month - which month to search. -1 means the previous month. 0 - the current month.
		 * can take only negative values
		 * @arg bool real - whether to use the original googleDoc or not.
		 * @arg bool mangoText - whether to search mangoTalker as text or as number.
		 * @return Google\Spreadsheet\ListEntry - the entry that corresponds 
		 * to the call and to the given search time 
		 */
		private function findGoogleRecord_lowLevel(GoogleDocApiHelper $api, $today = true, $month = 0, $real = false,$mangoText = true){
			//Если нужно искать по копии, то убрать!
			if (!$real) { return;}
			if (($api -> success)&&($this -> caller)) {
				//Выбираем нужный месяц.
				if ($real) {
					$api -> setWorkArea('Ремонт СПб', $this -> giveWorksheetLabel($month));
				} /*else {
					$api -> setWorkArea('Copy of СТАТИСТИКА СПб', $this -> giveWorksheetLabel($month));
				}*/
				$date = getdate($this -> giveUnixTime());
				//Ставим или не ставим ковычки в зависимости от того, ищем ли мы текст или число.
				$quotes = $mangoText ? '"' : '';
				//Если Мы хотим сегодня И нет отрицательного сдвига по месяцу
				if (($today)&&($month >= 0)) {
					//Пытаемся найти звонок в этот день.
					$queryString = sprintf(
						'дата = %d.%02d and mangotalkerномер = '.$quotes.'%s'.$quotes,
						(int)$date['mday'],(int)$date['mon'],$this -> caller
					);
				} else {
					//Просто пытаемся найти звонок с этого номера.
					$queryString = sprintf(
						'mangotalkerномер = '.$quotes.'%s'.$quotes,
						$this -> caller
					);
				}
				echo $queryString.'<br/>';
				
				//Обращаемся к гугл доку за информацией.
				$data = $api -> giveData (array('sq' => $queryString));
				if (!$data) {
					$entries = array();
				} else {
					//Получаем строки
					$entries = $data->getEntries();
				}
				if (count($entries) > 0) {
					$entry = end($entries);
				} else {
					$entry = false;
				}
				//Отдаем последнюю найденную строку, если есть.
				return $entry;
			}
		}
		/**
		 * @arg string monthDiff - month that shall be looked for the call data
		 * @return string - title of a worksheet. It uses the month and year
		 */
		private function giveWorksheetLabel($monthDiff){
			$time = $this -> giveUnixTime();
			if ($monthDiff < 0) {
				$dateInfo = getdate(strtotime($monthDiff.' month',$time));
			} else {
				$dateInfo = getdate($time);
			}
			return $dateInfo['month'].' '.$dateInfo['year'];
			//return 'January 2016';
		}
		/**
		 * @return integer - unix time of call
		 * Если дата записи попала на другой месяц, необходимо
		 * соответсвующим образом поменять время звонка, чтобы
		 * запись попала на нужный месяц.
		 */
		private function setCallTime() {
			//Делаем что-либо только если в отчете, действительно, есть дата
			if (!preg_match('/[a-zA-Zа-яА-Я]/',$this -> report)) {
				//Дата записи из отчета
				$arr = array_values(array_filter(array_map('trim',explode(' ', $this -> report))));
				//11.30 3.01 - формат даты
				$rDate = $arr[1];
				list($rDay, $rMonth) = array_filter(array_map('trim',explode('.',$rDate)));
				if (!$rMonth) {
					list($rDay, $rMonth) = array_filter(array_map('trim',explode('/',$rDate)));
				}//Получили месяц и день записи.
				//Получаем настоящее время
				$cTimeArr = getDate($this -> unix);
				$cMonth = $cTimeArr['mon'];
				$year = $cTimeArr['year'];
				//Если вдруг оказывается, что настоящий месяц больше месяца записи, то нужно прибавить год
				//к записи относительно настоящего времени (мы попали на границу двух лет)
				if ($cMonth != $rMonth) {
					$this -> prev_month = 1;
					if ($cMonth > $rMonth) {
						$year ++;
					}
				}
				list($rHour, $rMin) = explode('.',$arr[0]);
				$time = mktime($rHour,$rMin,0,$rMonth,$rDay,$year);
				//Если время записи не слишком сильно отличается от времени звонка, то заменяем одно другим.
				if (abs($this -> unix - $time) < 60 * 24 * 60 * 60) {
					$this -> unix = $time;
				}
				return $this -> unix;
			}
		}
		/**
		 * @arg object user - the user that may be the master of this call
		 * @return boolean - whether the user is or is not the master
		 */
		public function BelongsTo( User $user){
			return in_array($this -> phone, $user -> phones);
			//return true;
		}
		/**
		 * @return User - the owner of this call. The person who invited the patient.
		 */
		public function giveOwner(){
			if (is_a($this -> called, 'UserPhone')) {
				$users = $this -> called -> regular_users;
			} else {
				//На всякий случай еще раз ставим ошибку, если ее пока нет.
				if (!$this -> id_error) {
					$this -> id_error = CallError::model() -> giveText('no_number');
				}
				return NULL;
			}
			switch (count($users)) {
				case 0:
					$this -> id_error = CallError::model() -> giveText('invalid_i');
					return NULL;
				break;
				case 1:
					//Устанавливаем статус, что все ок, только если он был пуст.
					if (!$this -> id_error) {
						$this -> id_error = CallError::model() -> giveText('good');
					}
					return current($users);
				break;
				default:
					$this -> id_error = CallError::model() -> giveText('many_users');
					return NULL;
				break;
			}
		}
		/**
		 * @return integer - UNIX time of the call
		 */
		public function giveUnixTime(){
			return $this -> unix;
			//return 1451768400 + 3600 *5;
		}
		/**
		 * Saves information about the $this -> bCall if needed.
		 */
		public function MakeDatabaseChanges(){
			//Делаем что-то только если мы нашли запись гугл дока => объект bCall
			if ($this -> bCall) {
				//Если запись, соответсвующая пришедшему звонку, новая, то сохраняем ее.
				if ($this -> bCall -> isNewRecord) {
					echo "New record<br/>";
					$this -> bCall -> sendSms = true;
					if ($this -> bCall -> save()) {
						echo "Call save successful<br/>";
						echo "ErrorCode: ".$this -> bCall -> id_error."<br/>";
					} else {
						echo "Could not save<br/>";
					}
				} else {
					//echo "Found record in database.<br/>";
					//Если же запись не новая, то call содержит обновленную информацию, а
					// bCall - старую. Сравниваем, изменился ли статус звонка на отмену,
					//и если да, то обновляем.
					if (($this -> id_call_type == CallType::model() -> getNumber('cancelled'))&&($this -> id_call_type != $this -> bCall -> id_call_type)) {
						$this -> bCall -> id_call_type = $this -> id_call_type;
						$this -> bCall -> sendSms = true;
						$this -> bCall -> save();
						echo "Cancelled<br/>";
					}
				}
			}
		}
	}
?>