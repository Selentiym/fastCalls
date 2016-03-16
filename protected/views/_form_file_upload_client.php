<h4>Подгрузить статистику Mango</h4>
<?php CustomFlash::showFlashes(); ?>
<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'uploadFileClient-form',
		'action' => Yii::app() -> baseUrl.'/site/clientphonesupload',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data')
    ));?>
   <input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
   <div>
		<?php echo CHtml::fileField('ClientPhoneUpload'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton(CHtml::encode('Загрузить файл')); ?>
	</div>
	<?php $this -> endWidget(); ?>