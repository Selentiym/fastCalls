<form method="post" id="form">
<?php
	$data = array();
	//Если запрос пришел с _GET, то переделываем его в _POST
	if (!empty($_POST['userGroup'])||($_POST['selected'])) {
		$data = $_POST;
	} else {
		$data = $_GET;
	}
	if ((empty($data['userGroup']))&&(strlen($data['selected']))) {
		$data['userGroup'] = explode(';',$data['selected']);
	}
	if (!empty($data["userGroup"])) {
		foreach ($data["userGroup"] as $id){
			echo "<input type='hidden' name='userGroup[]' value='".$id."'/>";
		}
		echo "";
		function displayVar($var, $names = 'data'){
			$rez = '';
			if (is_array($var)) {
				foreach($var as $key => $val) {
					$rez .= displayVar($val, $names.'['.$key.']');
				}
			} else {
				$rez .= "<input type='hidden' value='{$var}' name='{$names}'/>";
			}
			return $rez;
		}
		echo displayVar($data['data']);
		switch ($data["action"]) {
			case "1" :
				$redirect = 'userSmsForm';
			break;
			case "2" :
				$redirect = 'userPropertyForm';
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