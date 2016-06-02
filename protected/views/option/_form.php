<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.04.2016
 * Time: 13:25
 */
CustomFlash::showFlashes();
?>

<!--<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front dialog ui-dialog-buttons ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-describedby="doctor-dialog" aria-labelledby="ui-id-1" style="position: absolute; height: auto; width: 550px; top: 2566px; left: 353px; display: block; z-index: 101;"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-1" class="ui-dialog-title">Форма добавление/редактирования партнера</span><button type="button" class="ui-dialog-titlebar-close"></button></div><div id="doctor-dialog" class="ui-dialog-content ui-widget-content" style="width: auto; min-height: 25px; max-height: none; height: auto;">-->
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'option-form',
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
            <?php echo $form->textField($model, 'name',array('size'=>60,'maxlength'=>255,'placeholder'=>'Название')); ?>
        </div>
    </div>
    <div class="well">
        <div class="form-group">
            <label for="name">Логотип</label>
            <?php
                if (!empty($model->logo)) {
                    //$logo = Yii::app()->baseUrl.'/images/companies/' . $model->id . '/' .$model->logo;
                    $logo = $model -> giveImageFolderRelativeUrl() . $model->logo;
                    echo '<div id="logo">' . CHtml::ajaxLink('<img src="'.Yii::app() -> baseUrl.'/images/cross_small.png"/>', Yii::app() -> baseUrl.'/propDelete', array('type'=> 'POST', 'data'=>array('modelClass' => 'UserOption', 'prop' => 'logo', 'arg' => $model -> id), 'success' => 'js: $("#logo").hide()'))
                        . CHtml::image($logo, CHtml::encode('Логотип'),
                            array('style' => 'max-width:172px;max-height:200px; padding: 8px 0px 8px 15px;')) . '</div>';
                }
                echo $form->fileField($model, 'logo');
            ?>
        </div>
    </div>
</fieldset>

<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
    <div class="ui-dialog-buttonset">
        <?php echo CHtml::submitButton($model->isNewRecord ? CHtml::encode('Создать') : CHtml::encode('Сохранить')); ?>
        <button type="button" onClick="history.back()">Отмена</button>
    </div>
</div>
<?php $this -> endWidget(); ?>


<div class="ui-widget-overlay ui-front" style="z-index: 100;"></div>


