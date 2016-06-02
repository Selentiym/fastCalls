<?php
	$this -> renderPartial('//navBar',array('user' => User::model() -> findByPk(Yii::app() -> user -> getId()),'button' => 'no'));
	//Получили всех главных партнеров
	$MDs = User::model() -> findAllByAttributes(array('id_type' => UserType::model() -> getNumber('mainDoc')));
	//Потом это будет масив всех телефонов, которые имеют юзеров.
	$phones = array();
?>
<table class="table table-bordered" id="admin_table">
	<tr class = "md_line">
	<td></td>
		<?php
		//Заводим счетчик линий
		$count = 0;
		//Двумерный массив, второе измерение которого - порядковый номер телефона, а первое - адрес. В ячейке лежит юзер.
		$rez = array();
		//Перебираем всех главных партнеров, получая от них их телефоны, на которые определено более одного юзера.
		foreach($MDs as $MD) {
			$md_phones = $MD -> phones;
			//Временный массив телефонов
			$temp = array();
			foreach($md_phones as $phone){
				//Если у номера есть более одного юзера, то сохраняем его и находим для него все адреса.
				if (count($phone -> regular_users) > 1) {
					$temp[] = $phone;
					//Перебираем всех юзеров, соответствующих данному номеру телефона. (их заведомо больше 1)
					foreach($phone -> regular_users as $user){
						//Перебираем все адреса пользователя.
						if (count($user -> address_array) > 0) {
							foreach($user -> address_array as $adr) {
								//echo $adr -> address;
								if (!$rez[$adr -> address][$count]) {
									//Сохраняем результат, то есть записываем пользователя в ячейку с адресом.
									$rez[$adr -> address][$count] = $user;
								} elseif (is_a($rez[$adr -> address][$count], 'User')) {
									//$rez[$adr -> address][$count] = 'Ошибка! Более одного пользователя. См. примечание.';
									new CustomFlash('warning','User','Collision:'.$adr -> address.$count,'Пользователи '.$user -> fio.' и '.$rez[$adr -> address][$count] -> fio.' попадают в одну ячейку! Адрес: '.$adr -> address.'; столбец: '.($count + 1),true);
									$rez[$adr -> address][$count] = 'Ошибка! Более одного пользователя. См. примечание.';
									
								}
							}
						} else {
							new CustomFlash('warning', 'User','User:'.$user -> username.'Phone:'.$phone -> i,'Пользователь '.$user -> fio. ' участвует в шахматке, но у него не указан адрес.',true);
						}
					}
					$count ++;
				}
			}
			$phones = array_merge($phones, $temp);
			//Показываем главного только если у него есть хоть один юзер в шахматке.
			if (count($temp) > 0) {
				echo "<td colspan = '".count($temp)."'>";
				echo CHtml::link($MD -> fio, $this -> createUrl('site/cabinet', array('arg' => $MD -> username)));
				echo "</td>";
			}
		}
		//echo $count;
		?>
	</tr>
	<tr>
		<td>Адрес/Компания</td>
		<?php
		foreach ($phones as $phone) {
			echo "<td class = 'user_cell'>";
			echo $phone -> number.":".$phone -> i;
			echo "</td>";
		}
		?>
	</tr>
	<?php
		//print_r($rez);
		foreach($rez as $adr_name => $users) {
			echo "<tr class = 'chess_address_line'>";
			echo "<td>";
			echo $adr_name;
			echo "</td>";
			for ($i = 0; $i < $count; $i++) {
				echo "<td>";
				//var_dump($users[$i]);
				if ($users[$i]) {
					if (is_a($users[$i],'User')) {
						$this -> renderPartial('//chess/_user', array('user' => $users[$i]));
					} else {
						echo $users[$i];
					}
				}
				echo "</td>";
			}
			echo "</tr>";
		}
	?>
</table>
<?php
CustomFlash::showFlashes();
?>