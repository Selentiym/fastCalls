<form method="post" id="form">
<?php
	$data = array();
	//Если запрос пришел с _GET, то переделываем его в _POST
	if (!empty($_POST['userGroup'])) {
		$data = $_POST;
	} else {
		$data = $_GET;
		if ((empty($_GET['userGroup']))&&(strlen($_GET['selected']))) {
			$data['userGroup'] = explode(';',$_GET['selected']);
		}
	}
	if (!empty($data["userGroup"])) {
		foreach ($data["userGroup"] as $id){
			echo "<input type='hidden' name='userGroup[]' value='".$id."'/>";
		}
		switch ($data["action"]) {
			case "1" :
				$redirect = 'userSmsForm';
			break;
			default:
				if (!$data['return']) {
					$redirect = 'activeuserlist';
				} else {
					$redirect = $data['return'];
				}
			break;
		}
	} else {
		new CustomFlash('warning','User','noneSelected','Не выбрано ни одного пользователя.',true);
		if (!$data['return']) {
			$redirect = 'activeuserlist';
		} else {
			$redirect = $data['return'];
		}
	}
	if ($redirect == "_close"){
		Yii::app()->getClientScript()->registerScript('redirect', '

		window.close();
		', CClientScript::POS_READY);
	} else {
		Yii::app()->getClientScript()->registerScript('redirect', '
		$("#form").attr("action","' . Yii::app()->baseUrl . '/' . $redirect . '");
		$("#form").submit();
		', CClientScript::POS_READY);
	}
?>
</form>