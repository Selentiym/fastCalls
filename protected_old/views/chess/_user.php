<?php
	echo CHtml::link($user -> fio, $this -> createUrl('site/cabinet', array('arg' => $user -> username)));
?>