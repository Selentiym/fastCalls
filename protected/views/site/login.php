<!DOCTYPE html>
<!-- saved from url=(0032)http://p.mrimaster.ru/login/auth -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Войти</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./enter_files/main.css" type="text/css">
    <link rel="stylesheet" href="./enter_files/mobile.css" type="text/css">
    
	<meta name="layout" content="main">
	
	<style type="text/css" media="screen">
	#login {
		margin: 15px 0px;
		padding: 0px;
		text-align: center;
	}

	#login .inner {
		width: 340px;
		padding-bottom: 6px;
		margin: 60px auto;
		text-align: left;
		border: 1px solid #aab;
		background-color: #f0f0fa;
		-moz-box-shadow: 2px 2px 2px #eee;
		-webkit-box-shadow: 2px 2px 2px #eee;
		-khtml-box-shadow: 2px 2px 2px #eee;
		box-shadow: 2px 2px 2px #eee;
	}

	#login .inner .fheader {
		padding: 18px 26px 14px 26px;
		background-color: #f7f7ff;
		margin: 0px 0 14px 0;
		color: #2e3741;
		font-size: 18px;
		font-weight: bold;
	}

	#login .inner .cssform p {
		clear: left;
		margin: 0;
		padding: 4px 0 3px 0;
		padding-left: 105px;
		margin-bottom: 20px;
		height: 1%;
	}

	#login .inner .cssform input[type='text'] {
		width: 120px;
	}

	#login .inner .cssform label {
		font-weight: bold;
		float: left;
		text-align: right;
		margin-left: -105px;
		width: 110px;
		padding-top: 3px;
		padding-right: 10px;
	}

	#login #remember_me_holder {
		padding-left: 120px;
	}

	#login #submit {
		margin-left: 15px;
	}

	#login #remember_me_holder label {
		float: none;
		margin-left: 0;
		text-align: left;
		width: 200px
	}

	#login .inner .login_message {
		padding: 6px 25px 20px 25px;
		color: #c33;
	}

	#login .inner .text_ {
		width: 120px;
	}

	#login .inner .chk {
		height: 12px;
	}
	</style>

    
    <script src="./enter_files/jquery-1.10.2.min.js" type="text/javascript"></script>

</head>

<body>

<div id="login">
	<div class="inner">
		<div class="fheader">Пожалуйста войдите в систему</div>

		
<?php $form=$this->beginWidget('CActiveForm', array(
	'htmlOptions' => array(
		'id'=>'loginForm',
		'class' => 'cssform',
	),
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
			<p>
				<label for="username">Имя:</label>
				<?php echo $form->textField($model,'username', array('id'=>'username','class'=>'text_')); ?>
			</p>

			<p>
				<label for="password">Пароль:</label>
				
				<?php echo $form->passwordField($model,'password', array('id' => 'password', 'class' => '_text')); ?>
			</p>

			<p id="remember_me_holder">
				<?php echo $form->checkBox($model,'rememberMe', array('id' => 'remember_me', 'class' => 'chk')); ?>
				
				<label for="remember_me">Запомните меня</label>
			</p>

			<p>
				<input type="submit" id="submit" value="Войти">
			</p>
<?php $this->endWidget(); ?>
	</div>
</div>
<script type="text/javascript">
	<!--
	(function() {
		document.forms['loginForm'].elements['j_username'].focus();
	})();
	// -->
</script>




</body></html>