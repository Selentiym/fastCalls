<?php

/**
 * This is the model class for table "{{sms}}".
 *
 * The followings are the available columns in table '{{sms}}':
 * @property string $id
 * @property integer $status
 * @property string $number
 * @property integer $id_user
 * @property string $text
 */
class Sms extends UModel
{
	public static $descriptions = [
			'-1' => 'Сообщение не найдено.',
			'99' => 'Сообщение ожидает отправки.',
			'100' => 'Сообщение находится в нашей очереди.',
			'101' => 'Сообщение передается оператору.',
			'102' => 'Сообщение отправлено (в пути).',
			'103' => 'Сообщение доставлено.',
			'104' => 'Не может быть доставлено: время жизни истекло.',
			'105' => 'Не может быть доставлено: удалено оператором.',
			'106' => 'Не может быть доставлено: сбой в телефоне.',
			'107' => 'Не может быть доставлено: неизвестная причина.',
			'108' => 'Не может быть доставлено: отклонено.',
			'200' => 'Неправильный api_id.',
			'210' => 'Используется GET, где необходимо использовать POST.',
			'211' => 'Метод не найден.',
			'220' => 'Сервис временно недоступен, попробуйте чуть позже.',
			'300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
			'301' => 'Неправильный пароль, либо пользователь не найден.',
			'302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
	];
	/**
	 * @property integer time - moment the sms has to be sent.
	 */
	//public $time;//not used!
	/**
	 * @property User user - the sender
	 */
	public $user = false;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sms}}';
	}
	/**
	 * @arg User sender - the user who sends the sms.
	 * @arg string number - the number to be sent to.
	 * @arg string text - the text of the sms.
	 */
	public function __construct($scenario = 'insert', User $sender = NULL, $number = '', $text = ''){
		parent::__construct($scenario);
		//Статус устанавливаем только "создана".
		$this -> status = 99;
		if (is_a($sender, 'User')) {
			$this -> id_user = $sender -> id;
			$this -> number = $sender -> tel;
			$this -> user = $sender;
		}
		if (!self::validateNumber($this -> number)) {
			$this -> number = $number;
		}
		if (!(self::validateNumber($this -> number))) {
			$this -> number = NULL;
		}
		$this -> text = $text;
	}
	/**
	 * @arg string number - the number to be validated
	 * @return bool - whether the number is valid
	 */
	public static function validateNumber($number){
		return preg_match('/^((8|\+7|7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/',$number);
	}
	/**
	 * Sends the sms.
	 * @arg integer time - moment the sms has to be sent.
	 * @return bool / \Zelenin\SmsRu\Api - information about the sent sms.
	 */
	public function send($time = false){
		$text = $this -> text;
		if (is_a($this -> user,'User')) {
			$text = SmsPattern::prepareText($this -> user, $this -> text);
		}
		if (($text)&&(self::validateNumber($this -> number))) {
			//echo "123";
			//$this -> number = 79523660187;
			$resp = Yii::app() -> sms -> sendSms($this -> number, $text, $time);
			if ($this -> isNewRecord) {
				$this -> ApiId = current($resp -> ids);
				echo $this -> ApiId;
				if ($this -> save()) {
					//echo "saved";
				} else {
					//vardump($this -> getErrors());
				}
			}
			return $resp;
		}
	}
	/**
	 * @var mixed arg - the argument to the custom search.
	 * @return Sms|null
	 */
	public function customFind($arg) {
		if (preg_match('/^\d*$/',$arg)) {
			return $this -> findByPk($arg);
		} else {
			return $this -> findByAttributes(array('ApiId' => $arg));
		}
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'required'),
			array('status, id_user', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>20),
			array('number', 'length', 'max'=>30),
			array('text', 'length', 'max'=>1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, status, number, id_user, text', 'safe', 'on'=>'search'),
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
			'action' => array(self::HAS_ONE, 'UserAction', 'id_sms')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'status' => 'Status',
			'number' => 'Number',
			'id_user' => 'Id User',
			'text' => 'Text',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sms the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * @arg integer status - the status to be set.
	 * @return bool - whether the change was successful.
	 */
	public function changeStatus($status) {
		$this -> status = $status;
		//Если смс была отправлена в качестве действия над юзером, то
		// при смене статуса, сообщаем об изменении в действие.
		if ($act = $this -> action) {
			//Добавляем в комментарий информацию.
			$act -> log (self::decodeDescr($status));
		}
		return $this -> save();
	}

	/**
	 * @param $id - id of the sms status
	 * @return string - explanation of the code
	 */
	public static function decodeDescr($id) {
		return self::$descriptions[$id];
	}
	/**
	 * @return array - descriptions of sms statuses.
	 */
	public static function giveDescriptions() {
		return self::$descriptions;
	}
}
