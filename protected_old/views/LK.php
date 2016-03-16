<?php
	if (!Yii::app() -> user -> isGuest) {
		$array = array(
			1 => '//admin',
			2 => '//mainDoc',
			3 => '//doctor'
		);
		$user = $model;
		//$type = Yii::app() -> user -> getState('type');
		$type = $user -> id_type;
		if (in_array($type, array_keys($array))) {
			$this -> renderPartial($array[$type], array('user' => $user));
		}
	} else {
		$this -> redirect('site/login');
	}
?>