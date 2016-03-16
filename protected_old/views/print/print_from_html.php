<?php
	$user = $model;
	if ((Yii::app() -> user -> checkAccess('viewOwnUserCabinet',array('user' => $user)))||((Yii::app() -> user -> checkAccess('viewUserCabinet')))||(Yii::app() -> user -> checkAccess('viewChildUserCabinet',array('user' => $user)))) {
	//if (Yii::app() -> user -> checkAccess('viewChildUserCabinet',array('user' => $user))) {
		Yii::app()->getClientScript()->registerScript('PrintScript','window.print()',CClientScript::POS_READY);
		Yii::app()->getClientScript()->registerCssFile(Yii::app() -> baseUrl .'/css/printDirections.css','print');
		Yii::app()->getClientScript()->registerCssFile(Yii::app() -> baseUrl .'/css/printDirections_page.css','screen');
		echo CHtml::tag('div', array('id' => 'to_print'));
		$this -> renderPartial('//print/maket',array('user' => $model));
		echo "</div>";
		echo CHtml::tag('div', array('id' => 'show_user'));
		echo "Направления распечатаны.";
		echo "</div>";
	} else {
		$this -> renderPartial('//accessDenied');
	}
?>