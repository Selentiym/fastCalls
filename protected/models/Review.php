<?php

/**
 * This is the model class for table "{{review}}".
 *
 * The followings are the available columns in table '{{review}}':
 * @property integer $id
 * @property string $company
 * @property string $doctor
 * @property string $repair_type
 * @property integer $rating
 * @property string $review
 * @property integer $our
 * @property integer $id_call
 * @property integer $id_user
 *
 * The followings are the available model relations:
 * @property Call $call
 * @property User $user
 */
class Review extends UModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{review}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rating, our', 'required'),
			array('rating, our, id_call', 'numerical', 'integerOnly'=>true),
			array('id_company, doctor', 'length', 'max'=>1024),
			array('repair_type', 'length', 'max'=>256),
			array('review', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_company, doctor, repair_type, rating, review, our, id_call', 'safe', 'on'=>'search'),
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
			'call' => array(self::BELONGS_TO, 'Call', 'id_call'),
			'user' => array(self::BELONGS_TO, 'User', 'id_user'),
			'company' => array(self::BELONGS_TO, 'TestAddress','id_company')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'company' => 'company',
			'doctor' => 'Doctor',
			'repair_type' => 'Research Type',
			'rating' => 'Rating',
			'review' => 'Review',
			'our' => 'Our',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('doctor',$this->doctor,true);
		$criteria->compare('repair_type',$this->repair_type,true);
		$criteria->compare('rating',$this->rating);
		$criteria->compare('review',$this->review,true);
		$criteria->compare('our',$this->our);
		$criteria->compare('id_call',$this->id_call);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Review the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function readData($get){
		//Если подан айди звонка, то считаываем с него данные и помечаем, что клиент наш
		if (isset($get['id_call'])) {
			$this -> id_call = $get['id_call'];
			$this -> our = 1;
			$this -> call  = BaseCall::model() -> findByPk($get['id_call']) ;
			$call = $this -> call;
			$this -> company = $call -> company;
			$this -> repair_type = $call -> repair_type;
		} else {
			//Если же нет айди звонка, то помечаем, что юзер не наш
			$this -> our = 0;
		}
		
		$this -> id_user = User::model() -> customFind($get['arg']) -> id;
	}
	public function checkCreateAccess($arg){
		return Yii::app() -> user -> checkAccess('viewOwnUserCabinet',array('user' => User::model() -> customFind($arg)));
	}
	public function checkDeleteAccess(){
		return Yii::app() -> user -> checkAccess('admin');
	}
	/**
	 * @return array - an array of TestAddress objects with attributes sum and countReviews set.
	 */
	public function giveStat() {
		$criteria = new CDbCriteria;
		$criteria -> group = 'id_company';
		$criteria -> with = 'company';
		$reviews = self::model() -> findAll($criteria);
		$comm = Yii::app() -> db -> createCommand();
		$comm -> select('COUNT(`rating`) as t1,SUM(`rating`) as t2');
		$comm -> where('`id_company` = :id');
		$comm -> from('{{review}}');
		$companies = array();
		foreach($reviews as $review) {
			$company = $review -> company;
			$comm -> params = array(':id' => $company -> id);
			$rez = $comm -> queryAll();
			$company -> sum = $rez[0]['t2'];
			$company -> countReviews = $rez[0]['t1'];
			$companies[] = $company;
		}
		return $companies;
	}
}
