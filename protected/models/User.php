<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $password
 * @property string $username
 * @property string $fio
 * @property string $email
 * @property integer $i
 * @property integer $jMin
 * @property integer $jMax
 * @property integer $conditions
 * @property string $create_time
 * @property boolean $allowPatients
 * @property integer $id_type
 * @property integer $id_speciality
 */
class User extends UModel
{
	const SMS_SEND = '1';
	/**
	 * @var string $_childrenIdString contains all chuildren's ids separated by commas
	 */
	private $_childrenIdString;

	public $addresses = array();
	public $password_change = '';
	public $password_change_second = '';
	public $phones_input;
	public $speciality = '';
	public $parent;
	public $children;
	public $input_type;
	public $input_options;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username', 'required'),
			array('id_type, id_speciality', 'numerical', 'integerOnly'=>true),
			array('password, email', 'length', 'max'=>128),
			array('conditions', 'length', 'max'=>50),
			array('username', 'length', 'max'=>20),
			array('fio', 'length', 'max'=>500),
			array('create_time', 'safe'),
			// The following rule is used by search().
			array('id, username, fio, email, i, create_time, id_type, id_speciality', 'safe', 'on'=>'search'),
			array('id, id_parent', 'unsafe', 'on'=>'create'),
			array('input_type', 'safe'),
			array('jMin, jMax, jMin_add, jMax_add, addresses, password_change, password_change_second,phone,tel,speciality, phones_input, id_mentor, username, conditions, conditions_add, bik,card_number,webmoney,bank_account,allowPatients,input_options', 'safe', 'on'=>'create'),
			array('jMin, jMax, jMin_add, jMax_add, addresses, password_change, password_change_second,phone,tel,speciality, phones_input, id_mentor, username, conditions, conditions_add, bik,card_number,webmoney,bank_account,allowPatients,input_options', 'safe', 'on'=>'updateByAdmins'),
			array('*', 'unsafe', 'on'=>'SelfUpdate'),
			array('bik,card_number,webmoney,bank_account', 'safe', 'on'=>'SelfUpdate'),
			array('password', 'unsafe'),
		);
	}
	/*public function __construct(){
		call_user_func_array(array("parent", __construct), func_get_args());
		$this -> prepareCalls();
	}*/
	public function setParent() {
		if (!(isset($this -> parent))) {
			$this -> parent = User::model() -> findByPk($this -> id_parent);
			if (!$this -> parent) {
				$this -> parent = new User;
			}
		}
		return $this -> parent;
	}
	public function getChildren() {
		if (!$this -> children) {
			$this -> children = $this -> findAllByAttributes(array('id_parent' => $this -> id));
		}
		return $this -> children;
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'type' => array(self::BELONGS_TO, 'UserType', 'id_type'),
			'userSpeciality' => array(self::BELONGS_TO, 'UserSpeciality', 'id_speciality'),
			'address_array' => array(self::MANY_MANY, 'UserAddress', '{{address_assignments}}(id_user, id_address)'),
			'options' => array(self::MANY_MANY, 'UserOption', '{{user_option_assignments}}(id_user, id_option)'),
			'phones' => array(self::MANY_MANY, 'UserPhone', '{{phone_assignments}}(id_user, id_phone)'),
			'mentor' => array(self::BELONGS_TO, 'UserMentor', 'id_mentor'),
			'calls' => array(self::HAS_MANY,'BaseCall', 'id_user'),
			'reviews' => array(self::HAS_MANY,'Review', 'id_user'),
			'patients' => array(self::HAS_MANY,'Patient', 'id_user', 'order'=>'patients.create_time DESC'),
			//'children' => array(self::HAS_MANY,'User','id_parent'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'password' => 'Пароль',
			'username' => 'Имя пользователя',
			'fio' => 'Фамилия Имя Отчество',
			'email' => 'e-mail',
			'i' => 'I',
			'j' => 'J',
			'create_time' => 'Дата регистрации',
			'id_type' => 'Тип пользователя',
			'id_speciality' => 'Специализация пользователя',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('fio',$this->fio,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('i',$this->i);
		$criteria->compare('j',$this->j);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('id_type',$this->id_type);
		$criteria->compare('id_speciality',$this->id_speciality);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function UserCreate($data){
		$model = new User('create');
		$model -> attributes = $data;
		$model -> save();
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * Returnes the address string that contains from addresses delimited by $del.
	 * @arg string del - the delimeter
	 * @return - look higher
	 */
	public function giveAddressString($del = ','){
		/*$address = '';
		foreach($user -> addresses as $address) {
			$address .= $address -> address . $del . ' ';
		}
		$address = substr($address, 0, strrpos($address, $del));
		return $address;*/
		return $this -> giveStringFromArray($this -> address_array, $del, 'address');
	}
	/**
	 * Function to be used in ViewModel action to have more flexibility
	 * @arg mixed arg - the argument populated from the controller.
	 * @return User
	 */
	public function customFind($arg){
		switch ($this -> scenario) {
			case 'searchById':
				return $this -> findByPk($arg);
				break;
			default:
				$obj = false;
				if ($arg) {
					if ((!(int)$arg)||(preg_match('/^\d{10}$/',$arg))) {
						$criteria = new CDbCriteria;
						$criteria -> compare('username',$arg, false);
						$obj = $this -> find($criteria);
					} else {
						$obj = $this -> findByPk($arg);
					}
					//var_dump($obj);
				}
				if (!$obj) {
					$obj = $this -> findByPk(Yii::app() -> user -> getId());
				}
				return $obj;
				break;
		}
	}
	public function checkIIdentificator($i) {
		if (!$this -> phones) {
			return false;
		}
		foreach ($this -> phones as $phone) {
			if ($phone -> i == $i) {
				return true;
			}
		}
		return false;
		
		//return (($this -> i == $i)&&($this -> i));
	}
	public function checkJIdentificator($j){
		return ((($j >= $this -> jMin)&&($j<=$this -> jMax))&&($this -> jMin)&&($this -> jMax));
	}
	public function isLoggedIn(){
		return ($this -> id == Yii::app() -> user -> getId());
	}
	public function giveUserNameForPage() {
		if (($this -> isLoggedIn())||(!$this -> username)) {
			return '';
		} else {
			return '/'.$this -> username;
		}
	}
	public function beforeSave(){
		//Проверяем на наличие дубля.
		if ($this -> isNewRecord) {
			$dups = $this -> findByAttributes(array('username' => $this -> username));
			if ($dups) {
				new CustomFlash('error','User', 'DuplUsername', 'Данное имя пользователя уже занято, выберите другое.');
				return false;
			}
			if (($doctors = $this -> giveByCoordinates($this -> addresses, $this -> phones_input))&&($this -> id_type == UserType::model() -> getNumber('doctor'))){
				new CustomFlash('error','User', 'DuplCoordinates', 'Врач по имени '.current($doctors) -> fio.' уже имеет тот же адрес и линию, что и создаваемый.',true);
				return false;
			}
		} else {
			if (($doctors = $this -> giveByCoordinates($this -> addresses, $this -> phones_input))&&($this -> id_type == UserType::model() -> getNumber('doctor'))){
				if ((count($doctors) > 1) || (current($doctors) -> id != $this -> id)) {
					new CustomFlash('error','User', 'DuplCoordinates', 'Врач по имени '.current($doctors) -> fio.' уже имеет тот же адрес и линию, что и редактируемый.',true);
					return false;
				}
			}
		}
		//Проверка на наличие одинаковых номеров у Медпредов.
		if ($this -> id_type == UserType::model() -> getNumber('mainDoc')) {
			$uid = $this -> id;
			if (!(empty($this -> phones_input))) {
				foreach ($this -> phones_input as $id) {
					$phone = UserPhone::model() -> findByPk($id, array('with'=>array('main_users')));
					$main_users = $phone -> main_users;
					if (!(empty($main_users))) {
						$main_users = array_filter($main_users, function($user) use ($uid) {
							return $user -> id != $uid;
						});
					}
					/*if ($key = array_search($this, $main_users)) {
						echo "unset";
						unset($main_users[$key]);
					}*/
					$num = count($main_users);
					if ($num > 0){
						new CustomFlash('warning','User', 'DuplLine'.$phone -> number, 'Медпред'. (($num > 1) ? 'ы' : '' ).' по имени '.$this -> giveStringFromArray($main_users, ',','fio').' уже име'. (($num > 1) ? 'ют' : 'ет' ).' ту же линию '.$phone -> number.', что и '.$this -> fio.'.',true);
					}
				}
			}
			
			
		}
		//Данные приходят с select2, который настроен на массив
		if ($this -> scenario != 'SelfUpdate') {
			$this -> speciality = is_array($this -> speciality) ? current($this -> speciality) : NULL;
			if ((!(int)$this -> speciality)&&($this -> speciality)) {
				$spec = new UserSpeciality();
				$spec -> name = $this -> speciality;
				if ($spec -> save()) {
					$this -> id_speciality = $spec -> id;
				}
				//echo "Add new spec";
			} else {
				//echo "Use existing spec:";
				//print_r($this -> speciality);
				$this -> id_speciality = $this -> speciality;
			}
		}
		if (($this -> password_change)||($this -> password_change_second)) {
			if ($this -> password_change == $this -> password_change_second) {
				$this -> password = CPasswordHelper::hashPassword($this -> password_change);
			} else {
				new CustomFlash('error', 'User', 'Passwords_not_match', 'Введенные пароли не совпадают, попробуйте еще раз.', true);
				return false;
			}
		}
		//Проверка на пересечение интервалов.
		$criteria = new CDbCriteria;
		$search = false;
		if (($this -> jMin) && ($this -> jMax)) {
			$criteria -> addCondition ('(jMax - '.$this -> jMin.') * ('.$this -> jMax.' - jMin) >= 0', 'OR');
			$criteria -> addCondition ('(jMax_add - '.$this -> jMin.') * ('.$this -> jMax.' - jMin_add) >= 0', 'OR');
			$search = true;
		}
		if (($this -> jMin_add) && ($this -> jMax_add)) {
			$criteria -> addCondition ('(jMax - '.$this -> jMin_add.') * ('.$this -> jMax_add.' - jMin) >= 0', 'OR');
			$criteria -> addCondition ('(jMax_add - '.$this -> jMin_add.') * ('.$this -> jMax_add.' - jMin_add) >= 0', 'OR');
			$search = true;
		}
		$criteria -> compare('id_type',$this -> id_type);
		$criteria -> addNotInCondition('id', array($this -> id));
		/*if ((($this -> jMin_add) && ($this -> jMax_add)) or (($this -> jMin) && ($this -> jMax))) {
			
			$search = true;
		}*/
		
		if (($dup = User::model() -> find($criteria))&&($search)) {
			new CustomFlash('warning','User','UpdateRanges','Пользователь '.$dup -> fio.' уже выбранные направления. Изменения в номерах направлений не сохранены.',true);
			$obj = $this -> findByPk($this -> id);
			$this -> jMin = $obj -> jMin;
			$this -> jMax = $obj -> jMax;
			$this -> jMax_add = $obj -> jMax_add;
			$this -> jMin_add = $obj -> jMin_add;
		}
		
		/*$this -> parent = $this -> findByPk($this -> id_parent);
		//Проверяем jmin, jmax: есть ли у главного врача этого юзера эти номера
		if (($this -> jMin < $this -> parent -> jMin)&&($this -> jMin)) {
			new CustomFlash('error', 'User', 'jMin_not_valid', 'Неверное значение меньшего номера назначенного направления.', true);
			return false;
		}
		if (($this -> jMax > $this -> parent -> jMax)&&($this -> jMax)) {
			new CustomFlash('error', 'User', 'jMax_not_valid', 'Неверное значение большего номера назначенного направления.', true);
			return false;
		}*/
		return parent::beforeSave();
		//return true;
	}
	public function afterSave() {
		//print_r($this -> addresses);
		//Добавялем опции юзеру
		if (($this -> input_type == 'mainForm')) {
			//Опции, которые должны быть у юзера
			$inp = $this -> input_options;
			if (empty($inp)) {
				$inp = array();
			}
			//Опции, которые уже есть
			$has = CHtml::giveAttributeArray($this -> options, id);
			//Удалим те, которые есть, но не нужны
			$toDel = array_diff($has, $inp);
			//Добавим те, которых нет
			$toAdd = array_diff($inp, $has);
			$delCr = new CDbcriteria();
			$delCr -> compare('id_user', $this -> id);
			$delCr -> addInCondition('id_option',$toDel);
			UserOptionAssignment::model() -> deleteAll($delCr);
			foreach ($toAdd as $id) {
				$ass = new UserOptionAssignment();
				$ass -> id_option = $id;
				$ass -> id_user = $this -> id;
				$ass -> save();
			}
		}
		//Добавляем адреса юзеру
		if (($this -> input_type == 'mainForm')) {
			//Удаляем адреса, елси они были записаны.
			if ($this -> address_array) {
				$address_ids = array();
				foreach ($this -> address_array as $addr) {
					$address_ids [] = $addr -> id;
				}
				$criteria = new CDbCriteria();
				$criteria -> compare('id_user', $this -> id);
				$criteria -> addInCondition('id_address', $address_ids);
				UserAddressAssignments::model() -> deleteAll($criteria);
			}
			if (!empty($this -> addresses)) {
				foreach ($this -> addresses as $addr) {
					if (!(int)$addr) {
						$Uaddr = new UserAddress();
						$Uaddr -> address = $addr;
						if ($Uaddr -> save()) {
							$addr = $Uaddr -> id;
						} else {
							continue;
						}
					}
					$ass = new UserAddressAssignments();
					$ass -> id_address = $addr;
					$ass -> id_user = $this -> id;
					$ass -> save();
				}
			}
		}
		//Добавляем юзеру телефоны
		//Изменение производим, если атрибут $phones_input является массивом. Он таковым будет только если сабмитнута форма с полем
		//User[phones_input][], иначе заансетится.
		//if ((is_array($this -> phones_input))) {
		if (($this -> input_type == 'mainForm')) {
			//Удаляем телефоны, елси они были записаны.
			if ($this -> phones) {
				$phone_ids = array();
				foreach ($this -> phones as $phone) {
					$phone_ids [] = $phone -> id;
				}
				$criteria = new CDbCriteria();
				$criteria -> compare('id_user', $this -> id);
				$criteria -> addInCondition('id_phone', $phone_ids);
				//print_r($phone_ids);
				UserPhoneAssignments::model() -> deleteAll($criteria);
			}
			if (!empty($this -> phones_input)) {
				foreach ($this -> phones_input as $phone) {
					$obj = new UserPhoneAssignments();
					$obj -> id_user = $this -> id;
					$obj -> id_phone = $phone;
					$obj -> save();
				}
			}
		}
		//Добавляем юзеру роли.
        $assignments = Yii::app()->authManager->getAuthAssignments($this->id);
        if (!empty($assignments)) {
			//Снимаем с юзера все, что на нем было раньше.
            foreach ($assignments as $key => $assignment) {
                Yii::app()->authManager->revoke($key, $this->id);
            }
        }
		//Добавляем ему роль, соответствующую его типу.
        Yii::app()->authManager->assign(UserType::model() -> getRole($this->id_type), $this->id);
        return parent::afterSave();
    }
	/**
	 * Returns true if the logged in user has rights to create a User Model of specified type
	 * @arg mixed arg - the argument to specify parent
	 */
	public function checkCreateAccess(){
		switch ($this -> id_type) {
			case 1:
				//return Yii::app() -> checkAccess('createAdmin');
				return false;
			break;
			case 2:
				return Yii::app() -> user -> checkAccess('createMainDoc');
			break;
			case 3:
				return Yii::app() -> user -> checkAccess('createDoctor');
			break;
			default:
				return false;
		}
	}
	/**
	 * Returns true if the logged in user has rights to delete a User Model
	 * @return boolean
	 */
	public function checkDeleteAccess(){
		return Yii::app() -> user -> checkAccess('admin');
	}
	/**
	 * Returns true if the logged in user has rights to update the current User Model
	 * @return boolean
	 */
	public function checkUpdateAccess(){
		/*echo $this -> id_type;
		echo "<br/>".Yii::app() -> user -> getId();
		echo "<br/>".$this -> id_parent;*/
		switch ($this -> id_type) {
			case 1:
				return Yii::app() -> user -> checkAccess('updateOwnUser',array('user' => $this));
			break;
			case 2:
				return (Yii::app() -> user -> checkAccess('updateMainDoc') || Yii::app() -> user -> checkAccess('updateOwnUser',array('user' => $this))|| Yii::app() -> user -> checkAccess('updateChildDoctor',array('user' => $this)));
			break;
			case 3:
				return (Yii::app() -> user -> checkAccess('updateDoctor') || Yii::app() -> user -> checkAccess('updateOwnUser',array('user' => $this)) || Yii::app() -> user -> checkAccess('updateChildDoctor',array('user' => $this)));
			break;
			default:
				return false;
		}
	}
	/**
	 * @arg array get - the $_GET variable. 
	 * This function is used to set some initial properties of the model 
	 * that are populated from the url along with modelClass
	 */
	public function readData($get){
		//print_r($get);
		if (isset($get['type'])){
			$this -> id_type = UserType::model() -> getNumber($get['type']);
			//echo $this -> id_type;
		}
		$parent = self::model() -> customFind($get['arg']);
		$this -> id_parent = $parent -> id;
	}
	/**
	 * Gives the redirect url/array
	 * @arg array|string external - the redirect url from the controller
	 * @return array|string - the url to redirect to
	 */
	public function redirectAfterCreate($external){
		$this -> setParent();
		return Yii::app() -> baseUrl. '/cabinet' . $this -> parent -> giveUserNameForPage() ;
	}
	public function redirectAfterUpdate($external){
		if ($this -> id_type != UserType::model() -> getNumber('doctor')) {
			$this -> setParent();
			return Yii::app() -> baseUrl. '/cabinet' . $this -> parent -> giveUserNameForPage() ;
		} else {
			return $external;
		}
	}
	/**
	 * @arg object[CDbCriteria] criteria - the criteria to be applied to users search
	 * @return array - array of User objects with the following structure: main doctor, <his users> , another main doctor, <his users>, ...
	 */
	/*public function findAllByMainDocs($criteria = false) {
		if (!$criteria) {
			$criteria = new CDbCriteria;
		}
		$crit = new CDbCriteria;
		$crit -> compare('id_type', UserType::model() -> getNumber('mainDoc'));
		//Выбираем всех главных докторов
		$MDs = User::model() -> findAll($crit);
		//Получаем для каждого из них юзеров и добавляем в массив.
		$criteria -> compare('id_parent', '1');
		$users = array();
		$params = $criteria -> params;
		foreach($MDs as $MD) {
			$users[] = $MD;
			$params[':ycp2'] = $MD -> id;
			$criteria -> params = $params;
			$users = array_merge($users, User::model() -> findAll($criteria));
		}
		return $users;
	}*/
	/**
	 * @return array - an array of User objects that are MedPred s.
	 */
	public function GiveMedPreds () {
		$crit = new CDbCriteria;
		$crit -> compare('id_type', UserType::model() -> getNumber('mainDoc'));
		return User::model() -> findAll($crit);
	}

	/**
	 * @param integer[] $medPreds - array of main doctor's ids whose users are to be shown
	 * @param string $sortby
	 * @return User[] - array of User with the following structure: main doctor, <his users> , another main doctor, <his users>, ...
	 */
	public function findAllByMainDocs($medPreds = false,$sortby = '') {
		$crit = new CDbCriteria;
		$crit -> compare('id_type', UserType::model() -> getNumber('mainDoc'));
		if (!empty($medPreds)) {
			$crit -> addInCondition('id', $medPreds);
		}
		$MDs = User::model() -> findAll($crit);
		//Получаем для каждого из них юзеров и добавляем в массив.
		$users = array();
		foreach($MDs as $MD) {
			$criteria = new CDbCriteria;
			//$criteria -> order = $sortString;
			$criteria -> compare('id_parent', $MD -> id);
			$users[] = $MD;
			$users = array_merge($users, User::model() -> findAll($criteria));
		}
		return $users;
	}
	/**
	 * @return object[User] - model of the logged in user
	 */
	public function giveLogged() {
		return self::model() -> findByPk(Yii::app() -> user -> getId());
	}
	/**
	 * @return string - information about user's bank accounts and so on.
	 */
	public function givePayString(){
		$rez = '';
		if ($this -> bik) {
			$rez .= 'БИК: '.$this -> bik."<br/>";
		}
		if ($this -> bank_account) {
			$rez .= 'Счет: '.$this -> bank_account."<br/>";
		}
		if ($this -> card_number) {
			$rez .= 'Карта: '.$this -> card_number."<br/>";
		}
		if ($this -> webmoney) {
			$rez .= 'Webmoney: '.$this -> webmoney."<br/>";
		}
		return $rez;
	}
	/**
	 * Sets the User::calls property for Main Doctors. (they do not have own calls, but their children do)
	 */
	public function prepareCalls(){
		if ($this -> id_type == UserType::model() -> getNumber('maindoc')) {
			$calls = array();
			foreach($this -> getChildren() as $child){
				$calls = array_merge($calls, $child -> calls);
			}
			$this -> calls = $calls;
		}
	}
	/**
	 * @return array - an array of UserAddress objects that belong to all his children
	 */
	public function giveChildrenAddresses(){
		$addresses = array();
		foreach ($this -> getChildren() as $child) {
			foreach ($child -> address_array as $address) {
				if (in_array($address, $addresses)) {
					continue;
				}
				$addresses [] = $address;
			} 
		}
		return $addresses;
	}
	/**
	 * @arg array addresses - an array of UserAddress objects that are to be owned by the users looked for
	 * @arg array phones - an array of UserPhone objects that are to be owned by the users looked for
	 * returns array of all users that have the given coordinates
	 */
	public function giveByCoordinatesObjects($addresses, $phones){
		return $this -> giveByCoordinates(CHtml::giveAttributeArray($addresses, 'id'), CHtml::giveAttributeArray($phones, 'id'));
	}
	/**
	 * @arg array addresses - an array of UserAddress ids that are to be owned by the users looked for
	 * @arg array phones - an array of UserPhone ids that are to be owned by the users looked for
	 * returns array of all users that have the given coordinates
	 */
	public function giveByCoordinates($addresses, $phones){
		$users = array();
		if ((empty($addresses))||(empty($phones))) {
			return $users;
		}
		$criteria = new CDbCriteria();
		$criteria -> with = array('address_array','phones');
		//print_r(CHtml::giveAttributeArray($addresses, 'id'));echo "<br/>";
		//print_r(CHtml::giveAttributeArray($phones, 'id'));echo "<br/>";
		
		$criteria -> addInCondition('address_array.id', $addresses);
		$criteria -> addInCondition('phones.id', $phones);
		$users = self::model() -> findAll($criteria);

		return $users;
	}
	/**
	 * Sets CustomFlash with information about errors;
	 */
	public function explainErrors(){
		$errors = $this -> getErrors();
		if ($errors['username']) {
			new CustomFlash('error','User','something','Поле "имя пользователя" заполнено некорректно или не заполнено!', true);
		}
		/*if ($errors['password']) {
			new CustomFlash('error','User','something','Поле "имя пользователя" заполнено некорректно или не заполнено!', true);
		}*/
	}
	/**
	 * @return boolean - whether this user has directions.
	 * Sets CustomFlash with information about errors;
	 */
	public function hasDirections(){
		return ((($this -> jMin)&&($this -> jMax))||(($this -> jMin_add)&&($this -> jMax_add)));
	}
	/**
	 * @retrun array - an array of all patients models corresponding to this user.
	 */
	public function givePatients(){
		if ($this -> id_type == UserType::model() -> getNumber('doctor')) {
			return $this -> patients;
		} elseif ($this -> id_type == UserType::model() -> getNumber('maindoc')) {
			$criteria = new CDbCriteria;
			$criteria -> compare('id_parent', $this -> id);
			$rez = array();
			foreach(User::model() -> findAll($criteria) as $doc) {
				$rez = array_merge($rez, $doc -> patients);
			}
			return $rez;
		} elseif ($this -> id_type == UserType::model() -> getNumber('admin')) {
			$criteria = new CDbCriteria;
			$criteria -> order = 'create_time DESC';
			return Patient::model() -> findAll($criteria);
		}
	}
	/**
	 * @arg mixed action - what to do with the collection
	 * @arg object[CList] list - list to be used
	 * @return mixed - modified list or true\false;
	 */
	public function collectionAction($action, $list){
		if ($action == self::SMS_SEND) {
			
		}
	}
	/**
	 * @arg string text - the text of an sms to be sent
	 * @return array('error' => '<errorText>','response' => SmsResponse, 'delayed' => '<when to send>', 'text' => '<text>', 'number' => '<number>')
	 */
	public function sendSms($text){
		//Если номер нормальный, отправляем. Регулярка с http://habrahabr.ru/post/110731/
		if (Sms::validateNumber($this -> tel)) {
			//echo "TryToSend";
			
			$sms = new Sms('create',$this, '' , $text);
			$date = getdate();
			//Получаем настоящее время в часах.
			//Отсрачиваем отправку смс, если время не рабочее.
			$hours = $date['hours'];
			if ($hours < 9) {
				$min = (9 - $hours) * 3600;
				$max = $min + 5 * 3600;
			}
			if ($hours > 21) {
				$min = (24 - $hours + 9) * 3600;
				$max = $min + 5 * 3600;
			}
			if (($min)&&($max)) {
				$add = rand($min, $max);
				$time = time() + $add;
				$sendStr = date("Y-m-d H:i",$time);
				$delayed = "Отправка в {$sendStr}";
			} else {
				$delayed = 'Отправка без задержки.';
			}
			
			//echo "sms to number: {$sms -> number} contains:<br/>{$sms -> text}<br/>{$delayed}<br/>";
			$resp = $sms -> send($time);
			return array(
				'response' => $resp,
				'delayed' => $delayed,
				'text' => $sms -> text,
				'number' => $sms -> number
			);
			//Yii::app() -> sms -> sendSms($this -> tel, $text);
		} else {
			//echo "Неверный номер!<br/>";
			return array('error' => 'У выбранного пользователя указан некорректный номер!\nНомер доожен начинаться с 7. Напимер,79113332211.');
			new CustomFlash('error','User','sendSmsInvalidNumber'.$this -> id,'Собщение пользователю '.$this -> fio.' не отправлено: неверный номер. Проверьте его правильность:'.$this -> tel,true);
		}
	}
	/**
	 * Adds the UserOption::$addProperty property to this user
	 * if it is not already added
	 */
	public function addProperty($unused, $option){


		if (is_a($option, 'UserOption')) {
			$id = $option->id;
		} else {
			return;
		}
		//Ищем дубли
		$dups = UserOptionAssignment::model() -> findByAttributes(array('id_user' => $this -> id, 'id_option' => $id));
		if (!$dups) {
			$opt = new UserOptionAssignment();
			$opt -> id_user = $this -> id;
			$opt -> id_option = $id;
			$opt -> save();
		}
	}
	/**
	 * Generates a link to this user's cabinet.
	 */
	public function showOneself(){
		//Если залогинен это пользователь, то не нужно в ссылку включать его логин.
		if ($this == $this -> findByPk(Yii::app() -> user -> getId())) {
			$login = '';
		} else {
			$login = '/'.$this -> username;
		}
		$link = Yii::app() -> baseUrl.'/cabinet'.$login;
		return CHtml::link($this -> fio, $link);
	}
	/**
	 * Gives statistics of the calls for the period
	 * @param integer from
	 * @param integer to
	 * @return array - array of pairs <callTypeId> => <numberOfSuchCalls>
	 */
	public function giveStatistics($from = 0, $to = 0){
		if (!$to){
			$to = time();
		}
		if (!$from) {
			$from = $to - 3600 * 24;
		}
		$types = CallType::model() -> findAll();
		$rez = array();
		foreach ($types as $type) {
			$command = Yii::app() -> db -> createCommand("SELECT COUNT(*) FROM `tbl_call` WHERE ".BaseCall::model() -> giveSqlForTimePeriod($from, $to).' AND `id_call_type`=\''.$type -> id.'\' AND `id_user`=\''.$this -> id.'\'');
			$rez [$type -> string] = ($command -> queryScalar());
		}
		return $rez;
		//return Data::model() -> giveCallsInRange($from,$to, $this);
	}

	/**
	 * Generates an sms to the user.
	 * @param integer from
	 * @param integer to
	 * @param bool send - whether to send the sms immediately
	 * @return string text of the sms.
	 */
	public function smsReportOnPeriod($from, $to, $send = false){
		$stat = $this -> giveStatistics($from, $to);
		$fromText = date('j.n',$from);
		$toText = date('j.n',$to);
		$ver = $stat['verifyed'];
		$money = $ver * $this -> conditions;
		$text = "Уважаемый(ая) <ФИО>!\nВ период с {$fromText} по {$toText} было потверждено {$ver} Ваших пациентов, и Вы заработали {$money}р.";
		if ($send) {
			$this -> sendSms($text);
		}
		$text = SmsPattern::prepareText($this, $text);
		return $text;
	}
	public function getChildrenIdString() {
		if (!$this -> _childrenIdString) {
			$this -> _childrenIdString = $this -> giveStringFromArray($this -> getChildren(),',','id');
		}
		return $this -> _childrenIdString;
	}

	/**
	 * Выводит json_encode массива идентификаторов пользователей
	 */
	public function giveChildrenIds(){
		$arr = array();
		$arr [] = $this -> id;
		if ($this -> id_type == UserType::model() -> getNumber('mainDoc')) {
			$arr = CHtml::giveAttributeArray($this -> getChildren(),'id');
		}
		echo json_encode(array('users' => $arr, 'fio' => $this -> fio));
	}

	/**
	 * Отвечает на запросы, когда в $_POST пришел айдишник пользователя из JS
	 * Отдает обратно json со свойствами юзера. Без какой-либо статистики.
	 */
	public function basicDataDumpForJs(){
		$user = User::model() -> findByPk($_POST['id']);
		$user -> setScenario('basicDumpForJs');
		echo json_encode(array(
			$user -> attributes
		));
	}

	/**
	 * Отвечает на запросы, когда в $_POST пришел айдишник пользователя из JS
	 * В массиве $_POST помимо айдишника пользователя должен быть
	 * задан интервал ячеек.
	 * Отдает обратно json со свойствами юзера. Без какой-либо статистики.
	 */
	public function statDumpJS(){
		//Для удобной смены источника данных, если вдруг понадобится.
		$data = $_GET;
		//$data = $_POST;
		$fromCell = $data['fromOffset'];
		$toCell = $data['toOffset'];
		$cellType = $data['cellType'];
		if ($data['reper'] > 100000) {
			$reper = new CDateTime();
			$time = $reper -> getTimestamp();
			$dif = $time - $data['reper'];
			$reper -> setTimestamp($data['reper']);
		} else {
			$reper = null;
		}
		$rez = array();
		//Ищем юзера, статистику которого хотим вывести.
		$user = User::model() -> findByPk($data['id']);
		if ((strlen($cellType))&&(strlen($fromCell))&&(strlen($toCell))&&($user)&&($fromCell <= $toCell)) {
			$dataObj = new Data();
			$timePeriod = TimePeriod::fromCell($fromCell, $cellType, $reper);
			for ($i = $fromCell; $i <= $toCell; $i++) {
				//Важно, что делаем просто push! Инчае json_encode сделает
				// объект, а нужен массив
				$rez[] = $user -> cellInfoByTimePeriod($timePeriod, $dataObj);
				//В конце переводим интервал чуть дальше
				$timePeriod -> nextCell($cellType);
			}
		} else {
			$rez['success'] = false;
		}
		echo json_encode(array(
			'response' => $rez,
			'dataId' => $data['dataId']
		), JSON_NUMERIC_CHECK);
	}
	public function cellHeaders(){
		//$data = $_POST;
		$data = $_GET;
		$fromCell = $data['fromOffset'];
		$toCell = $data['toOffset'];
		$cellType = $data['cellType'];
		if ($data['reper'] > 100000) {
			$reper = new CDateTime();
			$reper -> setTimestamp($data['reper']);
		} else {
			$reper = null;
		}
		$timePeriod = TimePeriod::fromCell($fromCell, $cellType, $reper);
		for ($i = $fromCell; $i <= $toCell; $i++) {
			$rez[] = $timePeriod -> giveHeader($cellType);
			$timePeriod -> nextCell($cellType);
		}
		echo json_encode(array(
			'response' => $rez,
			'dataId' => $data['dataId']
		),JSON_PRETTY_PRINT);
	}
	/**
	 *
	 */
	public function preciseList(){
		$data = $_GET;
		$rez = array();

		$smaller = array(
				'month' => 'week',
				'week' => 1,
				7 => 1,
				1 => 1
		);

		$cell = $smaller[$data["oldCell"]];
		if (!$cell) {
			//По умолчанию в качестве ячейки будем брать день.
			$cell = 1;
		}
		$user = $this -> findByPk($data['id']);
		if (!$user) { return; }
		$dataObj = new Data();
		$time = new CDateTime();
		$time -> setTimestamp($data["from"]);
		$timePeriod = TimePeriod::fromCell(0,$cell,$time);
		$length = 1;
		while (($timePeriod -> to -> getTimestamp() <= $data["to"]) && ($length < 100)) {
			$temp = $user -> cellInfoByTimePeriod($timePeriod, $dataObj);
			$temp['headerInfo'] = $timePeriod -> giveHeader($cell);
			$rez [] = $temp;
			$timePeriod -> nextCell();
			$length ++;
		}
		echo json_encode(array(
				'response' => $rez,
				'dataId' => $data['dataId']
		),JSON_PRETTY_PRINT);
	}

	/**
	 * @param $source[] - an array of user's ids
	 */
	public function showUserList($source) {
		$users = User::model() -> giveCollection($source);
		echo "Выбрано ".count($users)." пользователей:";
		foreach ($users as $ind => $user){
			echo "<div>";
			echo "<input type='hidden' name='userGroup[]' value='".$user -> id."'/>";
			echo ($ind+1).") ".$user -> fio;
			echo "</div>";
		}
	}
	/**
	 * @param $timePeriod
	 * @param Data $dataObj
	 * @return array
	 */
	public function cellInfoByTimePeriod(TimePeriod $timePeriod, Data $dataObj = null){
		if (!is_a($dataObj, 'Data')) {
			$dataObj = new Data();
		}
		//$timePeriod -> show();
		$cellInfo = array();
		//Выдаем информацию по статистике.
		$cellInfo['count'] = $dataObj -> countCallsInRange(
				$timePeriod -> from -> getTimestamp(),
				$timePeriod -> to -> getTimestamp(),
				$this
		);
		$cellInfo['events'] = array();

		// = $user -> count
		$cellInfo['from'] = $timePeriod -> from -> getTimestamp();
		$cellInfo['to'] = $timePeriod -> to -> getTimestamp();
		return $cellInfo;
	}
}
