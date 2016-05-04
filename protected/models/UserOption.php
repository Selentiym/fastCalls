<?php

/**
 * This is the model class for table "{{user_options}}".
 *
 * The followings are the available columns in table '{{user_options}}':
 * @property integer $id
 * @property string $name
 * @property string $logo
 */
class UserOption extends UModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_options}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, logo', 'required'),
			array('name', 'length', 'max'=>1024),
			array('logo', 'length', 'max'=>2048),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, logo', 'safe', 'on'=>'search'),
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
			'users' => array(self::MANY_MANY, 'User', '{{user_option_assignments}}(id_option, id_user)'),
			//'userAssignments' => array(self::HAS_MANY,'UserOptionAssignment','id_option')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'logo' => 'Logo',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('logo',$this->logo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserOption the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * Dumps information about the object in json style.
	 */
	public function DumpForJS(){
		$info['name'] = $this -> name;
		$info['users'] = CHtml::giveAttributeArray($this -> users, 'id');
		echo json_encode($info, JSON_PRETTY_PRINT);
	}
	/**
	 * @param $files_arr - $_FILES array
	 * @param string $imageProp - property that contains image name
	 */
	public function FileOperations($files_arr) {
		parent::uploadImage($files_arr, 'logo');
		return true;
	}
}