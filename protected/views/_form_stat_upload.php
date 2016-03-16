<h4>Подгрузить картинку со статистикой</h4>
<?php CustomFlash::showFlashes(); ?>
<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'uploadFileStat-form',
		'action' => Yii::app() -> baseUrl.'/site/statUpload',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data')
    ));?>
   <input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
   <div>
		<?php echo CHtml::fileField('statImageUpload'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton(CHtml::encode('Загрузить картинку')); ?>
	</div>
	<?php $this -> endWidget(); ?>
	Сейчас на сайте:
	<?php echo CHtml::image(Yii::app() -> baseUrl.'/images/stat.jpg?rand='.rand(), '',array('style' => 'width:200px')); ?>