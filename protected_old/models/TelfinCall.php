<?php

/**
 * This is the model class for table "{{telfin_call}}".
 *
 * The followings are the available columns in table '{{telfin_call}}':
 * @property string $ApiId
 * @property integer $id_phone
 * @property integer $id_call
 */
class TelfinCall extends UModel
{
	
	/**
	 * @const IVR_EXTENSION_NUM - number of the ivr extension number in telfin ATS
	 */
	const IVR_EXTENSION_NUM = '001';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{telfin_call}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ApiId', 'required'),
			array('id_phone, id_call', 'numerical', 'integerOnly'=>true),
			array('ApiId', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ApiId, id_phone, id_call', 'safe', 'on'=>'search'),
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
			'error' => array(self::BELONGS_TO, 'CallError', 'id_error'),
			'phone' => array(self::BELONGS_TO, 'UserPhone', 'id_phone'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ApiId' => 'Api',
			'id_phone' => 'Id Phone',
			'id_call' => 'Id Call',
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

		$criteria->compare('ApiId',$this->ApiId,true);
		$criteria->compare('id_phone',$this->id_phone);
		$criteria->compare('id_call',$this->id_call);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * Sets the time parameter correct
	 */
	public function beforeSave(){
		if ((is_int($this -> time))&&($this -> time)) {
			$this -> time = new CDbExpression('FROM_UNIXTIME('.$this -> time.')');
		}
		return parent::beforeSave();
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TelfinCall the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * @arg stdClass btCall - brefore telfin call object. It is taken from the telfin api response.
	 * @return TelfinCall - the created TelfinCall object with data taken from the btCall
	 */
	public static function createFromTelfinStat($btCall){
		//The time of the call.
		$time = new DateTime($btCall -> published);
		$tCall = new TelfinCall();
		$tCall -> ApiId = $btCall -> id;
		$tCall -> time = $time -> getTimeStamp();
		//Now we try to get the phone object.
		$callPath = $btCall -> phoneCallView;
		reset($callPath);
		//Получили первый отрезок пути звонка - куда попал пользователь сначала.
		$first = current($callPath);
		
		//Служба CDR не возвращает номер, который был набран пользователем, только лишь добавочный, на который он попал.
		//Поэтому в случае отсутствия записи о звонке в базе данных, уже ничего не поделаешь - phone object не найти.
		//
		if ($first -> destination == self::IVR_EXTENSION_NUM) {
			$tCall -> id_error = CallError::model() -> giveText('no_ivr');
		} else {
			$tCall -> id_error = CallError::model() -> giveText('lost_call');
		}
		//Сохраняем номер звонившего.
		$tCall -> caller = TelfinApiHelper::giveNumberFromNumString($first -> source);
		//Пытаемся найти гугл док запись.
		return $tCall;
	}
	/**
	 * Generates information Html.
	 * @return string - the html of the TelfinCall
	 */
	public function shortcut(){
		$tCall = $this;
		$errorText = '';
		if ($tCall -> id_error) {
			$errorText = "<span title='{$tCall -> error -> text}'>{$tCall -> id_error}</span>";
		}
		echo "<div style='border:1px solid black;'>{$tCall -> time}, caller: {$tCall -> caller}, called: {$tCall -> giveCalled()}, ошибка: {$errorText}</div>";
	}
	/**
	 * @return string - dialed number with IVR if there is one.
	 */
	public function giveCalled() {
		//Если телефон определился, возвращаем его данные.
		if ($this -> id_phone) {
			$phone = UserPhone::model() -> findByPk($this -> id_phone);
			if (is_a($phone, 'UserPhone')) {
				$owners = $this -> owners();
				if (!empty($owners)) {
					$belongsTo = 'title = "'.$this -> giveStringFromArray($ownres, ',','fio').'"';
				}
				return '<span '.$belongsTo.' style="color:#20b2aa;text-decoration:underlined;">'.$phone -> showMe().'</span>';
			}
		}
		//Если же не определился, то склеиваем набранный номер.
		if ($this -> ivr) {
			$ivr = ":".$this -> ivr;
		}
		$number = $this -> called.$ivr;
		if (!$number) {
			return 'Не определен';
		}
		return $number;
	}
	/**
	 * @return User[] - users who own this TelfinCall. (whose number was dialed). Usually only one user
	 */
	public function owners() {
		if ($this -> phone) {
			return $this -> phone -> regular_users;
		} else {
			return array();
		}
	}
	/**
	 * Searches google document again.
	 * @var bool showMess - whether to show log information or not.
	 */
	public function SearchAgain($showMess = true){
		if (!$showMess) {
			ob_start();
		}
		$phone = false;
		if ($this -> id_phone) {
			$phone = UserPhone::model() -> findByPk($this -> id_phone);
		}
		if (!$phone) {
			$phone = new UserPhone;
		}
		$fCall = new FastCall($this -> caller, $phone, $this);
		vardump($fCall);
		$fCall -> MakeDatabaseChanges();
		if (!$showMess) {
			ob_end_clean();
		}
	}
}
