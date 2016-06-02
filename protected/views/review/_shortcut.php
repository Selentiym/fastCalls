<tr>
	<td><?php echo $review -> company -> name; ?></td>
	<td><?php echo $review -> doctor; ?></td>
	<td><?php echo $review -> repair_type; ?></td>
	<td><?php echo $review -> rating; ?></td>
	<td><?php echo $review -> review; ?></td>
	<td><?php echo CHtml::link($review -> user -> fio,Yii::app() -> baseUrl . $user -> giveUserNameForPage() . '/cabinet') ?></td>
	<td><?php echo $review -> our ? 'Партнер': "Внешний"; ?></td>
	<td><?php echo CHtml::link('<span class="glyphicon glyphicon-pencil edit-review"></span>',Yii::app() -> baseUrl.'/editreview/'.$review -> id); ?>
	<?php if ($review -> checkDeleteAccess()) { ?><span class = "glyphicon glyphicon-remove delete-review" company="<?php echo $review -> company -> name ?>" goto = "<?php echo Yii::app() -> baseUrl . '/deletereview/' . $review -> id; ?>"></span><?php } ?>
	</td>
</tr>