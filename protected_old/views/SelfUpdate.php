<?php //if (Yii::app() -> user -> checkAccess('UpdateOwnUser',array('user' => $model))) : ?>
<?php if ((Yii::app() -> user -> checkAccess('viewOwnUserCabinet',array('user' => $model)))||((Yii::app() -> user -> checkAccess('viewUserCabinet')))||(Yii::app() -> user -> checkAccess('viewChildUserCabinet',array('user' => $model)))) : ?>
<?php $this -> renderPartial('//navBar_pay',array('user' => $model)); ?>
<div class="row">
	<div class="col-xs-6">
		<div class="panel panel-default">
			<div class="panel-heading">Укажите желаемый способ оплаты</div>

			<div class="panel-body">
				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'user-form',
					// Please note: When you enable ajax validation, make sure the corresponding
					// controller action is handling ajax validation correctly.
					// There is a call to performAjaxValidation() commented in generated controller code.
					// See class documentation of CActiveForm for details on this.
					'enableAjaxValidation'=>false
				));?>

					<div class="well">
						<label>Вариант №1. Реквизиты счета</label>

						<div class="form-group">
							<label for="bik">БИК</label>
							<?php echo $form->textField($model, 'bik',array('size'=>60,'maxlength'=>255)); ?>
							<!--<input type="text" class="form-control" id="bik" name="bik" title="" value="" data-original-title="БИК - Банковский идентификационный код">-->
						</div>

						<div class="form-group">
							<label for="account">Номер счета в банке</label>
							<?php echo $form->textField($model, 'bank_account',array('size'=>60,'maxlength'=>255)); ?>
							<!--<input type="text" class="form-control" id="account" name="account" title="" maxlength="20" minlength="20" value="" data-original-title="20ти значный номер вашего счета (не перепутайте с Корр. счёт)" aria-describedby="tooltip209673"><div class="tooltip fade top in" role="tooltip" id="tooltip209673" style="top: 137px; left: 232.25px; display: block;"><div class="tooltip-arrow" style="left: 50%;"></div><div class="tooltip-inner">20ти значный номер вашего счета (не перепутайте с Корр. счёт)</div></div>-->
						</div>

					</div>

					<div class="well">
						<label>Вариант №2. На карту</label>

						<div class="form-group">
							<label for="cardNumber">Номер карты</label>
							<?php echo $form->textField($model, 'card_number',array('size'=>60,'maxlength'=>255)); ?>
							<!--<input type="text" class="form-control" id="cardNumber" name="cardNumber" title="" maxlength="16" minlength="16" value="" data-original-title="16ти значный номер карты">-->
						</div>
					</div>

					<div class="well">
						<label>Вариант №3. Webmoney</label>

						<div class="form-group">
							<label for="webMoney">Номер WebMoney кошелька</label>
							<?php echo $form->textField($model, 'webmoney',array('size'=>60,'maxlength'=>255)); ?>
							<!--<input type="text" class="form-control" id="webMoney" name="webMoney" title="" value="" data-original-title="Пример: R427985080111">-->
						</div>

					</div>

					<button type="submit" class="btn btn-default">Сохранить</button>
				<?php $this -> endWidget(); ?>
			</div>
		</div>

	</div>

	<div class="col-xs-6">
		<div class="well">
			<h4>Когда происходит оплата?</h4>

			<p>Оплата за пациентов происходит 15-17 числа каждого месяца, следующего за отчетным.</p>

			<p>Например: оплата за июль 2015 будет происходить 15-17 августа(после согласования пациентов с клиниками).</p>
		</div>
		<!--<h4>Переводы</h4>
		<table class="table">
			<tbody><tr>
				
				<th>Сумма</th>
				<th>Описание</th>
			</tr>
			
		</tbody></table>-->
	</div>
</div>
<?php else : $this -> renderPartial('//accessDenied'); endif;?>