<h4>Импортировать врачей</h4>
<?php CustomFlash::showFlashes(); ?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'doctors-import-form',
	'action' => Yii::app() -> baseUrl.'/site/doctorsImport',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data')
));?>
<input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
<div>
	<?php echo CHtml::fileField('DoctorsFile'); ?>
</div>

<div class="buttons">
	<?php echo CHtml::submitButton(CHtml::encode('Загрузить файл')); ?>
</div>
<?php $this -> endWidget(); ?>