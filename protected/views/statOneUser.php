<div class="panel panel-default">
	<div class="panel-heading">Статистика записей по месяцам</div>
	<div class="panel-body">
		<h3>Статистика звонков</h3>
		<button onClick="location.href='<?php echo Yii::app() -> baseUrl."/payStat/user/{$user -> username}/".(time() - 24*3600*50)."/".time();?>';">Статистика за прошедший месяц</button>
		<div>Номер телефона: <?php foreach ($user -> phones as $phone) {echo $phone -> showMe()."<br/>";} ?></div>
		<table class="table">
			<tbody>
				<tr>
					<th>Месяц</th>
					<th>Звонков</th>
					<th>Нецелевых</th>
					<th>Записаны</th>
					<th>Подтверждены</th>
					<th>Отменили</th>
					<th>Не пришли</th>
				</tr>

				<?php
					$monthedCalls = Data::model() -> giveMonthedCalls($user);
					foreach($monthedCalls as $month => $calls_array) {
						$this -> renderPartial('//_month_calls_shortcut', array(
							'month' => $month,
							'calls' => $calls_array,
							'username' => (Yii::app() -> user -> getId() == $user -> id) ? '' : '/'.$user -> username 
						));
					}
				?>
			</tbody>
		</table>
	</div>
</div>