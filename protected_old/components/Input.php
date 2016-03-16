<?php
	class Input {
		public $from;
		public $to;
		public $phone;
		public function __construct($array = array()){
			//Сохраняем номера клиента и линии, предварительно отрезав лишний текст.
			//Считается, что номер заключен между "_" и "@".
			/*$this -> from = $this -> prepareString($array[3]);
			$this -> to = $this -> prepareString($array[2]);*/
			$this -> from = $this -> prepareString($array[2]);
			$this -> to = $this -> prepareString($array[3]);
			//echo $this -> from;
			//echo $this -> to;
			//print_r($array);
		}
		/**
		 * @arg string string - the string to be prepared (only part between "_" and "@" is taken)
		 * @return string - the rezult of preparations.
		 */
		public function prepareString($string){
			//echo $string."<br/>";
			if ($pos_dog = strpos($string,'@')) {
				$string = substr($string, 0, $pos_dog);
			}
			
			if (preg_match('/^sip:.*/',$string)) {
				$string = str_replace('sip:','', $string);
				//echo "match";
			}
			
			if ($pos_ = strpos($string,'_')) {
				$string = substr($string,$pos_ + 1);
			}
			$string = str_replace("+","",$string);
			//echo $string."<br/>";
			return $string;
		}
		/**
		 * @return object[ClientPhone] | false - the model of the number corresonding to this line in the csv file. 
		 * If the DB record is not found false is returned
		 */
		public function record(){
			$criteria = new CDbCriteria;
			$criteria -> compare('mangoTalker', $this -> from);
			
			if ($cphone = ClientPhone::model() -> find($criteria)) {
				return $cphone;
			} else {
				return false;
			}
			//$record = BaseCall::model() -> findByAttributes(array());
		}
		/**
		 * @return object[UserPhone] - the model of the phone which corresponds to $this -> to number.
		 */
		public function givePhone(){
			if (!isset($this -> phone)) {
				//echo $this -> from.' -> '.$this -> to;
				$this -> phone = UserPhone::model() -> findByAttributes(array('number' => $this -> to));
			}
			return $this -> phone;
		}
		/**
		 * Sets the attributes of the given ClientPhone model with own attributes.
		 * @arg object[ClientPhone] record - the record attributes of which are to be set
		 */
		public function setRecordAttributes($record){
			$record -> mangoTalker = $this -> from;
			if ($phone = $this -> givePhone()) {
				$record -> id_phone = $phone -> id;
				//echo $phone -> id;
				return $record;
			} else {
				return false;
			}
		}
	}
?>