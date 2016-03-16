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
 * @property integer $price
 * @property string $report
 * @property string $mangoTalker
 * @property string $comment
 * @property integer $id_call_type
 * @property integer $id_user
 * @property string $date
 *
 * The followings are the available model relations:
 * @property User $idUser
 * @property CallType $idCallType
 */

class BaseCall extends UModel
{
	public $phone;
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
	 * Sets propper values for date
	 */
	public function beforeSave(){
		if (is_int($this -> date)) {
			$this -> date = new CDbExpression('FROM_UNIXTIME('.$this -> date.')');
		}
		return parent::beforeSave();
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
		return strtotime($this -> date);
	}
	/**
	 * @arg integer from - the time to search from
	 * @arg integer to - the time to search to
	 * @return object[CDbCRiteria]
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
			$this -> i = $CPhone -> phone -> i;
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
}
