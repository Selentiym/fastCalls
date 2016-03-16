<?php /*
	$label - name of the list
	$criteria - cdbcriteria object
	$display - function to show object
	$modelName - name of the model to be in the list
	$htmlOptions
	
*/ ?>
<form>
<div class = "row">
	<label><?php echo $label; ?></label>
	<?php 
		$data = $modelName::model() -> findAll($criteria);
		$data = CHtml::listData($data, 'id', $display);
		echo CHtml::activeDropDownListChosen2($modelName::model(),'id',$data, $htmlOptions,array(),'');
		echo CHtml::button('Изменить', array('id' => 'change'.$modelName));
		echo CHtml::button('Создать', array('id' => 'create'.$modelName));
		echo CHtml::button('Удалить', array('id' => 'delete'.$modelName));
		Yii::app()->clientScript->registerScript('clickScriptFor'.$modelName, "
			$('#change".$modelName."').click(function(){
				location.href = ('".$this -> createUrl('/'.$modelName.'Update')."/'+$('#".$htmlOptions['id']."').val());
			});
			$('#delete".$modelName."').click(function(){
				location.href = ('".$this -> createUrl('/'.$modelName.'Delete')."/'+$('#".$htmlOptions['id']."').val());
			});
			$('#create".$modelName."').click(function(){
				location.href = ('".$this -> createUrl('/'.$modelName.'Create')."');
			}
			//$('#createRT').click(alert('asda'));
		);
		");
	?>
</div>
</form>