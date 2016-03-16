<?php $this -> renderPartial('//navBar',array('user' => User::model() -> findByPk(Yii::app() -> user -> getId()))); ?>
<?php
	$this -> renderPartial('//_form_file_upload');
	$this -> renderPartial('//_form_file_upload_client');
	//$this -> renderPartial('//users/_doctor_import');
	$this -> renderPartial('//_telephone_numbers_list');
	$this -> renderPartial('//_object_list',array(
		'htmlOptions' => array('id' => 'addressList'),
		'label' => 'Список адресов/клиник, где работают партнеры',
		'criteria' => new CDbCriteria,
		'display' => function($object){ return $object -> address; },
		'modelName' => 'UserAddress'
	));
	$this -> renderPartial('//_object_list',array(
		'htmlOptions' => array('id' => 'TestAddressList'),
		'label' => 'Список клиник, где делают анализы',
		'criteria' => new CDbCriteria,
		'display' => function($object){ return $object -> name; },
		'modelName' => 'TestAddress'
	));
	$this -> renderPartial('//_mentor_list');
	
?>