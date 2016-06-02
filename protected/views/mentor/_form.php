<?php
	CustomFlash::showFlashes();
?>
	
<!--<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front dialog ui-dialog-buttons ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-describedby="doctor-dialog" aria-labelledby="ui-id-1" style="position: absolute; height: auto; width: 550px; top: 2566px; left: 353px; display: block; z-index: 101;"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-1" class="ui-dialog-title">Форма добавление/редактирования партнера</span><button type="button" class="ui-dialog-titlebar-close"></button></div><div id="doctor-dialog" class="ui-dialog-content ui-widget-content" style="width: auto; min-height: 25px; max-height: none; height: auto;">-->
<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'mentor-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    ));?>
	<?php 
		//Yii::app()->getClientScript()->registerScript('check', 'alert($("select").select2());',CClientScript::POS_END);
	?>
        <fieldset>
            

            <div class="well">
                <div class="form-group">
                    <label for="name">Имя</label>
                    <?php echo $form->textField($model, 'name',array('size'=>60,'maxlength'=>255,'placeholder'=>'Имя')); ?>
                </div>


				<div class="form-group">
					<label for="email">Email</label>
					<?php echo CHtml::activeEmailField($model, 'email', array('class' => 'form-control')); ?>
					<!--<input type="email" class="form-control" id="email" name="email" placeholder="" title="" data-original-title="">-->
				</div>
				
                <div class="form-group">
                    <label for="telephone">Телефон</label>
                    <?php echo $form->textField($model, 'telephone',array('size'=>60,'maxlength'=>255,'placeholder'=>'Номер телфона куратора')); ?>
                </div>
				
				<div class="form-group">
                    <label for="skype">login skype</label>
                    <?php echo $form->textField($model, 'skype',array('size'=>60,'maxlength'=>255,'placeholder'=>'skype')); ?>
                </div>
            </div>
        </fieldset>

<!--</div>--><div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset"><?php echo CHtml::submitButton($model->isNewRecord ? CHtml::encode('Создать') : CHtml::encode('Сохранить')); ?><button type="button" onClick="history.back()">Отмена</button></div></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div><ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-2" tabindex="0" style="display: none;"></ul><ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-3" tabindex="0" style="display: none;"></ul></div><span role="status" aria-live="assertive" aria-relevant="additions" class="ui-helper-hidden-accessible"></span><span role="status" aria-live="assertive" aria-relevant="additions" class="ui-helper-hidden-accessible"></span>
<?php $this -> endWidget(); ?>


<div class="ui-widget-overlay ui-front" style="z-index: 100;"></div>

