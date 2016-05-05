<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.04.2016
 * Time: 13:06
 */
?>
<form>
    <div class = "row">
        <label>Список параметров</label>
        <?php
        UserOption::prettySelect();
        echo CHtml::button('Изменить', array('id' => 'changeOption'));
        echo CHtml::button('Создать', array('id' => 'createOption'));
        echo CHtml::button('Удалить', array('id' => 'deleteOption'));
        Yii::app()->clientScript->registerScript('clickScriptForOptions', "
			$('#changeOption').click(function(){
				location.replace('".$this -> createUrl('/OptionUpdate')."/'+$('#options').val());
			});
			$('#deleteOption').click(function(){
				location.replace('".$this -> createUrl('OptionDelete')."/'+$('#options').val());
			});
			$('#createOption').click(function(){
				location.replace('".$this -> createUrl('OptionCreate')."');
			}
		);
		");
        ?>
    </div>
</form>
