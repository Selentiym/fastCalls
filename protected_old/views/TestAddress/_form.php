<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'address-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false
));
?>
<div class="flash">
<?php CustomFlash::showFlashes(); ?>
</div>
<div class="well">
	<div class="form-group">
		<?php echo $form->textField($model, 'name',array('size'=>60,'maxlength'=>255,'placeholder'=>'Название клиники')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->textField($model, 'address',array('size'=>60,'maxlength'=>255,'placeholder'=>'Адрес клиники')); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->textArea($model, 'note',array('size'=>60,'placeholder'=>'Примечание')); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>
	<div class="form-group">
		<?php echo CHtml::submitButton($model -> isNewRecord ? 'Создать' : 'Сохранить'); ?>
		<button type="button" onClick="history.back()">Отмена</button>
	</div>
</div>
<?php $this -> endWidget(); ?>
