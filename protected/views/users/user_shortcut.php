<?php
	/**
	 * @var user - user model
	 */
	echo "<tr>";
	echo "<td>";
	echo CHtml::link($user -> fio, $this -> createUrl('site/cabinet', array('arg' => $user -> username)), array("target" => "_blank"));
	$user -> setParent();
	echo CHtml::link('<span class="glyphicon glyphicon-pencil edit-doctor"></span>',Yii::app() -> baseUrl.$user -> parent -> giveUserNameForPage().'/edit/'.$user -> id, array("target" => "_blank"));
	
	//echo $user -> fio;
	echo "</td>";
	echo "<td>";
	echo $user -> type -> name;
	echo "</td>";
	echo "<td>";
	echo $user -> userSpeciality -> name;
	echo "</td>";
	echo "<td>";
	echo $user -> giveAddressString();
	echo "</td>";
	echo "<td>";
	echo '<span class="delete-doctor" name="'.$user -> fio.'" goto="'.Yii::app() -> baseUrl.'/delete/'.$user -> id.'">del</span>';
	echo "</td>";
	
	echo "</tr>";
?>