<?php
	$form=$this->beginWidget('CActiveForm', array(
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		'action' => Yii::app() -> baseUrl . '/assignCall'
    ));
	echo CHtml::hiddenField('ApiId', $tCall -> ApiId);
?>

<tr>
<td><?php echo CHtml::tag('input', array("type"=>"checkbox", "class" => "groupCheckbox","value" => $tCall -> ApiId)); ?></td>
<td><?php echo $tCall -> time; ?></td>
<td class='mistake'><?php echo $tCall -> error -> text; ?></td>
<td><?php echo $tCall -> caller; ?></td>
<?php $phone = $tCall -> phone; ?>
<td><?php echo $phone -> number .":". $phone -> id; ?></td>
<td><a href="<?php echo Yii::app() -> baseUrl."/searchAgain/{$tCall -> ApiId}"; ?>">Искать</a></td>
<td><?php echo $tCall -> giveStringFromArray($phone -> regular_users,',','fio'); ?></td>

<td><?php echo CHtml::link('del', Yii::app() -> baseUrl.'/deleteTelfinCall/'.$tCall -> ApiId); ?></td>

</tr>
<?php
	$this -> endWidget();
?>