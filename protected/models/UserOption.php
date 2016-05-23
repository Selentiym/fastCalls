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
			array('name', 'required'),
			array('name', 'length', 'max'=>1024),
			array('logo', 'length', 'max'=>2048),
			array('name', 'safe'),
			array('logo', 'unsafe'),
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
		if ($this -> checkImage('logo')) {
			$info['image'] = $this -> giveImageFolderRelativeUrl(). $this -> logo;
		}
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

	/**
	 * @param UserOption[] $data - options to be displayed
	 * @param string $id - id of the select container
	 * @param string $appendTo - id of the element to append select2 to
	 * @param string $placeholder - placeholder text
	 * @param bool $tags - whether to allow adding new options
	 */
	public static function prettySelect($data = false, $id = 'options', $appendTo = false, $placeholder = false, $tags = false){
		if ($data === false) {
			$data = self::model()->findAll();
		}
		if ($appendTo === false) {
			$appendTo = $id;
		}
		if ($tags) {
			$tags = 'tags:true,tokenSeparators:[";"],';
		} else {
			$tags = '';
		}
		/**
		 * @var array[] $htmlOptions_options - contains settings to be given to
		 * CHtml::listOptions() method.
		 */
		$htmlOptions_options = array();
		/*if ($placeholder) {
			$data [] = '';
		}*/
		foreach($data as $option){
			/**
			 * @var UserOption $option
			 */
			$url = $option -> giveImageFolderAbsoluteUrl();
			$url .=  $option -> logo;
			//if the image is accessible, se the data-image attr to use it later in JS
			if (file_exists($url)) {
				$htmlOptions_options [$option -> id] = array('data-image' => $option -> giveImageFolderRelativeUrl() . $option -> logo, 'style' => 'color:blue');
			}
			$dataToSelect[$option -> id] = $option -> name;
		}
		/*$data = CHtml::listData($data, 'id', function($option){
            $img = $option -> logo ? CHtml::image(Yii::app() -> baseUrl . '/images/'. $option -> logo, '', array('style' => 'height:20px;')) : '';
            return $img . $option -> name;

		'.
				($placeholder ? 'placeholder:'.$placeholder .',': '')
				.'


        });*/
		CHtml::activeDropDownListChosen2(UserOption::model(),'id',$dataToSelect, array('name'=>'options', 'id' => $id,'style' => 'width:200px', 'options' => $htmlOptions_options,'appendSelect2To' => $appendTo, 'empty' => $placeholder),array(),'{'.
				($placeholder ? 'placeholder:"'.$placeholder.'",' : '')
				.$tags.'
				templateResult: function(state){
                if (!state.element) {
                    return state.text;
                }
                //return state.text;
                return $("<img/>",{
                    src:$(state.element).attr("data-image"),
                    css: {
                        height:"20px",
                        margin:"2px",
                        "margin-right":"5px"
                    }
                }).after(state.text);
            }
        }');
	}
}
