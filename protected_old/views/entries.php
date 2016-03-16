<?php $this -> renderPartial('//navBar', array('user' => User::model() -> giveLogged(), 'button' => 'no')); ?>
<?php
$criteria = new CDbCriteria;
$criteria -> order = 'moment DESC';
$criteria -> limit = 300;
$entries = UserEntry::model() -> findAll($criteria);
if (is_array($entries)&&(!empty($entries))):
 ?>
<table class="table table-bordered" id="admin_table">
	<tr>
		<th>Дата</th>
		<th>login</th>
		<th>Партнер</th>
	</tr>
	<?php
	
		foreach($entries as $entry){
			$this -> renderPartial('//_single_entry',array('entry' => $entry));
		}
	?>
</table>
<?php
	else:
	echo "Пока что входов не зафиксировано.";
	endif;
?>