<?php $this -> renderPartial('//navBar', array('user' => User::model() -> giveLogged(), 'button' => 'no')); ?>
<?php
	Yii::app() -> getClientScript() -> registerScript("deleteGroup","
		$('#delete_group').click(function(){
			if (confirm('Вы уверены, что хотите удалить выбранные объекты?')) {
				//$('#group_action').attr('action','".Yii::app() -> baseUrl."/deleteGroup');
				var chosen = new Array();
				$('.groupCheckbox:checked').each(function(){
					chosen.push($(this).val());
				});
				$('#groupHidden').val(chosen.join(';'));
				$('#group_action').submit();
			}
		});
	",CClientScript::POS_READY);
	Yii::app() -> getClientScript() -> registerScript('checkboxes','
		$("#checkAll").click(function(){
			$("table input[type=\'checkbox\']").attr("checked","checked");
		});
		$("#unCheckAll").click(function(){
			$("table input[type=\'checkbox\']").removeAttr("checked");
		});
	',CClientScript::POS_READY);
	$pageSize = 10;
	$criteria = new CDbCriteria;
	$criteria -> addCondition ('id_user IS NULL');
	if (!$_GET["page"]) {
		$_GET["page"] = Yii::app()->session->get('errorPage');
	}
	$page = $_GET['page'] ? $_GET['page'] : 1;
	$command = Yii::app()->db->createCommand('SELECT COUNT(`id`) FROM {{call}} WHERE `id_user` IS NULL');
	$maximum = $command -> queryScalar();
	if (($page - 1) * $pageSize > $maximum) {
		$page = 1;
	}
	Yii::app()->session->add('errorPage', $page);
	$criteria -> limit = $pageSize;
	$criteria -> offset = ($page - 1) * $pageSize;
	$criteria -> order = 'date DESC';
	$calls = BaseCall::model() -> findAll($criteria);
	
	/*Yii::app() -> getClientScript() -> registerScript('clickScript',"
		$('.assign').click(function(){
			if ($('#'+$(this).attr('idlist')).val()) {
				location.href = '".Yii::app() -> baseUrl."/assignCall/' + $(this).attr('call') + '/' + $('#'+$(this).attr('idlist')).val();
			} else {
				alert('Выберите пользователя');
			}
		});
	", CClientScript::POS_END);*/
	CustomFlash::showFlashes();
	echo '<button id="checkAll">Выделить все</button>';
	echo '<button id="unCheckAll">Cнять выделение</button>';
	echo "<div>";
	echo "Всего звонков не привязано: ". $maximum;
	echo "</div>";
	if ($maximum > $pageSize) {
		echo "<div class='pages'>";
		
		for ($i = 1; $i < (float)$maximum/$pageSize + 1; $i++) {
			$this -> renderPartial('//_page', array('num' => $i, 'url' => Yii::app() -> baseUrl . '/errors', 'active' => $page));
		}
		echo "</div>";
	}
	if (is_array($calls)&&(!empty($calls))):
?>
<form name="groupAction" id="group_action" action="<?php echo Yii::app() -> baseUrl; ?>/deleteGroup">
<input type="hidden" id="groupHidden" name="group"/>
</form>
<table class="table table-stripped" style="margin-top: 10px; width:1700px">
    <tbody><tr>
        
        
        <th></th>
        <th>Статус</th>
        <th>Дата</th>
        <th class='mistake'>Текст ошибки</th>
        <th>i</th>
        <th>Вызовы Telfin</th>
        <th>Комментарий</th>
        <th>Исслед.</th>
        <th>Телефон<br/>клиента</th>
        <th>Отчет</th>
        <th>ФИО</th>
		<th>Клиника</th>
        <th>Удалить<br/>звонок</th>
        <th>Присвоить пользователю</th>
        <th>Изменить номер</th>
		
    </tr>
	<?php
	$criteria = new CDbCriteria;
	$criteria -> order = 'fio ASC';
	$criteria -> compare('id_type',UserType::model() -> getNumber('doctor'));
	$users = CHtml::listData(User::model() -> findAll($criteria), 'id', 'fio');
	foreach($calls as $call){
		$this -> renderPartial('//_error_call', array('call' => $call, 'users' => $users));
	}
	?>
	</tbody>
</table>
<input type="button" id="delete_group" value="Удалить"/>
</form>
<?php
	else:
	echo "Все звонки определены верно.";
	endif;
?>