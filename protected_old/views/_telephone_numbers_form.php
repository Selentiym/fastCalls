<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'phones-form',
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
		<?php echo $form->textField($model, 'number',array('size'=>60,'maxlength'=>255,'placeholder'=>'Телефонный номер')); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->textField($model, 'ivr',array('size'=>60,'maxlength'=>255,'placeholder'=>'IVR')); ?>
		<?php echo $form->error($model,'ivr'); ?>
	</div>
	
	<div class="form-group">
		<?php echo CHtml::submitButton($model -> isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>
</div>
<?php $this -> endWidget(); ?>
