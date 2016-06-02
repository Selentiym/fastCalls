<?php $this -> renderPartial('//navBar',array('user' => User::model() -> findByPk(Yii::app() -> user -> getId()))); ?>
<?php $users = User::model() -> findAllByMainDocs('',''); ?>
<?php
	Yii::app()->getClientScript()->registerScript('deleteScript','$(".delete-doctor").click(function(){
		if (!confirm("Вы уверены, что хотите удалить партнера "+$(this).attr("name")+"?")) {
			return false;
		} else {
			location.replace($(this).attr("goto"));
		}
	});',CClientScript::POS_END);
?>
<table class="table table-bordered" id="admin_table">
<tr>
<th class="fio">ФИО</th>
<th class="tel">Телефон</th>
<th class="mail">email</th>
<th class="create">Начало работы</th>
<th class="del">del</th>
<th>Партнерские телефоны</th>
<!--<th>Идентификаторы телефонов</th>-->
<th>Адреса</th>
<th>Направления</th>
<th>Логин</th>
<th>Условия работы</th>
<th>Оплата</th>
</tr>
<?php
	foreach ($users as $user) {
		$this -> renderPartial('//users/_full_info', array('user' => $user));
	}
?>
</table>