<?php
	CustomFlash::showFlashes();
?>
	
<!--<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front dialog ui-dialog-buttons ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-describedby="doctor-dialog" aria-labelledby="ui-id-1" style="position: absolute; height: auto; width: 550px; top: 2566px; left: 353px; display: block; z-index: 101;"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-1" class="ui-dialog-title">Форма добавление/редактирования партнера</span><button type="button" class="ui-dialog-titlebar-close"></button></div><div id="doctor-dialog" class="ui-dialog-content ui-widget-content" style="width: auto; min-height: 25px; max-height: none; height: auto;">-->
<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'users-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    ));?>
	<?php 
		//Yii::app()->getClientScript()->registerScript('check', 'alert($("select").select2());',CClientScript::POS_END);
	?>
        <fieldset>
			<?php $model -> input_type = 'mainForm'; ?>
            <?php echo $form->hiddenField($model, 'input_type'); ?>

            <div class="well">
                <div class="form-group">
                    <label for="name">ФИО (полностью)</label>
                    <?php echo $form->textField($model, 'fio',array('size'=>60,'maxlength'=>255,'placeholder'=>'ФИО')); ?>
                </div>
				
                <div class="form-group">
                    <label for="name">Имеет право создавать пациентов</label>
                    <?php echo $form->checkBox($model, 'allowPatients',array()); ?>
                </div>

                <div class="form-group">
                    <label for="speciality">Специализация</label>
					<?php $input = array($model -> id_speciality); ?>
					<?php CHtml::activeDropDownListChosen2(UserSpeciality::model(), 'id',CHtml::listData(UserSpeciality::model() -> findAll(),'id','name'), array('class' => 'select2 createUser','name' => 'User[speciality]', 'multiple' => 'multiple'), $input, '{
						tokenSeparators: [";"],
						tags:true,
						placeholder:"Выберите специализацию",
						maximumSelectionLength: 1
					}'); ?>
                    <!--<input type="text" class="form-control" id="speciality" name="speciality" placeholder="" title="" data-original-title="Специализация врача: невролог, хирург, ортопед и тд">-->
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <?php
								$parent = $model -> setParent();
								//var_dump(count($model -> parent -> phones));
								//$parent = User::model() -> findByPk($model -> id_parent);
								if ($parent -> phones) :
							?>
							<label for="phone">Партнерский телефон</label>
							<?php $input = CHtml::giveAttributeArray($model -> phones, 'id'); ?>
							<?php CHtml::activeDropDownListChosen2(UserPhone::model(), 'id',CHtml::listData($parent -> phones,'id',
								function($phone){
									if ($phone -> ivr) {
										$ivr = ':'.$phone -> ivr;
									}
									return $phone -> number.$ivr;
								}), 
								array('class' => 'select2 createUser','name' => 'User[phones_input][]','multiple' => 'multiple'), $input, '{
								placeholder: "Выберите телефон",
								maximumSelectionLength: 1
							}'); ?>
							<?php endif; ?>
							
							<?php //переделать. случай создания/редактирования главврача
								if ($model -> id_type == 2):
							?>
							<label for="phone">Партнерский телефон</label>
							<?php $input = CHtml::giveAttributeArray($model -> phones, 'id'); ?>
							<?php CHtml::activeDropDownListChosen2(UserPhone::model(), 'id',CHtml::listData(UserPhone::model() -> findAll(),'id',
								function($phone){
									if ($phone -> ivr) {
										$ivr = ':'.$phone -> ivr;
									}
									return $phone -> number.$ivr;
								}),  array('class' => 'select2 createUser','name' => 'User[phones_input][]','multiple' => 'multiple'), $input, '{
								placeholder: "Выберите телефон"
							}'); ?>
							<?php endif; ?>
                            <!--<input type="text" class="form-control" id="phone" name="phone" placeholder="911 1234567" title="" data-original-title="" readonly="readonly">-->
                        </div>
						<div class="form-group">
                            <label for="personal_phone">Личный телефон</label>
                            <?php echo CHtml::activeTextField($model, 'tel', array('class' => 'form-control')); ?>
							<!--<input type="email" class="form-control" id="email" name="email" placeholder="" title="" data-original-title="">-->
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <?php echo CHtml::activeEmailField($model, 'email', array('class' => 'form-control')); ?>
							<!--<input type="email" class="form-control" id="email" name="email" placeholder="" title="" data-original-title="">-->
                        </div>
                    </div>
                </div>
            </div>
			<div class="well">
				<?php if (Yii::app() -> user -> checkAccess('admin')) : ?>
				<div class="form-group">
					<label for="conditions">Условия работы</label>
					<?php echo CHtml::activeTextField($model, 'conditions', array('class' => 'form-control')); ?>
					<label for="conditions">Условия работы(для подчиненных)</label>
					<?php echo CHtml::activeTextField($model, 'conditions_add', array('class' => 'form-control')); ?>
					<!--<input type="email" class="form-control" id="email" name="email" placeholder="" title="" data-original-title="">-->
				</div>
				<?php endif; ?>
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

				<div class="form-group">
					<label for="cardNumber">Номер карты</label>
					<?php echo $form->textField($model, 'card_number',array('size'=>60,'maxlength'=>255)); ?>
					<!--<input type="text" class="form-control" id="cardNumber" name="cardNumber" title="" maxlength="16" minlength="16" value="" data-original-title="16ти значный номер карты">-->
				</div>

				<div class="form-group">
					<label for="webMoney">Номер WebMoney кошелька</label>
					<?php echo $form->textField($model, 'webmoney',array('size'=>60,'maxlength'=>255)); ?>
					<!--<input type="text" class="form-control" id="webMoney" name="webMoney" title="" value="" data-original-title="Пример: R427985080111">-->
				</div>
			</div>
			<?php $settings = ''; ?>
            <label>Выберете адрес/клинику <?php if (Setting::model() -> find() -> allowMDCreateAddresses) : 
					$settings = '{
						tokenSeparators: [";"],
						tags:true
					}';
					?> или добавьте свою (разделитель - ;) 
					<?php endif; ?>
			</label>

            <div class="well">
				<div class="form-group">
					<?php $input = CHtml::giveAttributeArray($model -> address_array, 'id'); ?>
					<?php CHtml::activeDropDownListChosen2(UserAddress::model(), 'id',CHtml::listData(UserAddress::model() -> findAll(),'id','address'), array('class' => 'select2 createUser','multiple' => 'multiple','name' => 'User[addresses][]'), $input, $settings); ?>
				</div>
            </div>
                <div class="well">
                    <div class="form-group">
                        <label>Выданные Информационные бланки</label>

                        <div class="row">
                            <div class="col-xs-1">
                                c
                            </div>

                            <div class="col-xs-5">
								<?php echo CHtml::activeNumberField($model, 'jMin',array('size'=>60,'maxlength'=>255,'min' => $parent -> jMin ? $parent -> jMin : 1 , 'max' => $parent -> jMax ? $parent -> jMax : '', 'class' => 'form-control', 'placeholder' => $parent -> jMin )); ?>
                                <!--<input type="number" class="form-control" id="interval.startIndex" name="interval.startIndex" placeholder="8000" min="8000" max="9000" title="" data-original-title="Номер первого Информационного бланк выданного врачу">-->
                            </div>

                            <div class="col-xs-1">
                                по
                            </div>

                            <div class="col-xs-5">
								<?php echo CHtml::activeNumberField($model, 'jMax',array('size'=>60,'maxlength'=>255,'min' => $parent -> jMin ? $parent -> jMin : 1, 'max' => $parent -> jMax ? $parent -> jMax : '', 'class' => 'form-control', 'placeholder' => $parent -> jMax )); ?>
                                <!--<input type="number" class="form-control" id="interval.endIndex" name="interval.endIndex" placeholder="9000" min="8000" max="9000" title="" data-original-title="Номер последнего Информационного бланк выданного врачу">-->
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label>А также</label>

                        <div class="row">
                            <div class="col-xs-1">
                                c
                            </div>

                            <div class="col-xs-5">
								<?php echo CHtml::activeNumberField($model, 'jMin_add',array('size'=>60,'maxlength'=>255,'min' => $parent -> jMin_add ? $parent -> jMin_add : 1 , 'max' => $parent -> jMax_add ? $parent -> jMax_add : '', 'class' => 'form-control', 'placeholder' => $parent -> jMin_add )); ?>
                                <!--<input type="number" class="form-control" id="interval.startIndex" name="interval.startIndex" placeholder="8000" min="8000" max="9000" title="" data-original-title="Номер первого Информационного бланк выданного врачу">-->
                            </div>

                            <div class="col-xs-1">
                                по
                            </div>

                            <div class="col-xs-5">
								<?php echo CHtml::activeNumberField($model, 'jMax_add',array('size'=>60,'maxlength'=>255,'min' => $parent -> jMin_add ? $parent -> jMin_add : 1, 'max' => $parent -> jMax_add ? $parent -> jMax_add : '', 'class' => 'form-control', 'placeholder' => $parent -> jMax_add )); ?>
                                <!--<input type="number" class="form-control" id="interval.endIndex" name="interval.endIndex" placeholder="9000" min="8000" max="9000" title="" data-original-title="Номер последнего Информационного бланк выданного врачу">-->
                            </div>
                        </div>

                    </div>
				</div>
				<div class="well">
                    <div class="form-group">
                        <!--<label>Имя пользователя и пароль</label>-->

                        <div class="row">
							
                            <div class="col-xs-5">
							<label>Имя пользователя</label>
								<?php echo CHtml::activeTextField($model, 'username',array('size'=>60,'maxlength'=>255,'class' => 'form-control' )); ?>
                                <!--<input type="number" class="form-control" id="interval.startIndex" name="interval.startIndex" placeholder="8000" min="8000" max="9000" title="" data-original-title="Номер первого Информационного бланк выданного врачу">-->
                            </div>
                        </div>
						<div class="row">
							
                            <div class="col-xs-5">
							<label>Пароль</label>
								<?php echo CHtml::passwordField('User[password_change]', '',array('size'=>60,'maxlength'=>255,'class' => 'form-control' )); ?>
                                <!--<input type="number" class="form-control" id="interval.startIndex" name="interval.startIndex" placeholder="8000" min="8000" max="9000" title="" data-original-title="Номер первого Информационного бланк выданного врачу">-->
                            </div>
						</div>
						<div class="row">
							
							<div class="col-xs-5">
							<label>Еще раз</label>
								<?php echo CHtml::passwordField('User[password_change_second]', '',array('size'=>60,'maxlength'=>255,'class' => 'form-control' )); ?>
                                <!--<input type="number" class="form-control" id="interval.startIndex" name="interval.startIndex" placeholder="8000" min="8000" max="9000" title="" data-original-title="Номер первого Информационного бланк выданного врачу">-->
                            </div>
						</div>

                    </div>
                </div>
				
				<label>Свойства</label>
				<div class="well">
                    <div class="form-group">
						
						<div class="col-xs-5">
							<?php
							$inp = $_POST['User']['input_type']=='mainForm' ? $_POST['User']['input_options'] : CHtml::giveAttributeArray($model -> options, 'id');
							if (empty($inp)) {
								$inp = array();
							}
							CHtml::activeDropDownListChosen2(UserOption::model(), 'id',CHtml::listData(UserOption::model() -> findAll(),'id','name'), array('class' => 'select2 createUser','name' => 'User[input_options]', 'multiple' => 'multiple'), $inp, '{
								tokenSeparators: [";"],
								tags:true,
								placeholder:"Выберите специализацию"
							}');
							//User::model();
							?>
						</div>
                    </div>
                </div>

				<label>Куратор</label>
				<div class="well">
                    <div class="form-group">

						<div class="col-xs-5">
							<?php CHtml::activeDropDownListChosen2(UserMentor::model(), 'id',CHtml::listData(UserMentor::model() -> findAll(),'id','name'), array('class' => 'select2 createUser','name' => 'User[id_mentor]'), array($model -> id_mentor), '{}'); ?>
						</div>
                    </div>
                </div>
				
            
        </fieldset>

<!--</div>--><div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset"><?php echo CHtml::submitButton($model->isNewRecord ? CHtml::encode('Создать') : CHtml::encode('Сохранить')); ?><button type="button" onClick="history.back()">Отмена</button></div></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div><ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-2" tabindex="0" style="display: none;"></ul><ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-3" tabindex="0" style="display: none;"></ul></div><span role="status" aria-live="assertive" aria-relevant="additions" class="ui-helper-hidden-accessible"></span><span role="status" aria-live="assertive" aria-relevant="additions" class="ui-helper-hidden-accessible"></span>
<?php $this -> endWidget(); ?>


<div class="ui-widget-overlay ui-front" style="z-index: 100;"></div>

