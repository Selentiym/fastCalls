<?php $user = $model; ?>
<?php
	if (!($user -> allowPatients)) {
		$this -> redirect(Yii::app() -> baseUrl);
	}

	?>
<?php if ((Yii::app() -> user -> checkAccess('viewOwnUserCabinet',array('user' => $user)))||((Yii::app() -> user -> checkAccess('viewUserCabinet')))||(Yii::app() -> user -> checkAccess('viewChildUserCabinet',array('user' => $user)))) : ?>
<?php
	$this -> renderPartial('//navBar', array('user' => $user,'button' => 'patients'));
	Yii::app() -> getClientScript() -> registerScript('clickScript',"
		$('#addNew').click(function(){
			location.href = '".Yii::app() -> baseUrl."/addPatient/".$user->id."';
		});
	",CClientScript::POS_END);
	$show = ($user -> id_type != UserType::model() -> getNumber('doctor'));
	$patients = $user -> givePatients();
?>
<?php if (!$show): ?>
<div class='row'>
<div style="margin:5px auto; width:80%"><input id="addNew" type="button" value='Добавить нового'/></div>
	
</div>
<?php endif; ?>
<div class='row'>
	<?php if (count($patients) > 0): ?>
	<table class="table table-bordered" id="patients_table">
		<thead>
			<tr>
				<th class="num">Номер</th>
				<th class="fio">ФИО</th>
				<th class="tel">Телефон</th>
				<?php if ($show) : ?>
				<th class="doc">Партнер</th>
				<?php endif; ?>
				<th class="create">Добавлен</th>
				<th class="note">Примечание</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$num = 0;
				foreach($patients as $patient){
					$num ++;
					$this -> renderPartial('//_one_patient',array('patient' => $patient, 'num' => $num,'show_owner' => $show));
				}
			?>
		</tbody>
	</table>
	<?php else: ?>
	<div style="margin:0 auto; text-align:center;">Пока что не зарегистрировано ни одного пациента.</div>
	<?php endif; ?>
	
</div>
<?php else: ?>
<?php $this -> renderPartial('//accessDenied'); ?>
<?php endif; ?>