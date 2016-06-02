<?php 
if (Setting::model() -> find() -> showcompanyStat){
	echo CHtml::link(CHtml::image(Yii::app() -> baseUrl.'/images/stat.jpg', '',array('style' => 'width:200px')),Yii::app() -> baseUrl.'/reviewstat');
} else {
	echo CHtml::image(Yii::app() -> baseUrl.'/images/stat.jpg?rand='.rand(), '',array('style' => 'width:200px'));
} ?>