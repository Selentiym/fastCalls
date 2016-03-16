<?php
	class UserFromFile {
/* The followings are the available columns in table 'user': */
		//public $id
		public $password;
		public $username;
		public $fio;
		public $email;
		public $tel;
		public $card_number;
		public $bik;
		public $bank_account;
		public $webmoney;
		public $i;
		public $jMin;
		public $jMax;
		public $conditions;
		public $conditions_add;
		public $create_time;
		public $id_type;
		public $id_parent;
		public $id_speciality;
		
		
/* The following are other properties that are used for help*/
		public $addresses = array();
		public $password_change = '';
		public $password_change_second = '';
		public $phones_input;
		public $speciality = '';
		public $parent;
		public $children;
		public $input_type = 'mainForm';
		
		
		public function __construct($array = array(), $header = array()) {
			if ($header) {
				$nums = array_flip(array_map('trim',$header));
				
				if (!empty($array)) {
					$data = trim($array[$nums['Специальность']]);
					$id = UserSpeciality::model() -> findByAttributes(array('name' => $data)) -> id;
					if ($id) {
						$this -> speciality = array($id);
					} else {
						$this -> speciality = array($data);
					}
					//Если специализация не "Медпред", то ставим тип юзера и ищем медпреда. 
					//Если же он сам медпред, то просто ставим тип и родителем объявляем залогиненного юзера.
					if ($data != 'Медпред') {
						$this -> id_type = UserType::model() -> getNumber('doctor');
						$this -> id_parent = User::model() -> findByAttributes(array('fio' => trim($array[$nums['Медпред']]),'id_type' => UserType::model() -> getNumber('mainDoc'))) -> id;
					} else {
						$this -> id_type = UserType::model() -> getNumber('mainDoc');
						$this -> id_parent = Yii::app() -> user -> getId();
					}
					$this -> username = trim($array[$nums['log']]);
					$this -> password_change = $this -> FourDigits(trim($array[$nums['pass (если 3 на первом месте 0)']]));
					$this -> password_change_second = $this -> password_change;
					$this -> fio = trim($array[$nums['Врач (ФИО)']]);
					$this -> email = trim($array[$nums['email']]);
					$this -> jMin = trim($array[$nums['Номер инфобланка с']]);
					$this -> jMax = trim($array[$nums['Номер инфобланка по']]);
					$this -> webmoney = trim($array[$nums['WM']]);
					
					$data = trim($array[$nums['Start']]);
					$data = explode('.',$data);
					/* Fix: year not only 2015 */
					$time = mktime(12,0,0,$data[1],$data[0],2015);
					$this -> create_time = new CDbExpression('FROM_UNIXTIME('.$time.')');
					//$this -> addresses = trim($array[$nums['Адрес']]);
					$data = explode('/',trim($array[$nums['Условия работы']]));
					$this -> conditions = $data[0];
					$this -> conditions_add = $data[1] ? $data[1] : NULL;
					
					$this -> webmoney = trim($array[$nums['WM']]);
					$this -> card_number = trim($array[$nums['Карта']]);
					$this -> tel = trim($array[$nums['Телефон личный']]);
					$data = array_filter(explode('/',trim($array[$nums['БИК/СЧЕТ']])));
					$this -> bik = $data[0];
					$this -> bank_account = $data[1];
					/* phones block */
					$data = explode('/',trim($array[$nums['Телефон']]));
					/*echo "<br/>";
					print_r($data);
					echo "<br/>";//*/
					$this -> phones_input = array();
					foreach($data as $phone){
						$obj = UserPhone::model() -> findByAttributes(array('number' => $phone));
						if ($obj) {
							$this -> phones_input[] = $obj -> id;
						} elseif ($this -> id_type == UserType::model() -> getNumber('mainDoc')){
							$obj = new UserPhone;
							//$maxI = Yii::app()->db->createCommand('SELECT MAX(`i`) FROM {{phone}}')->queryScalar();
							//echo $maxI.' - maxi<br/>';
							$obj -> i = 0;
							$obj -> number = $phone;
							if ($obj -> save()) {
							//if (1) {
								$this -> phones_input[] = $obj -> id;
								new CustomFlash('warning','UserPhone','NotFound','Номер телефона '.$phone.' не был найден в таблице линий при добавлении медпреда. Ему был присвоен идентификатор '.$obj -> i, true);
							} else {
								new CustomFlash('error','UserPhone','CanNotSave','Не удалось добавить линию <'.$phone.'> .',true);
							}
						}
					}
					/* addresses block */
					$data = array_filter(explode('/',trim($array[$nums['Адрес']])));
					$this -> addresses = array();
					//print_r($data);
					//echo $array[$nums['Адрес']];
					foreach($data as $addr) {
						$obj = UserAddress::model() -> findByAttributes(array('address' => $addr));
						$this -> addresses[] = $obj -> id ? $obj -> id : $addr;
						
					}
					//echo "user: ".$this -> fio." : ";
					//print_r($data);
					//print_r($this -> addresses);
					//echo "<br/>";
					//print_r($this -> addresses);
					
				}
			}//*/
			//echo '1 ';
		}
		/**
		 * @arg object[User] user - the User model the attributes of which are to be set
		 * @return object[User] - the same object with properties set.
		 */
		public function setUserAttributes($user){
			if ($user) {
				$reflect = new ReflectionClass($this);
				$public = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
				foreach($public as $o){
					$nm = (string)$o -> name;
					$user -> $nm = $this -> $nm;
					/*if (is_array($this -> $nm)) {
						echo $nm.': ';
						print_r($this -> $nm);
						echo "<br/>";
					} else {
						echo $nm.': '.$user -> $nm."<br/>";
					}*/
				}
				return $user;
			} 
			return false;
		}
		/**
		 * @arg string arg - string to be processed
		 * @return string - string which has been extended by zerof from left to contain 4 digits
		 */
		public function FourDigits($arg){
			if (strlen($arg) < 4) {
				return $this -> FourDigits('0'.$arg);
			} else {
				return $arg;
			}
		}
	}
?>