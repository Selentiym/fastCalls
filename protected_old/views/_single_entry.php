<tr>
<td><?php echo $entry -> moment; ?></td>
<?php $user = User::model() -> findByPk($entry -> id_user); ?>
<td><?php echo $user -> username; ?></td>
<td><?php echo CHtml::link($user -> fio, $this -> createUrl('site/cabinet', array('arg' => $user -> username))); ?> </td>
</tr>