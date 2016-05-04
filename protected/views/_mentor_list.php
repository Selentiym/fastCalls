<form>
<div class = "row">
	<label>Список кураторов</label>
	<?php 
		$data = UserMentor::model() -> findAll();
		$data = CHtml::listData($data, 'id', function($mentor){
			return $mentor -> name;
		});
		echo CHtml::activeDropDownListChosen2(UserMentor::model(),'id',$data, array('name'=>'mentors', 'id' => 'mentors','style' => 'width:200px'),array(),'');
		echo CHtml::button('Изменить', array('id' => 'changeMentor'));
		echo CHtml::button('Создать', array('id' => 'createMentor'));
		echo CHtml::button('Удалить', array('id' => 'deleteMentor'));
		Yii::app()->clientScript->registerScript('clickScriptForMentors', "
			$('#changeMentor').click(function(){
				location.replace('".$this -> createUrl('/MentorUpdate')."/'+$('#mentors').val());
			});
			$('#deleteMentor').click(function(){
				location.replace('".$this -> createUrl('MentorDelete')."/'+$('#mentors').val());
			});
			$('#createMentor').click(function(){
				location.replace('".$this -> createUrl('MentorCreate')."');
			}
		);
		");
	?>
</div>
</form>