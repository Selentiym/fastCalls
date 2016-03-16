<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'patients-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
        //'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    ));?>
        <fieldset>
            <div class="well">
                <div class="form-group">
                    <label for="name">ФИО</label>
                    <?php echo $form->textField($patient, 'fio',array('size'=>60,'maxlength'=>255,'placeholder'=>'ФИО')); ?>
                </div>
                <div class="form-group">
                    <label for="name">Телефон</label>
                    <?php echo $form->textField($patient, 'tel',array('size'=>60,'maxlength'=>255,'placeholder'=>'Телефон')); ?>
                </div>
                <div class="form-group">
                    <label for="name">Дата исследования</label>
                    <?php //echo $form->hiddenField($patient, 'create_time',array()); ?>
                    <?php echo $form->textField($patient, 'create_time',array('id' => 'date')); ?>
					<?php CHtml::setDatePicker('date',''); ?>
                </div>
				<div class="form-group">
                    <label for="name">Примечание</label>
                    <?php echo $form->textArea($patient, 'note',array('placeholder'=>'Комментарий')); ?>
                </div>
			</div>
		</fieldset>
		<?php echo CHtml::submitButton($patient->isNewRecord ? CHtml::encode('Создать') : CHtml::encode('Сохранить')); ?>
<?php $this -> endWidget(); ?>