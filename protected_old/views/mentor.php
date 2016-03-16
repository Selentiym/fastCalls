<div class="panel panel-default">
<?php //var_dump($mentor); ?>
	<div class="panel-heading">Есть вопрос или требуется помощь?</div>

	<div class="panel-body">
	Вашим куратором является: <strong><?php echo $mentor -> name; ?></strong><br>

	Email: <a href="mailto:<?php echo $mentor -> email; ?>"><?php echo $mentor -> email; ?></a><br>



	Телефон: <?php echo $mentor -> telephone; ?><br>
	<?php if ($mentor -> skype) : ?>
	Skype: <?php echo $mentor -> skype; ?>
	<?php endif; ?>
	</div>
</div>