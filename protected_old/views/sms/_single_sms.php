<tr>
<?php
	$user = User::model() -> findByPk($sms -> id_user);
	$userString = $user ? $user -> showOneself() : '';
	$descrs = Sms::giveDescriptions();
	
	$descr = $descrs[$sms -> status];
	echo "
	<td></td>
	<td>{$userString}</td>
	<td>{$sms -> number}</td>
	<td>{$sms -> text}</td>
	<td>{$sms -> changed}</td>
	<td>{$sms -> send}</td>
	<td title='{$descr}'>{$sms -> status}</td>
	";
?>
</tr>