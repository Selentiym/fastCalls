<tr class="<?php echo UserType::model() -> getRole($user -> id_type); ?>">
<td>
	<input type="checkbox" class="checkable" name="userGroup[]" value="<?php echo $user -> id; ?>"/>
</td>
<td>
<?php echo CHtml::link($user -> fio, $this -> createUrl('site/cabinet', array('arg' => $user -> username)), array("target" => "_blank"));
$user -> setParent();
echo CHtml::link('<span class="glyphicon glyphicon-pencil edit-doctor"></span>',Yii::app() -> baseUrl.$user -> parent -> giveUserNameForPage().'/edit/'.$user -> id, array("target" => "_blank"));
if ($user -> id_type == UserType::model() -> getNumber('doctor'))
	echo "<br/>(От ".CHtml::link($user -> parent -> fio, $this -> createUrl('site/cabinet', array('arg' => $user -> parent -> username))).")"; ?>
<td><?php echo $user -> tel; ?></td>
<!--<td><?php echo $user -> giveStringFromArray($user -> phones, ',', 'number'); ?></td>-->
<td><?php echo $user -> email; ?></td>
<td><?php echo date('d.n.Y',strtotime($user -> create_time)); ?></td>
<!-- new -->
<td>
<?php
	echo $user -> giveStringFromArray($user -> phones, ',', function($phone){return $phone -> showMe();});
?>
</td>
<td>
	<?php echo $user -> giveStringFromArray($user -> address_array, ',', 'address'); ?>
</td>
<td>
<?php
	if ($user -> jMin)
		echo $user -> jMin.' - '.$user -> jMax;
	if ($user -> jMin_add)
		echo "<br/>".$user -> jMin_add.' - '.$user -> jMax_add;
?>
</td>
<td>
<?php echo $user -> username; ?>
</td>
<td>
<?php echo $user -> conditions; ?>
<?php if (($user -> conditions_add)&&($user -> conditions)) { echo " - ";} ?>
<?php echo $user -> conditions_add; ?>
</td>
<td>
<?php echo $user -> givePayString(); ?>
</td>
</tr>