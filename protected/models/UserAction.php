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
 * @property string $period
 * @property string $comment
 * @property string $report
 * @property integer $auto
 * @property integer $id_sms
 */
class UserAction extends UModel implements iUserAction
{
	/**
	 * status for the just created actions.
	 */
	const CREATED = 1;
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
			array('id_owner, id_user, id_chain, id_type, id_status, created', 'required'),
			array('id_owner, id_user, id_chain, id_type, id_status, auto, id_sms', 'numerical', 'integerOnly'=>true),
			array('period', 'length', 'max'=>512),
			array('time, comment, report', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_owner, id_user, id_chain, id_type, id_status, created, time, period, comment, report, auto, id_sms', 'safe', 'on'=>'search'),
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
		// @todo Please modify the following code to remove attributes that should not be searched.

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
		$this -> comment = $data -> comment;
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
			$this -> auto = true;
		}
		$this -> id_status = self::CREATED;
	}
}
