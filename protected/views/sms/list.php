<?php
	$this -> renderPartial('//navBar', array('user' => User::model() -> giveLogged(), 'button' => 'no'));
	$criteria = new CDbCriteria;
	$criteria -> addNotInCondition('status',array(103));
	$criteria -> order = ('changed ASC');
	$criteria -> with = 'user';
	//Смски, которые еще не были доставлены.
	$smsE = Sms::model() -> findAll($criteria);
	$criteria = new CDbCriteria;
	$criteria -> addInCondition('status',array(103));
	$criteria -> order = ('changed DESC');
	$criteria -> with = 'user';
	
	
	$pageSize = 100;
	if (!$_GET["page"]) {
		$_GET["page"] = Yii::app()->session->get('errorPage');
	}
	$page = $_GET['page'] ? $_GET['page'] : 1;
	$command = Yii::app()->db->createCommand("SELECT COUNT(`id`) FROM {{sms}} WHERE `status` = '103'");
	$maximum = $command -> queryScalar();
	if (($page - 1) * $pageSize > $maximum) {
		$page = 1;
	}
	Yii::app()->session->add('smsPage', $page);
	$criteria -> limit = $pageSize;
	$criteria -> offset = ($page - 1) * $pageSize;
	//Доставленные смски.
	$smsD = Sms::model() -> findAll($criteria);
	if ($page == 1) {
		$smss = array_merge($smsE, $smsD);
	} else {
		$smss = $smsD;
	}
	if (!empty($smss)) {
?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th></th>
			<th>Пользователь</th>
			<th>Номер</th>
			<th>Текст</th>
			<th>Запись создана</th>
			<th>Отправить в</th>
			<th>Статус</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($smss as $sms) {
				$this -> renderPartial('//sms/_single_sms',array('sms' => $sms));
			}
		?>
	</tbody>
</table>
<?php
		echo "<div>";
		echo "Всего смс доставлено: ". $maximum;
		echo "</div>";
		if ($maximum > $pageSize) {
			echo "<div class='pages'>";
			
			for ($i = 1; $i < (float)$maximum/$pageSize + 1; $i++) {
				$this -> renderPartial('//_page', array('num' => $i, 'url' => Yii::app() -> baseUrl . '/smsList', 'active' => $page));
			}
			echo "</div>";
		}
	} else {
		echo "Нет смс в базе.";
	}
?>