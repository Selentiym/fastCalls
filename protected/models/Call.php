<?php
	class Call {
		public $id_address;
		public $array;
		public $State;
		public $dateString;
		public $report;
		public $i;
		public $j;
		public $fio;
		public $H;
		public $wishes;
		public $birth;
		public $number;
		public $company;
		public $price;
		public $mangoTalker;
		public $comment;
		public $repair_type;
		public $id_error;
		public $IFromFile = false;
		public function __construct($array = array(), $header = array()){
			$this->array = $array;
			if ($header) {
				$num = array_flip(array_map('trim',$header));
				$this -> dateString = $array[$num["Дата"]];
				$this -> State = $array[$num["sa"]];
				$this -> report = $array[$num["Отчет по звонку"]];
				$this -> repair_type = $array[$num["Тип Описание ремонта"]];
				//$this -> i = $array[2];
				$data = $array[$num["Н"]];
				//echo $data;
				if (preg_match('/id\d+/',$data)) {
					$this -> i = str_replace("id","",$data);
					$this -> IFromFile = true;
					//echo "i";
				} elseif (preg_match('/^\d+$/',$data)) {
					$this -> j = $data;
					//echo "j";
				} elseif (is_string($data)) {
					$this -> H = $data;
					//echo "H";
				}
				//$this -> j = $array[3];
				//$this -> H = $array[4];
				$this -> fio = $array[$num["ФИО"]];
				$this -> wishes = $array[$num["Пожелания клиента"]];
				$this -> birth = $array[$num["Дата рождения"]];
				$this -> number = $array[$num["Контактный телефон"]];
				$this -> company = $array[$num["Компания"]];
				$this -> price = $array[$num["Цена"]];
				$this -> mangoTalker = $array[$num["MangoTalker номер"]];
				$this -> comment = $array[$num["Комментарий"]];
			} else {
				if (!empty($array)) {
					$data = $array[2];
					if (preg_match('/id\d+/',$data)) {
						$this -> i = str_replace("id","",$data);
					} elseif (preg_match('/^\d+$/',$data)) {
						$this -> j = $data;
					} elseif (is_string($data)) {
						$this -> H = $data;
					}
					$this -> dateString = $array[0];
					$this -> State = $array[12];
					$this -> report = $array[9];
					$this -> repair_type = $array[1];
					//$this -> i = $array[2];
					//$this -> j = $array[3];
					$this -> fio = $array[4];
					//$this -> H = $array[4];
					$this -> wishes = $array[3];
					$this -> birth = $array[5];
					$this -> number = $array[6];
					$this -> company = $array[7];
					$this -> price = $array[8];
					$this -> mangoTalker = $array[10];
					$this -> comment = $array[11];
				}
			}
		}
		public function giveDate() {
			$dateArr = explode('.', $this -> dateString);
			$rez['day'] = (int)$dateArr[0];
			$rez['mon'] = (int)$dateArr[1];
			//$rez['year'] = $dateArr[2];
			return $rez;
		}
		public function giveUnixTime(){
			$date = $this -> giveDate();
			return mktime(12,0,0,$date['mon'],$date['day'],$this -> getYear());
		}
		/**
		 * @return string - the report on the call
		 */
		public function giveReport(){
			if (preg_match('/[a-zA-Zа-яА-Я]/',$this -> report)){
				return $this -> report;
			} else {
				$arr = explode(',',$this -> report);
				$day_month = explode('/',$arr[2]);
				return 'Записан на '.$arr[0].':'.$arr[1].' '.$arr[2];
			}
		}
		/**
		 * @arg object user - the user that may be the master of this call
		 * @return boolean - whether the user is or is not the master
		 */
		public function BelongsTo($user){
			return ($user -> checkIIdentificator($this -> i) || ($user -> checkJIdentificator($this -> j)));
			//return true;
		}
		/**
		 * @return integer - the year of the call
		 */
		public function getYear(){
			//Пока что не доделано, поэтому тупо 2015
			return 2015;
		}
		/**
		 * @return integer - the time of call.
		 */
		public function giveCallTime(){
			return strtotime($this -> dateString.'.'.$this -> getYear());
			//return ($this -> dateString.'.'.$this -> getYear());
		}
		/**
		 * @arg integer from - the earliest second call is ok
		 * @return boolean - whether the call is after $from
		 */
		public function checkLowerBoundary($from){
			$time = $this -> giveCallTime();
			return ($time >= $from);
		}
		/**
		 * @arg integer to - the latest second call is ok
		 * @return boolean - whether the call is before $to
		 */
		public function checkUpperBoundary($to){
			$time = $this -> giveCallTime();
			return ($time <= $to);
		}
		/**
		 * @return string - the type of call. They are array('verifyed', 'missed', 'cancelled', 'side', 'declined', 'assigned')
		 */
		public function Classify(){
			//Если у звонка проставлен статус(поле SA), то смотрим на него.
			switch ($this ->State) {
				case 'Y':
					return 'verifyed';
				break;
				case 'N':
					return 'missed';
				break;
				case 'O':
					return 'cancelled';
				break;
			}
			//Если есть слово спам в отчете, значит звонок нецелевой.
			if (strstr($this -> report, 'спам')) {
				return 'side';
			}
			//Если в поле "отчет" есть буквы, то НЕ записан.
			if (preg_match('/[a-zA-Zа-яА-Я]/',$this -> report)) {
				return 'declined';
			} else {
				return 'assigned';
			}
		}
		/**
		 * @return interger - the id of the type of call.
		 */
		public function ClassifyId(){
			return CallType::model() -> findByAttributes(array('string' => $this -> Classify())) -> id;
		}
		/**
		 * @return object[BaseCall] | false - the model of the call corresonding to this line in the csv file. 
		 * If the DB record is not found false is returned
		 */
		public function record(){
			$criteria = new CDbCriteria;
			$criteria -> addCondition('TO_DAYS(date) = TO_DAYS(FROM_UNIXTIME('.$this -> giveUnixTime().'))');
			//$criteria -> params = array(':date' => $this -> giveUnixTime());
			$criteria -> compare('fio', $this -> fio);
			if ($this -> mangoTalker) {
				$criteria -> compare('mangoTalker', $this -> mangoTalker);
			}
			if ($this -> j) {
				$criteria -> compare('j', $this -> j);
			} else {
				$criteria -> addCondition('j IS NULL');
			}
			//echo $this -> fio."<br/>";
			
			/*echo "<br/>";
			print_r(getdate($this -> giveUnixTime()));
			echo "<br/>";*/
			if ($bcall = BaseCall::model() -> find($criteria)) {
				return $bcall;
			} else {
				return false;
			}
			//$record = BaseCall::model() -> findByAttributes(array());
		}
		/**
		 * @return object[User] - the owner of this record.
		 */
		public function giveOwner() {
			//Если задан номер направления, ищем, прежде всего, по нему.
			if ($this -> j) {
				$criteria = new CDbCriteria();
				//$criteria -> compare('jMin','<=:',$this -> j);
				$criteria -> addCondition('jMin <= '.$this -> j);
				//$criteria -> compare('jMax','>=:', $this -> j);
				$criteria -> addCondition('jMax >= '.$this -> j);
				$criteria -> compare('id_type', UserType::model() -> getNumber('doctor'));
				$users = User::model() -> findAll($criteria);
				//Ищем не только по основному интервалу, но и по дополнительному.
				$criteria = new CDbCriteria();
				//$criteria -> compare('jMin','<=:',$this -> j);
				$criteria -> addCondition('jMin_add <= '.$this -> j);
				//$criteria -> compare('jMax','>=:', $this -> j);
				$criteria -> addCondition('jMax_add >= '.$this -> j);
				$criteria -> compare('id_type', UserType::model() -> getNumber('doctor'));
				$users = array_merge($users, User::model() -> findAll($criteria));
				
				/*foreach($users as $user) {
					echo "<br/>".$user -> username;
				}*/
				switch (count($users)) {
					case 0:
						$this -> id_error = CallError::model() -> giveText('invalid_j');
						//Нужно ничего не делать.
						echo '';
					break;
					case 1:
						$this -> id_error = CallError::model() -> giveText('good');
						return current($users);
					break;
					default:
						//print_r($users);
						$this -> id_error = CallError::model() -> giveText('two_j');
						//echo "<br/><br/>".$this -> j;
						new CustomFlash('error','BaseCall','Multiple_j_assign','Номер направления'.$this -> j.' присвоен более чем одному партнеру. Из-за этого невозможно определить принадлежность звонка.',true);
						return NULL;
				}
			}
			if (!$this -> i) {
				$this -> lookForIAttribute();
				//echo "123 - ".$this -> i."<br/>";
			}
			//Если задана линия, то ищем по ней с учетом адреса.
			if ($this -> i) {
				//нашли линию с юзерами, у которых есть она.
				$phone = UserPhone::model() -> findByAttributes(array('i' => $this -> i));
				//Если нашли телефон, то берем его обладателей.
				if ($phone) {
					$users = $phone -> regular_users;
				}
				//Если их не нащлось, говорим, что их нет.
				if (!$users) {
					$this -> id_error = CallError::model() -> giveText('invalid_i');
					return NULL;
				}
				//Если оператор указал, что данный пользователь не участвует в шахматке, то не проверяем аресов.
				if (!$this -> IFromFile) {
					//Если задан адрес, то проверяем по нему
					if (($this -> H)||($this -> id_address)) {
						if (!$this -> id_address) {
							$this -> id_address = UserAddress::model() -> findByAttributes(array('address' => $this -> H)) -> id;
						}
						//Проверяем юзера на наличие нужного адреса, если объект адреса найден.
						if ($this -> id_address) {
							//echo "filter";
							$id = $this -> id_address;
							$users = array_filter($users, function ($user) use ($id){
								
								return in_array($id, CHtml::giveAttributeArray($user -> address_array,'id'));
							});
						} else {
							//Выдаем пустой результат, если не смогли определить адрес.
							//new CustomFlash('error','Call','addressNotFound','Адрес не найден');
							return NULL;
						}
					}
				}
					
				//print_r($users);
				//echo count($users);
				/*foreach($users as $user) {
					echo "<br/>".$user -> username;
				}*/
				switch (count($users)) {
					case 0:
						$this -> id_error = CallError::model() -> giveText('invalid_a');
						//new CustomFlash('error','BaseCall','Multiple_phone_assign','',true);
					break;
					case 1:
						$this -> id_error = CallError::model() -> giveText('good');
						return current($users);
					break;
					default:
						$this -> id_error = CallError::model() -> giveText('two_i');
						new CustomFlash('error','BaseCall','Multiple_phone_assign','Номер с индентификатором '.$phone -> i.' присвоен более чем одному партнеру с совпадающими адресами. Из-за этого невозможно определить принадлежность звонка.',true);
						return NULL;
				}
				//print_r($phone -> regular_users);
			}
			$this -> id_error = CallError::model() -> giveText('no_data');
			return NULL;
		}
		/**
		 * Checks the ClientPhone table and sets $this -> i if there is a record corresponding to user's mangoTalker.
		 */
		public function lookForIAttribute(){
			$CPhone = ClientPhone::model() -> findByAttributes(array('mangoTalker' => $this -> mangoTalker), array('with' => 'phone'));
			if ($CPhone) {
				$this -> i = $CPhone -> phone -> i;
			} else {
				unset($this -> i);
			}
		}
		/**
		 * Sets the attributes of the given BaseCall model with own attributes.
		 * @arg object[BaseCall] record - the record attributes of which are to be set
		 */
		public function setRecordAttributes($record){
			$record -> repair_type = $this -> repair_type;
			$record -> i = $this -> i ? $this -> i : NULL;
			$record -> j = $this -> j ? $this -> j : NULL;
			$record -> H = $this -> H ? $this -> H : NULL;
			
			$record -> wishes = $this -> wishes;
			$record -> fio = $this -> fio;
			$record -> birth = $this -> birth;
			$record -> number = $this -> number;
			$record -> company = $this -> company;
			$record -> price = $this -> price;
			$record -> price = $this -> price;
			$record -> report = $this -> report;
			$record -> mangoTalker = $this -> mangoTalker;
			$record -> comment = $this -> comment;
			$record -> date = $this -> giveUnixTime();
			
			$owner = $this -> giveOwner();
			
			$record -> id_error = $this -> id_error;
			
			//var_dump($owner);
			//Если не смогли найти владельца, то не заполняем это поле.
			$record -> id_user = $owner ? $owner -> id : NULL;
			$record -> id_call_type = $this -> ClassifyId();
		}
		/**
		 * @return string - full name if user is admin and short name for others
		 */
		public function giveName(){
			if (Yii::app() -> user -> checkAccess('admin')) {
				return $this -> fio;
			} else {
				$words = array_map('trim',explode(' ',$this -> fio));
				$rez = substr($words[0],0,4).'. ';
				unset($words[0]);
				foreach ($words as $word) {
					$rez.=''.substr($word,0,2).'.';
				}
				return $rez;
			}
		}
	}
?>