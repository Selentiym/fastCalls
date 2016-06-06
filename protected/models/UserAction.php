<?php

/**
 * This is the model class for table "{{actions}}".
 *
 * The followings are the available columns in table '{{actions}}':
 * @property string $id
 * @property integer $id_owner
 * @property integer $id_user
 * @property integer $id_chain
 * @property integer $id_type
 * @property integer $id_status
 * @property string $created
 * @property string $time
 * Содержит текстовую строку, в которой закодирована периодичность
 * повторения действия.
 * @property string $period
 * @property string $comment
 * Нужен только для действий, которые связаны с отправкой чего-либо
 * @property string $text
 * @property string $report
 * @property integer $auto
 * @property integer $id_sms
 *
 * Declared via relations
 * @property User $user
 */
class UserAction extends UModel implements iUserAction
{
	/**
	 * Содержит идентификатор типа действия в базе данных.
	 * Должен быть переопределен в каждом потомке!
	 */
	const TYPE = 0;
	/**
	 * status for the just created actions.
	 */
	const CREATED = 1;
	/**
	 * status for the postponed actions.
	 */
	const POSTPONED = 2;
	/**
	 * status for the postponed actions.
	 */
	const MISSED = 3;
	/**
	 * status for the postponed actions.
	 */
	const ERROR = 4;
	/**
	 * status for completed actions.
	 */
	const GOOD = 5;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{actions}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_owner, id_user, id_status', 'required'),
			array('id_owner, id_user, id_chain, id_type, id_status, id_sms', 'numerical', 'integerOnly'=>true),
			array('period', 'length', 'max'=>512),
			array('id_chain, comment, text, report, period', 'safe'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_owner' => 'Id Owner',
			'id_user' => 'Id User',
			'id_chain' => 'Id Chain',
			'id_type' => 'Id Type',
			'id_status' => 'Id Status',
			'created' => 'Created',
			'time' => 'Time',
			'period' => 'Period',
			'comment' => 'Comment',
			'report' => 'Report',
			'auto' => 'Auto',
			'id_sms' => 'Id Sms',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('id_owner',$this->id_owner);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('id_chain',$this->id_chain);
		$criteria->compare('id_type',$this->id_type);
		$criteria->compare('id_status',$this->id_status);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('period',$this->period,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('report',$this->report,true);
		$criteria->compare('auto',$this->auto);
		$criteria->compare('id_sms',$this->id_sms);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserAction the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * Sets basic properties of the action
	 */
	public function initialize($data){
		if (is_a($data['user'],'User')) {
			$this -> user = $data['user'];
			$data['id_user'] = $this -> user -> id;
		}
		$this -> attributes = $data;
		//$this -> comment = $data -> comment;
		if ($this -> isNewRecord) {
			$this -> firstTimeInitialize($data);
		}
		$this -> time = new CDbExpression("FROM_UNIXTIME({$data['time']})");
	}

	/**
	 * @param $data
	 */
	public function firstTimeInitialize($data){
		$this -> id_owner = Yii::app()->user->getId();
		$this -> id_user = $data['id_user'];
		if ($data['repeat']) {
			//Если auto = true, то в момент выполнения действие не
			// потребует участия пользователя.
			$this -> auto = true;
		}
		$this -> id_status = self::CREATED;
	}

	/**
	 * Сохраняет в историю действия что-то.
	 * @param string $logText
	 * @param bool $save whether to save changes to DB or to wait.
	 * @return bool whether the loging was successful.
	 */
	public function log($logText, $save = true) {
		$this -> comment .= PHP_EOL . date(CDateTime::longFormat) . $logText;
		if ($save) {
			return $this -> save();
		}
		return true;
	}
	/**
	 * Функция-заглушка
	 */
	public function MakeAction(){
		return;
	}

	/**
	 * Действия перед записью изменений в БД
	 */
	public function beforeSave() {
		if ((!$this -> id_type)) {
			//Если тип не задан в объекте, то берем его из класса.
			if (static::TYPE) {
				$this->id_type = static::TYPE;
			} else {
				//А вот если он и в классе отсутсвует, то ахтунг!
				return false;
			}
		}
		//Такая вот странная проверка на то, лежит ли в time метка времени Unix
		if ($this -> time > 10000000) {
			$this -> time = new CDbExpression("FROM_UNIXTIME('".$this -> time."'')");
		}
		return true;
	}
	/**
	 * We're overriding this method to fill findAll() and similar method result
	 * with proper models.
	 * @param array $attributes
	 * @return UserAction
	 */
	protected function instantiate($attributes) {
		//Получаем все возможные типы действий
		$types = UserActionFactory::$types;
		//Ищем название класса
		$class = $types[$attributes['id_type']];
		if (!$class) {
			//Если не нашли, то ставим класс по умолчанию.
			$class = UserActionFactory::DefaultClass;
		}
		$class = ucfirst(strtolower($class))."Action";
		//Возвращаем новую модель.
		$model = new $class(null);
		return $model;
	}

	/**
	 * Чтобы при вызове методов семества find потомка выдавались только
	 * соответсвующие ему записи, а не все.
	 * P.S. В потомке ОБЯЗАТЕЛЬНО должна быть объявлена const TYPE.
	 * @return array
	 */
	public function defaultScope(){
		//Основной класс UserAction имеет тип 0.
		//Нужно, чтобы при поиске через UserAction искались все записи.
		if (static::TYPE) {
			return array('condition' => "id_type = '" . static::TYPE . "'");
		} else {
			return array();
		}
	}
}
