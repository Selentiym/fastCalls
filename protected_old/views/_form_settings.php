<?php $model = Setting::model() -> find(); ?>
<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'setting-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('e	nctype'=>'multipart/form-data')
    ));?>
<?php //echo $form->labelEx($model,'main_text'); ?>
<?php
	/*$this->widget('application.extensions.tinymce.TinyMce',
	array(
		'model'=>$model,
		'attribute'=>'comment_text',
		//'editorTemplate'=>'full',
		'skin'=>'cirkuit',
		'htmlOptions'=>array('rows'=>5, 'cols'=>30, 'class'=>'tinymce'),
	));*/
?>
<h4>Год звонков</h4>
<?php echo CHtml::activeTextField($model, 'year'); ?>
<h4>Разрешить ли докторам добавлять новые адреса при создании пользователя</h4>
<div>
<?php //echo CHtml::activeRadioButtonList($model,'allowMDCreateAddresses',array(1 => 'Разрешить', 0 => 'Не разрешить')); ?>
<?php echo CHtml::radioButtonList(get_class($model).'[allowMDCreateAddresses]',$model -> allowMDCreateAddresses,array(1 => 'Разрешить', 0 => 'Не разрешить')); ?>
</div>
<!--<div>
<?php echo CHtml::activeTextArea($model, 'comment_stat'); ?>
</div>
<div>
<?php echo CHtml::activeRadioButtonList($model,'comment_show',array(1 => 'Показать', 0 => 'Не показать')); ?>
</div>-->
<h4>Показывать ли статистику по клиникам при щелчке по картинке</h4>
<div>
<?php echo CHtml::activeRadioButtonList($model,'showClinicStat',array(1 => 'Показать', 0 => 'Не показать')); ?>
</div>
<div>
<?php echo CHtml::submitButton('Сохранить'); ?>
</div><button type="button" onClick="history.back()">Отмена</button>
<?php $this -> endWidget(); ?>
<?php $this -> renderPartial('//_form_stat_upload'); ?>