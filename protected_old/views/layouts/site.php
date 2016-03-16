<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		
		
		<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap.min.css'); ?>
		<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/bundle-bundle_bootstrap_defer.css'); ?>
		<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/custom.css'); ?>
		<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/pager.css'); ?>
		
		<?php  Yii::app()->getClientScript()->registerCoreScript('jquery'); ?>
		
		<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/css/bootstrap.min.js'); ?>
		<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/css/bundle-bundle_bootstrap_defer.js'); ?>
		
		<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/select2.min.css'); ?>
		<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/select2.full.js'); ?>
		<?php //Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/select2_locale_ru.js'); ?>
		<link rel="shortcut icon" href="<?php echo Yii::app() -> baseUrl;?>/images/favicon.ico" type="image/x-icon"/>
		
		<?php
		$title = $this -> getPageTitle();
		$title = $title ? $title : Yii::app() -> name;
		?>
		<title><?php echo $title; ?></title>

	</head>

	<body>
	<div class="container-fluid" style="margin: 10px">

		
		
	<!-- navbar -->

			<div class="row">
				<?php echo $content; ?>
			</div>

	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	</div>
	</body>
</html>