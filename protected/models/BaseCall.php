<?php

/**
 * This is the model class for table "{{call}}".
 *
 * The followings are the available columns in table '{{call}}':
 * @property integer $id
 * @property string $research_type
 * @property integer $i
 * @property integer $j
 * @property string $H
 * @property string $wishes
 * @property string $fio
 * @property string $birth
 * @property string $number
 * @property string $clinic
 * @property string $price
 * @property string $report
 * @property string $mangoTalker
 * @property string $comment
 * @property integer $id_call_type
 * @property integer $id_user
 * @property integer $id_error
 * @property string $date
 * @property string $dateString
 * @property integer $prev_month
 *
 * The followings are the available model relations:
 * @property CallType $idCallType
 * @property User $idUser
 * @property CallError $idError
 * @property Review[] $reviews
 */

class BaseCall extends UModel
{
	/**
	 * @var boolean inited - whether the init method was successful. If not, the object will remain balnk.
	 */
	private $inited = false;
	/**
	 * @var boolean inited - whether to send sms to the user or not.
	 */
	public $sendSms = false;
	/**
	 * @var TelfinCall tCall - the corresponding TelfinCall object.
	 * It need to be saved with the id of this call.
	 */
	public $tCall = NULL;
	public $phone;
	
	/**
	 * Sets the attributes to the record from an iCall object.
	 * @arg iCall call - object that imlements the iCall interface.
	 */
	public function storeData(iCall $call){
		$this -> research_type = $call -> research_type;
		$this -> i = $call -> i ? $call -> i : NULL;
		$this -> j = $call -> j ? $call -> j : NULL;
		$this -> H = $call -> H ? $call -> H : NULL;
		
		$this -> mangoTalker = $call -> mangoTalker;
		$this -> wishes = $call -> wishes;
		$this -> fio = $call -> fio;
		$this -> birth = $call -> birth;
		$this -> number = $call -> number;
		$this -> clinic = $call -> clinic;
		$this -> price = $call -> price;
		$this -> report = $call -> report;
		//$this -> mangoTalker = $call -> mangoTalker;
		$this -> comment = $call -> comment;
		$this -> date = $call -> giveUnixTime();
		$this -> dateString = $call -> dateString;
		
		$owner = $call -> giveOwner();
		
		$this -> id_error = $call -> id_error;
		/*Это актуально для iCall, получаемых из TelfinApi. Нужно для дальнейшего обновления
		информации о привязке Telfin звонков к звонкам в базе*/
		if (is_a($call -> tCall,'TelfinCall')) {
			$this -> tCall = $call -> tCall;
		}
		
		//var_dump($owner);
		//Если не смогли найти владельца, то не заполняем это поле.
		$this -> id_user = $owner ? $owner -> id : NULL;
		$this -> id_call_type = $call -> ClassifyId();
		
		$this -> inited = true;
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{call}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date', 'required'),
			array('i, j, id_call_type, id_user', 'numerical', 'integerOnly'=>true),
			array('H, wishes, report, comment', 'length', 'max'=>1024),
			array('fio, birth', 'length', 'max'=>256),
			array('number', 'length', 'max'=>100),
			array('clinic', 'length', 'max'=>512),
			array('mangoTalker', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, i, j, H, wishes, fio, birth, number, clinic, price, report, mangoTalker, comment, id_call_type, id_user, date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'id_user'),
			'callType' => array(self::BELONGS_TO, 'CallType', 'id_call_type'),
			'error' => array(self::BELONGS_TO, 'CallError', 'id_error'),
			'tCalls' => array(self::HAS_MANY, 'TelfinCall','id_call')
			//'phone' => array(self::BELONGS_TO, 'UserPhone', array('mangoTalker' => 'number'))
		);
	}
	/**
	 * Find the corresponding UserPhone and set it.
	 */
	public function setPhone(){
		$CPhone = ClientPhone::model() -> findByAttributes(array('mangoTalker' => $this -> mangoTalker), array('with' => 'phone'));
		if ($CPhone) {
			$this -> phone = $CPhone -> phone;
			$this -> i = $this -> phone -> i;
		}
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'i' => 'I',
			'j' => 'J',
			'H' => 'H',
			'wishes' => 'Wishes',
			'fio' => 'Fio',
			'birth' => 'Birth',
			'number' => 'Number',
			'clinic' => 'Clinic',
			'price' => 'Price',
			'report' => 'Report',
			'mangoTalker' => 'Mango Talker',
			'comment' => 'Comment',
			'id_call_type' => 'Id Call Type',
			'id_user' => 'Id User',
			'date' => 'Date',
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
		$criteria->compare('i',$this->i);
		$criteria->compare('j',$this->j);
		$criteria->compare('H',$this->H,true);
		$criteria->compare('wishes',$this->wishes,true);
		$criteria->compare('fio',$this->fio,true);
		$criteria->compare('birth',$this->birth,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('clinic',$this->clinic,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('report',$this->report,true);
		$criteria->compare('mangoTalker',$this->mangoTalker,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('id_call_type',$this->id_call_type);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaseCall the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * @return integer - unix time of the moment which the patient is assigned to.
	 */
	/*public function giveAssignDate(){
		//18,40 2/11 - формат даты
		$arr = array_values(array_filter(array_map('trim',explode(' ', $this -> report))));
		//print_r($arr);
		$date = $arr[1];
		//echo $date.' - date';
		$date_arr = array_map('trim',explode('/', $date));
		if (count($date_arr) < 2) {
			$date_arr = array_map('trim',explode('.', $date));
		}
		$day = $date_arr[0];
		$month = $date_arr[1];
		//свойство date уж должно быть задано!
		$call_date = $this -> giveDate();
		//print_r( $this -> giveDate());
		//echo "123";
		//Сначала считаем, что запись была сделана на тот же год.
		$year = $call_date['year'];
		//Но если номер месяца записи меньше номера месяца звонка, то считаем, что запись произошла на следующий год.
		if ($call_date['mon'] > $month) {
			$year ++;
		}
		//echo $month.' - '.$day.' - '.$year.'<br/>';
		//echo $this -> report;
		return mktime(12,0,0,$month,$day,$year);
	}*/
	/**
	 * Sets propper values for date
	 */
	public function beforeSave(){
		//Сбрасывает все атрибуты, которые имеют нулевую длину.
		$attrs = array('price', 'dateString', 'research_type', 'j','wishes','birth');
		foreach ($attrs as $attr) {
			//if (property_exists($this, $attr)) {
				if (strlen($this -> $attr) == 0) {
					$this -> $attr = new CDbExpression('NULL');
				}
			//}
		}
		if ($this -> isNewRecord) {
			//Со старой модели добавления звонков. Скоро можно будет убрать.
			/*if (($this -> id_call_type == CallType::model() -> getNumber('verifyed'))||($this -> id_call_type == CallType::model() -> getNumber('assigned'))) {
				//echo "123";
				$assign_time = $this -> giveAssignDate();
				$assign = getdate($assign_time);
				$date = getdate($this -> date);
				if ($assign ["mon"] != $date ["mon"]) {
					$this -> date = $assign_time;
					$this -> prev_month = 1;
					//echo "next!";
				}
			}*/
			//echo "beforesave";
			if (is_int($this -> date)) {
				$this -> date = new CDbExpression('FROM_UNIXTIME('.$this -> date.')');
			}//*/
		}
		if ($this -> sendSms) {
			$this -> sendSms = false;
			$owner = User::model() -> findByPk($this -> id_user);
			if ($owner) {
				$cont = true;
				
				switch($this -> id_call_type) {
					//case CallType::model() -> getNumber('cancelled'):
					case '3':
						$smsBody = "К сожалению, пациент ".$this -> fio." отменил(а) запись.";
					break;
					//case CallType::model() -> getNumber('assigned'):
					case '6':
						$smsBody = "Пациент ".$this -> fio." записался(ась) на исследование ".$this -> research_type." на ".$this -> report.".";
					break;
					default:
					$cont = false;
					break;
				}
				if ($cont) {
					$owner -> sendSms("Здравсвуйте, <ФИО>! ".$smsBody);
					echo "Sms was added to a sendlist!";
				}
				
				/*echo "Trying to send an sms<br/>";
				require_once(Yii::getPathOfAlias('webroot.vendor'). DIRECTORY_SEPARATOR .'autoload.php');
				$client = Yii::app() -> sms -> giveClient();
				$bal = $client -> myBalance();
				echo "SmsBalance: ".$bal -> balance."<br/>";
				
				$owner -> 
				$sms = new \Zelenin\SmsRu\Entity\Sms('89516727222', 'Смс с сайта через класс Api. Дошла ли?');
				$smsResp = $client->smsSend($sms);*/
			}
		}
		
		/**
		 * Если запись новая, то нужно правильным образом задать ей поле dateString_timestamp - в этом поле 
		 * хранится timestamp дня, когда была сделана запись гугл дока.
		 */
		if ($this -> isNewRecord) {
			$ds = $this -> dateString;
			$dsArr = array_map('trim',explode('.',$ds));
			if (!$dsArr[1]) {
				$dsArr = array_map('trim',explode('/',$ds));
			}
			if ($dsArr[1]) {
				$month = $dsArr[1];
				$day = $dsArr[0];
				$dsTime = new DateTime();
				$curDateArr = getdate();
				$dsTime -> setDate($curDateArr['year'],$month, $day);
				$diff = $dsTime -> getTimestamp() - time();
				if ($diff > 0) {
					$dsTime -> setDate($curDateArr['year'] - 1,$month, $day);
				}
				//echo $dsTime -> format(DateTime::W3C);
				$this -> dateString_timestamp = new CDbExpression("FROM_UNIXTIME({$dsTime -> getTimestamp()})");
			}
		}
		return parent::beforeSave();
	}
	public function beforeDelete() {
		if (parent::beforeDelete()) {
			if ($this -> id_user != 1) {
				$this -> id_user = 1;
				$this -> save();
				return false;
			}
			return true;
		}
	}
	/**
	 * Executed after successful save() method.
	 * 
	 */
	public function afterSave() {
		//Не забываем отметить, что звонок на АТС не ушел в трубу, а привязался к этой записи.
		if (is_a($this -> tCall, 'TelfinCall')) {
			$this -> tCall -> id_call = $this -> id;
			$this -> tCall -> save();
		}
	}
	/**
	 * @return array - the array returned by getdate function from create_time attr
	 */
	public function giveDate() {
		/*$dateArr = explode('.', $this -> dateString);
		$rez['day'] = (int)$dateArr[0];
		$rez['mon'] = (int)$dateArr[1];*/
		//$rez['year'] = $dateArr[2];
		return getdate($this -> giveTime());
	}
	/**
	 * @return integer - the unix time of the call (it is valid only up to days)
	 */
	public function giveTime(){
		if (!($this -> date * 1)) {
			return strtotime($this -> date);
		} else {
			return $this -> date;
		}
	}
	/**
	 * @arg integer from - the time to search from
	 * @arg integer to - the time to search to
	 * @return CDbCRiteria
	 */
	public function giveCriteriaForTimePeriod($from = NULL, $to = NULL){
		$criteria = new CDbCriteria;
		if ((int)($from)) {
			$criteria -> addCondition('date >= FROM_UNIXTIME('.$from.')');
		}
		if ((int)($to)) {
			$criteria -> addCondition('date < FROM_UNIXTIME('.$to.')');
		}
		return $criteria;
	}
	/**
	 * @arg integer from - the time to search from
	 * @arg integer to - the time to search to
	 * @arg string oper - operator to be used
	 * @return string - the sql where string containing time condition
	 */
	public function giveSqlForTimePeriod($from = NULL, $to = NULL, $oper = ''){
		$rez = '';
		if ((int)($from)) {
			$rez .= ' '.$oper.' `date` >= FROM_UNIXTIME(\''.$from.'\')';
			$oper = 'AND';
		}
		if ((int)($to)) {
			$rez .= ' '.$oper.' `date` < FROM_UNIXTIME(\''.$to.'\')';
		}
		return $rez;
	}
	/**
	 * @return string - the type of call. They are stored in the database table {{call_type}}
	 */
	public function Classify(){
		//echo $this -> callType -> string.' - ';
		return $this -> callType -> string;
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
	 * Checks the ClientPhone table and sets $this -> i if there is a record corresponding to user's mangoTalker.
	 */
	public function lookForIAttribute(){
		$CPhone = ClientPhone::model() -> findByAttributes(array('mangoTalker' => $this -> mangoTalker), array('with' => 'phone'));
		if ((!$CPhone)&&(preg_match('/^7812\d+/',$this -> mangoTalker))) {
			$CPhone = ClientPhone::model() -> findByAttributes(array('mangoTalker' => str_replace('7812','',$this -> mangoTalker)), array('with' => 'phone'));
		}
		if ($CPhone) {
			$this -> i = $CPhone -> phone -> id;
		}
		return $this -> i;
	}
	/**
	 * Checks the ClientPhone table and returns the corresponding Phone Object
	 */
	public function givePhone(){
		$CPhone = ClientPhone::model() -> findByAttributes(array('mangoTalker' => $this -> mangoTalker), array('with' => 'phone'));
		if ((!$CPhone)&&(preg_match('/^7812\d+/',$this -> mangoTalker))) {
			$CPhone = ClientPhone::model() -> findByAttributes(array('mangoTalker' => str_replace('7812','',$this -> mangoTalker)), array('with' => 'phone'));
		}
		return UserPhone::model() -> findByPK($CPhone -> id_phone);
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
	/**
	 * Searches GoogleDoc for the renewed status (SA field)
	 * @var GoogleDocApiHelper api - api object to find information in.
	 */
	public function refreshStatus($api) {
		if (!$this -> isNewRecord) {
			$str = $this -> dateString;
			$date = explode('.',$str);
			if (!$date[1]) {
				$date = explode(',', $str);
			}
			if (!$date[1]) {
				$date = explode(',', $str);
			}
			$time = new DateTime($this -> date);
			var_dump($time);
			$workSheetLabel = '';
			$entry = $this -> lookForGoogleDocRec($api, false);
		}
	}
	/**
	 * Searches GoogleDoc for the renewed status (SA field)
	 * @var bool real - whether to search in real google doc or not.
	 * @var string wLabel - label of the worksheet
	 * @var GoogleDocApiHelper api - api object to find information in.
	 */
	private function lookForGoogleDocRec($api, $wLabel, $real = false){
		if ($real) {
			$api -> setWorkArea('СТАТИСТИКА СПб', $wLabel);
		} else {
			$api -> setWorkArea('Copy of СТАТИСТИКА СПб', $wLabel);
		}
	}
	/**
	 * @arg string operator - the sql operator. Defaults to AND. Give false if you don't need one
	 * @return string - the sql not to choose the deleted Calls.
	 */
	public static function notDeletedSql ($operator = "AND"){
		return " {$operator} id_user <> 1";
	}
	/**
	 * @return string - url to unlink this call from the user.
	 */
	public function unlinkUrl() {
		if (Yii::app() -> user -> checkAccess('admin')) {
			return Yii::app() -> baseUrl.'/unlinkCall/'.$this -> id;
		} else {
			return '';
		}
	}
	/**
	 * @arg string text - text of the link.
	 * @arg array htmlOptions - standard Yii html options array for the link tag to have
	 * return string - the link tag that unlinks the call
	 */
	public function unlinkTag($text = 'Отвязать', $htmlOptions = array()){
		if ((Yii::app() -> user -> checkAccess('admin'))&&($this -> id_user)) {
			return CHtml::link($text, $this -> unlinkUrl(), $htmlOptions);
		} else {
			return '';
		}
	}
	/**
	 * unlinks the call from the user and set a corresponding error status.
	 */
	public function unlink(){
		if (Yii::app() -> user -> checkAccess('admin')) {
			if ($this -> id_user) {
				$this -> id_error = CallError::model() -> giveText('unlinked');
				$this -> id_user = NULL;
				$this -> save();
			}
		}
	}
}
