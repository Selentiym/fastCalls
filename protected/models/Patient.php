<?php

/**
 * This is the model class for table "{{patients}}".
 *
 * The followings are the available columns in table '{{patients}}':
 * @property integer $id
 * @property integer $id_user
 * @property string $fio
 * @property string $tel
 * @property string $note
 * @property string $create_time
 */
class Patient extends UModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{patients}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, fio, create_time', 'required'),
			array('id_user', 'numerical', 'integerOnly'=>true),
			array('fio', 'length', 'max'=>512),
			array('tel, note', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_user, fio, tel, note, create_time', 'safe', 'on'=>'search'),
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
			'doctor' => array(self::BELONGS_TO,'User', 'id_user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
			'fio' => 'Fio',
			'tel' => 'Tel',
			'note' => 'Примечание',
			'create_time' => 'Create Time',
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
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('fio',$this->fio,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Patient the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * @arg array get - the $_GET variable. 
	 * This function is used to set some initial properties of the model 
	 * that are populated from the url along with modelClass
	 */
	public function readData () {
		if (User::model() -> findByPk($_GET["id"])) {
			$this -> id_user =$_GET["id"];
		} else {
			new CustomFlash('error', 'Patient','IncorrectId','Ошибка при определении партнера, который добавляет пациента.',true);
		}
	}
	public function beforeSave() {
		if (parent::beforeSave()) {
			$this -> create_time = new CDbExpression('STR_TO_DATE("'.trim($this -> create_time).' 12:00:00", "%m/%d/%Y %H:%i:%s" )');
			return true;
		} else {
			return false;
		}
	}
	public function redirectAfterCreate($external){
		$verb = User::model() -> findByPk($this -> id_user) -> username;
		return Yii::app() -> baseUrl.'/'.$verb.'/patients';
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
