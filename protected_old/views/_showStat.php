<tr class="<?php echo UserType::model() -> getRole($user -> id_type); ?>">
<td>
<?php echo $num; ?>
</td>
<td>
<?php echo CHtml::link($user -> fio, $this -> createUrl('site/cabinet', array('arg' => $user -> username)), array("target" => "_blank"));
$user -> setParent();
echo CHtml::link('<span class="glyphicon glyphicon-pencil edit-doctor"></span>',Yii::app() -> baseUrl.$user -> parent -> giveUserNameForPage().'/edit/'.$user -> id,array('target' => "_blank"));
if ($user -> id_type == UserType::model() -> getNumber('doctor'))
	echo "<br/>(От ".CHtml::link($user -> parent -> fio, $this -> createUrl('site/cabinet', array('arg' => $user -> parent -> username))).")"; ?>
</td>
<td><?php echo $user -> tel; ?></td>
<!--<td><?php echo $user -> giveStringFromArray($user -> phones, ',', 'number'); ?></td>-->
<td><?php echo $user -> email; ?></td>
<td><?php echo date('d.n.Y',strtotime($user -> create_time)); ?></td>
<td>
<?php echo '<span class="delete-doctor" name="'.$user -> fio.'" goto="'.Yii::app() -> baseUrl.'/delete/'.$user -> id.'">del</span>'; ?>
</td>

<?php
$all_common = 0;
$all_good = 0;
$all_ver = 0;
	foreach($data -> giveWeekedStats($user, $from, $to) as $week) {
		if ($week == -1) {
			echo "<td></td>";
		} else {
			echo "<td class='month'>";
			echo $week['common']. '-> '.($week['assigned'] + $week['verifyed']).'-> '. $week['verifyed'];
			$all_common += $week['common'];
			$all_good += $week['assigned'] + $week['verifyed'];
			$all_ver += $week['verifyed'];
			echo "</td>";
		}
	}
?>
<td class='month'><?php echo $all_common."-> ".$all_good."-> ".$all_ver; ?></td>
</tr>