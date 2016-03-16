<h4>Подгрузить статистику Google Doc (Во вторую очередь!)</h4>
<?php CustomFlash::showFlashes(); ?>
<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'uploadFile-form',
		'action' => Yii::app() -> baseUrl.'/site/uploadFile',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data')
    ));?>
	<input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
	<div>
		<?php echo CHtml::fileField('inputFile'); ?>
	</div>
	
	<div>
	<h4>Введите год звонков:</h4>
		<?php echo CHtml::textField('year',Setting::model() -> find() -> year); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton(CHtml::encode('Загрузить файл')); ?>
	</div>
	<?php $this -> endWidget(); ?>