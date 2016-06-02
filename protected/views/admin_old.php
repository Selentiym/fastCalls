<?php if (Yii::app() -> user -> checkAccess('viewOwnUserCabinet', array('user' => $user))) : ?>
<?php
	$this -> renderPartial('//navBar',array('user' => $user));
	echo CHtml::link('Добавить главного врача',Yii::app() -> baseUrl.'/create/maindoc');
	$criteria = new CDbCriteria();
	$criteria -> addNotInCondition('id_type', array(1));//1 заменить на что-то, что относится к админам
	$users = User::model() -> findAllByMainDocs($criteria);
?>
<?php Yii::app()->getClientScript()->registerScript('deleteScript','$(".delete-doctor").click(function(){
	if (!confirm("Вы уверены, что хотите удалить партнера "+$(this).attr("name")+"?")) {
		return false;
	} else {
		location.replace($(this).attr("goto"));
	}
});',CClientScript::POS_END); ?>
<table class="table table-bordered" id="admin_table">
<tr>
<th class="fio">ФИО</th>
<th class="tel">Телефон</th>
<th class="mail">email</th>
<th class="create">Начало работы</th>
<th class="del">del</th>
<?php
	$data = new Data;
	/*$user = User::model() -> findByAttributes(array('username' => 'doctor99'));
	print_r($user -> calls);*/
	$add = 604800;
	//print_r($data -> giveArrayKeys());
	foreach ($data -> giveArrayKeys() as $moment) {
		echo "<th>";
		echo date('d M',$moment);
		echo " - ";
		echo date('d M',$moment+$add);
		echo "</th>";
	}
?>
</tr>
<?php
	foreach($users as $user){
		$this -> renderPartial('//_showStat',array('user' => $user, 'data' => $data));
	}
?>
</table>
<?php
	$this -> renderPartial('//_form_file_upload');
	$this -> renderPartial('//_telephone_numbers_list');
	$this -> renderPartial('//_mentor_list');
?>
<div id="admin_lk_container">
	<div class="left_column">
	<?php
		$this -> renderPartial('//users/_info_all_users', array('users' => $users));
	?>
	</div>
	<div class="right_column">
		<?php //print_r(Data::model() -> giveData($user)); ?>
	</div>
</div>
<?php else : $this -> renderPartial('//accessDenied'); endif;?>