<?php
	
	if($user -> id_type == UserType::model() -> getNumber('maindoc')) {
		$user -> prepareCalls();
	}
	$calls = $data -> giveCallsInRange($from, $to, $user);
	/*if ($user -> id_type == UserType::model() -> getNumber('doctor')) {
		$calls = $user -> calls;
	} elseif($user -> id_type == UserType::model() -> getNumber('maindoc')) {
		$user -> prepareCalls();
		$calls = $user -> calls;
	}*/
	if (($calls)&&(count($calls > 0))) :
	$counted = Data::model() -> countArray($calls);
?>

<tr class="<?php echo UserType::model() -> getRole($user -> id_type); ?>">
<td><?php echo $num; ?></td>
<td>
<?php echo CHtml::link($user -> fio, $this -> createUrl('site/cabinet', array('arg' => $user -> username)), array("target" => "_blank"));
$user -> setParent();
echo CHtml::link('<span class="glyphicon glyphicon-pencil edit-doctor"></span>',Yii::app() -> baseUrl.$user -> parent -> giveUserNameForPage().'/edit/'.$user -> id, array("target" => "_blank"));
if ($user -> id_type == UserType::model() -> getNumber('doctor'))
	echo "<br/>(От ".CHtml::link($user -> parent -> fio, $this -> createUrl('site/cabinet', array('arg' => $user -> parent -> username)), array("target" => "_blank")).")"; ?>
</td>

<td><?php echo $counted['common'].' -> '. ($counted['common'] - $counted['side'] - $counted['declined']); ?></td>

<td><?php echo $counted['assigned']; ?></td>

<td><?php echo $counted['missed'] + $counted['cancelled']; ?></td>

<td><?php echo $counted['verifyed']; ?></td>

<!--<td><?php echo $user -> giveStringFromArray($user -> phones, ',', 'number'); ?></td>-->
<td><?php echo $user -> givePayString(); ?></td>

<td><?php echo $user -> tel."<br/>". $user -> email; ?></td>

<td>
<?php $this -> renderPartial('//_conditions',array('user' => $user));?>
</td>

</tr>
<?php endif; ?>