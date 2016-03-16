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
	$criteria -> addCondition ('id_call IS NULL');
	if (!$_GET["page"]) {
		$_GET["page"] = Yii::app()->session->get('errorPage');
	}
	$page = $_GET['page'] ? $_GET['page'] : 1;
	$command = Yii::app()->db->createCommand('SELECT COUNT(`ApiId`) FROM {{telfin_call}} WHERE `id_call` IS NULL');
	$maximum = $command -> queryScalar();
	if (($page - 1) * $pageSize > $maximum) {
		$page = 1;
	}
	Yii::app()->session->add('errorPage', $page);
	$criteria -> limit = $pageSize;
	$criteria -> offset = ($page - 1) * $pageSize;
	$criteria -> order = 'time DESC';
	$calls = TelfinCall::model() -> findAll($criteria);
	
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
			$this -> renderPartial('//_page', array('num' => $i, 'url' => Yii::app() -> baseUrl . '/errors/telfin', 'active' => $page));
		}
		echo "</div>";
	}
	if (is_array($calls)&&(!empty($calls))):
?>
<form name="groupAction" id="group_action" action="<?php echo Yii::app() -> baseUrl; ?>/deleteTelfinGroup">
<input type="hidden" id="groupHidden" name="group"/>
</form>
<table class="table table-stripped" style="margin-top: 10px">
    <tbody>
	<tr>
        <th></th>
        <th>Дата</th>
        <th class='mistake'>Текст ошибки</th>
        <th>Телефон звонившего</th>
        <th>Партнерский телефон</th>
        <th>Искать заново</th>
		<th>Возможные владельцы</th>
        <th>Удалить звонок</th>
    </tr>
	<?php
	/*$criteria = new CDbCriteria;
	$criteria -> order = 'fio ASC';
	$criteria -> compare('id_type',UserType::model() -> getNumber('doctor'));
	$users = CHtml::listData(User::model() -> findAll($criteria), 'id', 'fio');
	*/
	foreach($calls as $call){
		$this -> renderPartial('//_error_telfin_call', array('tCall' => $call));
	}
	?>
	</tbody>
</table>
<input type="button" id="delete_group" value="Удалить"/>
<?php
	else:
	echo "Все звонки определены верно.";
	endif;
?>