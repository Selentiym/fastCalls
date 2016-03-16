<form method="post" id="form">
<?php
	if (!empty($_POST["userGroup"])) {
		foreach ($_POST["userGroup"] as $id){
			echo "<input type='hidden' name='userGroup[]' value='".$id."'/>";
		}
		switch ($_POST["action"]) {
			case "1" :
				$redirect = 'userSmsForm';
			break;
			default:
				$redirect = 'activeuserlist';
			break;
		}
	} else {
		new CustomFlash('warning','User','noneSelected','Не выбрано ни одного пользователя.',true);
		$redirect = 'activeuserlist';
	}
	Yii::app() -> getClientScript() -> registerScript('redirect','
		$("#form").attr("action","'.Yii::app() -> baseUrl.'/'.$redirect.'");
		$("#form").submit();
	',CClientScript::POS_READY);
?>
</form>