<?php

/**
 * This is the model class for table "{{phone}}".
 *
 * The followings are the available columns in table '{{phone}}':
 * @property integer $id
 * @property integer $ivr
 * @property string $number
 *
 * The followings are the available model relations:
 * @property PhoneAssignments[] $phoneAssignments
 */
class UserPhone extends UModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{phone}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('number', 'required'),
			array('ivr', 'numerical', 'integerOnly'=>true),
			array('number', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ivr, number', 'safe'),
		);
	}
	
	/**
	 * @return array - an array of UserAddress objects that can be set to this phone number to point some user
	 */
	public function giveAddresses(){
		$addr = array();
		if ($this -> regular_users) {
			foreach($this -> regular_users as $user){
				//echo count($user -> address_array).'<br/>';
				$addr = array_merge($addr, $user -> address_array);
			}
		}
		return $addr;
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'phoneAssignments' => array(self::HAS_MANY, 'PhoneAssignments', 'id_phone'),
			'regular_users' => array(self::MANY_MANY, 'User', '{{phone_assignments}}(id_phone, id_user)','condition' => 'regular_users.id_type = '.UserType::model() -> getNumber('doctor')),
			'main_users' => array(self::MANY_MANY, 'User', '{{phone_assignments}}(id_phone, id_user)','condition' => 'main_users.id_type = '.UserType::model() -> getNumber('mainDoc')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ivr' => 'IVR',
			'number' => 'Number',
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
		$criteria->compare('ivr',$this->ivr);
		$criteria->compare('number',$this->number,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function checkDeleteAccess(){
		return Yii::app() -> user -> checkAccess('admin');
	}
	public function beforeSave(){
		$arr ['number'] = $this -> number;
		if ($this -> ivr) {
			$arr ['ivr'] = $this -> ivr;
		}
		$dups = $this -> findAllByAttributes($arr);
		//vardump($dups);
		//echo $this -> id.' - '.$dups -> id.'<br/>';
		//Yii::app() -> end();
		$show = (($dups)&&($dups -> id != $this -> id)&&(!$this -> isNewRecord))||(($dups)&&($this -> isNewRecord));
		if ($show) {
			new CustomFlash('error','UserPhone','DuplicatePhoneNumber','Заданный телефонный номер с таким IVR уже существует.',true);
			return false;
		}
		return parent::beforeSave();
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserPhone the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * @arg integer from - unix time of the lower boundary of the period
	 * @arg integer to - upper boundary
	 * @return arry - array('<number>' => array('<call_type>'=>'<number_of_calls_of_that_type>'))
	 */
	public function stat($from, $to) {
		$phones = UserPhone::model() -> findAll();
		$types = CallType::model() -> findAll();
		foreach ($phones as $phone) {
			echo "<br/>".$phone -> number.":<br/>";
			foreach ($types as $type){
				$command = Yii::app()->db->createCommand("SELECT COUNT(`tbl_call`.`id`) FROM `tbl_call`, `tbl_phone`,`tbl_client_phone` WHERE `tbl_client_phone`.`mangoTalker`=`tbl_call`.`mangoTalker` AND `tbl_phone`.`id`=`tbl_client_phone`.`id_phone` AND `tbl_phone`.`number`='".$phone -> number."' AND `tbl_call`.`date` > '2015-10-01 00:00:00' AND `tbl_call`.`date`<'2015-10-31 23:59:59' AND `tbl_call`.`id_call_type`='".$type -> id."'");
				echo $type -> name." - ".$command -> queryScalar()."<br/>";
			}
		}
	}
	/**
	 * @return string <number>[:<ivr>]
	 */
	public function showMe(){
		$ivr = $this -> ivr ? ':'.$this -> ivr: '';
		return $this -> number.$ivr;
	}
}
