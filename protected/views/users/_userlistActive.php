<?php
	$this -> renderPartial('//navBar',array('user' => User::model() -> findByPk(Yii::app() -> user -> getId())));
	$users = User::model() -> findAllByMainDocs('','');
	Yii::app() -> getClientScript() -> registerScriptFile(Yii::app() -> baseUrl.'/js/onShiftPressed.js');
	Yii::app() -> getClientScript() -> registerScript('checkScript','
		$("#admin_table input:checkbox").setCheckboxesShift(); 
	',CClientScript::POS_READY);
	Yii::app() -> getClientScript() -> registerScript('buttonsScript','
		$("#buttons button").click(function(){
			$("#userGroupForm input[name=\'action\']").val($(this).attr("data-action"));
			$("#userGroupForm").submit();
		});
	',CClientScript::POS_READY);
?>
<div id="buttons" class="well">
	<button data-action="<?php echo User::SMS_SEND; ?>" class="btn btn-xs btn-success">Отправить смс</button>
</div>
<?php echo CustomFlash::ShowFlashes(); ?>
<form method="post" id="userGroupForm" name="userGroupForm" action="<?php echo Yii::app() -> baseUrl; ?>/userCollection">
<input type="hidden" name="action" value="0"/>
<table class="table table-bordered" id="admin_table">
<tr>
<th></th>
<th class="fio">ФИО</th>
<th class="tel">Телефон</th>
<th class="mail">email</th>
<th class="create">Начало работы</th>
<th>Партнерские телефоны</th>
<th>Адреса</th>
<th>Направления</th>
<th>Логин</th>
<th>Условия работы</th>
<th>Оплата</th>
</tr>
<?php
	$count = 0;
	foreach ($users as $user) {
		$this -> renderPartial('//users/_userActive', array('user' => $user));
		$count ++;
		//if ($count > 10) { break; }
	}
?>
</table>
</form>