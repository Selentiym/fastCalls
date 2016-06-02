<?php
	abstract class aCall implements iCall {
	//class aCall implements iCall {
		public $id_address;
		public $State;
		public $dateString;
		public $report;
		public $i;
		public $j;
		public $H;
		public $wishes;
		public $fio;
		public $birth;
		public $number;
		public $company;
		public $price;
		public $repair_type;
		public $comment;
		public $id_error;
		
		public $id_call_type;
		
		
		
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
			//Если есть слово отмена в отчете, значит отмена.
			if (strstr($this -> report, 'отмена')) {
				return 'cancelled';
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
		 * @return BaseCall | false - the model of the call corresonding to the object
		 * If the DB record is not found false is returned
		 */
		public function record(){
			$criteria = new CDbCriteria;
			$this -> id_call_type = $this -> ClassifyId();
			//Проблема, тк время может быть проставлено по записи.
			/*if (!(($this -> id_call_type == CallType::model() -> getNumber('verifyed'))||($this -> id_call_type == CallType::model() -> getNumber('assigned')))) {
				$criteria -> addCondition('TO_DAYS(date) = TO_DAYS(FROM_UNIXTIME('.$this -> giveUnixTime().'))');
			} else {*/
			//Мы вообще можем не знать времени или даты звонка, поэтому ищем по отчету
			//Отчет мог измениться, поэтому по нему искать как раз-таки нельзя!
			/*$criteria -> compare('report', $this -> report);
			if (!$this -> report) {
				$criteria -> addCondition('report IS NULL');
			}*/
			$criteria -> compare('fio', $this -> fio);
			if ($this -> mangoTalker) {
				$criteria -> compare('mangoTalker', $this -> mangoTalker);
			}
			if ($this -> price) {
				$criteria -> compare('price', $this -> price);
			} else {
				$criteria -> addCondition('price IS NULL');
			}
			if ($this -> dateString) {
				$criteria -> compare('dateString', $this -> dateString);
			} else {
				$criteria -> addCondition('dateString IS NULL');
			}
			if ($this -> repair_type) {
				$criteria -> compare('repair_type', $this -> repair_type);
			} else {
				$criteria -> addCondition('repair_type IS NULL');
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
	}
?>