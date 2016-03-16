	<?php 
		if ((Yii::app() -> user -> isGuest)||(!Setting::model() -> find() -> showClinicStat)) {
			$this -> redirect(Yii::app() -> baseUrl.'/cabinet');
		}
		$this -> renderPartial('//navBar',array('user' => User::model() -> findByPk(Yii::app() -> user -> id),'button' => 'no'));
	?>
	
	<?php
		$clinics = Review::model() -> giveStat();
	?>
	<h3>Рейтинг клиник</h3>
	<table class="table table-stripped" style="margin-top: 10px">
		<tbody>
			<tr>
				<th>Название клиники</th>
				<th>Адрес клиники</th>
				<th>Рейтинг клиники</th>
				<th>Всего отзывов</th>
				<th>Примечание</th>
			</tr>
			<?php
			usort($clinics, function ($c1,$c2){
				//return strncmp($c1 -> name, $c2 -> name, 3);
				//return($c1 -> sum/$c1 -> countReviews - $c2 -> sum/$c2 -> countReviews);
				return -(($c1 -> sum/$c1 -> countReviews - $c2 -> sum/$c2 -> countReviews > 0) ? 1 : -1);
			});
			foreach($clinics as $clinic){
				$this -> renderPartial('//review/clinic_shortcut',array('clinic' => $clinic));
			}
			?>
		</tbody>
	</table>