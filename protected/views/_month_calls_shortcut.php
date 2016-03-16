<tr>
	<?php
		$year = 2015;
		$year += floor($month / 12);
		$from = strtotime('1.'.$month % 12 .'.'.$year);
		$to = Data::model() -> DateAdd('m',1,$from) - 1;
	?>
	<!--<td><?php //echo CHtml::link(ucfirst($month), $this -> createUrl('site/stat',array('from'=>strtotime('1.'.($month+0).'.'.$year),'to'=>strtotime('1.'.($month+1).'.'.$year)-1))); //Переелать!!!! ?></td>-->
	<td><?php echo CHtml::link(ucfirst(Data::model() -> giveMonthName($month)), Yii::app() -> baseUrl.'/stat'.$username.'/'.$from.'/'.$to); //Переделать!!!! ?></td>
	<?php
		$display = Data::model() -> CountArray($calls);
	?>
	<td><?php echo count($calls); ?></td>
	<td><?php echo $display['side']; ?></td>
	<td><?php echo $display['assigned']; ?></td>
	<td><?php echo $display['verifyed']; ?></td>
	<td><?php echo $display['cancelled']; ?></td>
	<td><?php echo $display['missed']; ?></td>
</tr>