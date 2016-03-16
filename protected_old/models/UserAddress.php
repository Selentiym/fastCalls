<?php

/**
 * This is the model class for table "{{address}}".
 *
 * The followings are the available columns in table '{{address}}':
 * @property integer $id
 * @property string $address
 * @property string $physical_address
 * @property string $note
 *
 * The followings are the available model relations:
 * @property AddressAssignments[] $addressAssignments
 */
class UserAddress extends UModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{address}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('address', 'length', 'max'=>1024),
			array('address', 'required'),
			array('address', 'unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, address, physical_address, note', 'safe'),
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
			'addressAssignments' => array(self::HAS_MANY, 'AddressAssignments', 'id_address'),
			'users' => array(self::MANY_MANY, 'User', '{{address_assignments}}(id_address, id_user)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'address' => 'Address',
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
		$criteria->compare('address',$this->address,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * @return boolean whether to save record or not.
	 */
	/*public function beforeSave(){
		if (parent::beforeSave()) {
			if (!$this -> address) { return false }
			$old = self::model() -> findByPk($this -> id);
			if (($old -> address != $this -> address)||($this -> isNewRecord)) {
				$num = 0;
			} else {
				$num = 1;
			}
			$dups = self::model() -> findByAttributes();
		} else {
			return false;
		}
	}*/
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserAddress the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * @return boolean - whether the user has rights to delete record
	 */
	public function checkDeleteAccess(){
		return Yii::app() -> user -> checkAccess('admin');
	}
}
