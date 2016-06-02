<tr class="<?php echo $call -> Classify(); ?>">
<?php
//'verifyed', 'missed', 'cancelled', 'side', 'declined', 'assigned')
	$translate = array(
		'verifyed' => 'Подтвержден',
		'missed' => 'Не пришел',
		'cancelled' => 'Отменен',
		'side' => 'Нецелевой',
		'declined' => 'Не записан',
		'assigned' => 'Записан',
	);
?>
<td><?php echo $num; ?></td>
<td><?php echo $translate[$call -> Classify()]; ?></td>
<td><?php echo $call -> date; ?></td>
<td><?php echo $call -> user -> fio; ?></td>
<td><?php echo $call -> number; ?></td>
<td><?php echo $call -> giveReport(); ?></td>
<td><?php echo $call -> giveName(); ?></td>
<td><?php echo $call -> repair_type; ?></td>
<td><?php echo CHtml::link('Отзыв', Yii::app() -> baseUrl.'/addreview/'.$call -> id); echo $call -> unlinkTag() ? '<br/>'.$call -> unlinkTag() : '' ;?></td>

</tr>