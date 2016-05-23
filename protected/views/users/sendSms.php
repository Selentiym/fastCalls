<?php $this -> renderPartial('//navBar', array('user' => User::model() -> giveLogged(), 'button' => 'no'));
Yii::app() -> getClientScript() -> registerScript('applyText','
	$("#texts button").click(function(event){
		event.preventDefault();
		var subs = true;
		if ($("#smsText").attr("data-changed") == 1) {
			subs = confirm("Применить шаблон? Все изменения будут потеряны.");
		}
		if (subs) {
			$("#smsText").val($(this).attr("data-text"));
			$("#smsText").attr("data-changed","0");
		}
	});
	$("#smsText").change(function(){
		$(this).attr("data-changed","1");
	});
',CClientScript::POS_READY);
CustomFlash::ShowFlashes(); 
//print_r($_POST);
?>
<form method="post" action="<?php echo Yii::app() -> baseUrl; ?>/userSendSms" style="padding-left:20px">
	<div class="row">
	<?php
		User::model() -> showUserList($_POST['userGroup']);
	?>
	</div>
	<div class="row">
		<div class="col-sm-4">
		<textarea data-changed="0" id="smsText" name="smsText" placeholder="Введите текст сообщения. <ФИО> заменяется на ФИО в именительном падеже." style="width:400px;height:250px"></textarea>
		<div><input type="submit" value="Отправить"/></div>
		</div>
		<div id="texts" class="col-sm-4">
			<?php
				$patterns = SmsPattern::model() -> findAll();
				foreach ($patterns as $pt) {
					echo "<div><button data-text='{$pt -> text}'>{$pt -> value}</button></div>";
				}
			?>
			<!--<div><button data-text="text1">Поздравление с др</button></div>
			<div><button data-text="text2">Текст2</button></div>
			<div><button data-text="text3">Текст3</button></div>-->
		</div>
	</div>
</form>