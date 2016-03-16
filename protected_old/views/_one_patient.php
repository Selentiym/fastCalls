<tr>
	<td><?php echo $num; ?></td>
	<td><?php echo $patient -> giveName(); ?></td>
	<td><?php echo $patient -> tel; ?></td>
	<?php if ($show_owner): ?>
	<td><?php echo $patient -> doctor -> fio; ?></td>
	<?php endif; ?>
	<td><?php echo date('d.m.Y',strtotime($patient -> create_time)); ?></td>
	<td><?php echo $patient -> note; ?></td>
</tr>