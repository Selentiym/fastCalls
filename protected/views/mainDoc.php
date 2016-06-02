<?php if ((Yii::app() -> user -> checkAccess('viewOwnUserCabinet', array('user' => $user))) || (Yii::app() -> user -> checkAccess('viewChildUserCabinet', array('user' => $user))) || (Yii::app() -> user -> checkAccess('viewMDCabinet'))) : ?>
<?php
	$this -> renderPartial('//navBar',array('user' => $user));
	if($user -> id_type == UserType::model() -> getNumber('maindoc')) {
		$user -> prepareCalls();
	}
	$children = $user -> getChildren();
	$addresses = $user -> giveChildrenAddresses();
	//print_r(CHtml::giveAttributeArray(User::model() -> giveByCoordinatesObjects(UserAddress::model() -> findAllByAttributes(array('address' => 'Городская поликлиника №114')), UserPhone::model() -> findAllByAttributes(array('number' => '78124071024'))),'fio'));
	/*$criteria = new CDbCriteria();
	$criteria -> addInCondition('address_array.id', array(61));
	$criteria -> addInCondition('phones.id', array(18));
	//$criteria -> compare('id_type', 3);
	$criteria -> with = array('address_array','phones');
	print_r(CHtml::giveAttributeArray(User::model() -> findAll($criteria), 'fio'));//*/
?>
<div class="row">
	
	<div class="col-sm-4">
	<?php $this -> renderPartial('//_conditions', array('user' => $user)); ?>	
	<?php $this -> renderPartial('//mentor', array('mentor' => $user -> mentor)); ?>
	<?php //$this -> renderPartial('//_meassage'); ?>
	<?php //$this -> renderPartial('//_statImage'); ?>
	
		
	</div>
	<div class="col-sm-8">
		<?php
			$this -> renderPartial('//statOneUser', array('user' => $user));
		?>
		<div class="panel panel-default">
			<div class="panel-heading">Моя партнерская сеть</div>
			<div class="panel-body">
				<?php if (($user -> jMin) && ($user -> jMax)) : ?>
				<h4>Выданные мне направления</h4>
				№<?php echo $user -> jMin; ?> ... №<?php echo $user -> jMax; ?><br/>
				<?php endif; ?>
				<?php if (($user -> phones)&&(!empty($user -> phones)))  : ?>
				<h4>Выданные мне телефоны</h4>
				<?php
					foreach ($user -> phones as $phone) {
						echo $phone -> showMe()."<br/>";
					}
				?>
				<?php endif; ?>
				<div class="row">
					<?php
						if ($addresses) {
							foreach ($addresses as $address){
								//echo "<div class = 'clear'>{$address -> address}:</div>";
								echo "<div class='row' style='margin:0'><div style ='margin: 5px 30px;'>{$address -> address}:</div><br/>";
								$users = $address -> users;
								usort($users, function($u1, $u2){
									return strncmp($u1 -> fio, $u2 -> fio, 3);
								});
								foreach($users as $child) {
									if ($child -> id_parent == $user -> id) {
										$child -> parent = $user;
										$this -> renderPartial('//_doctor_shortcut',array('user' => $child));
									}
								}
								echo "</div>";
							}
						}
						//Выводим тех, кто без адреса
						echo "<div class='row' style='margin:0'><div style ='margin: 5px 30px;'>Адрес не указан:</div><br/>";
						
						$users = array_filter($children , function ($user) { return !(count($user -> address_array) > 0); });
						usort($users, function($u1, $u2){
							return strncmp($u1 -> fio, $u2 -> fio, 3);
						});
						foreach($users as $child) {
							if ($child -> id_parent == $user -> id) {
								$child -> parent = $user;
								$this -> renderPartial('//_doctor_shortcut',array('user' => $child));
							}
						}
						echo "</div>";
						//echo "</ul>";
						//Просто вывод всех подчиненных
						/*foreach ($children as $child) {
							$child -> setParent();
							$this -> renderPartial('//_doctor_shortcut',array('user' => $child));
						}*/
					?>
				</div>
				<?php echo CHtml::link('
				<button class="btn btn-xs btn-success" id="create-doctor">
					<span class="glyphicon glyphicon-plus"></span>
					Добавить партнера
				</button>', Yii::app() -> baseUrl.$user -> giveUserNameForPage().'/create/doctor', array("target" => "_blank")); ?>
				<div class="well" style="margin-top: 30px">
					<h5>Не получается подключить партнера?</h5>

					<p>
						Администратор сайта поможет Вам.
						Для подключения партнера, отправьте заявку на почту partnership@o-mri.ru.
					</p>

					<p>
						В заявке нужно указать:
					</p>
						<ul>
							<li>ФИО врача полностью</li>
							<li>Название мед. учреждения, где работает врач + Телефон на бланках, которые будут выданы врачу.</li>
							<li>ИЛИ: номера бланков, которые будут выданы врачу</li>
						</ul>
					<p></p>
					<p>
						В течение дня в вашем личном кабинете появится информация по данному врачу.
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php else : $this -> renderPartial('//accessDenied'); endif;?>