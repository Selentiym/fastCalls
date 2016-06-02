<div class="single_direction">
	<div class="heading">Общегородская медицинская служба записи на МРТ и КТ диагностику</div>
	<div class="tel">Многоканальный телефон службы записи: <?php echo $tel; ?></div>
	<div class="bottom_line"></div>
	<div class="inf_list">Информационный лист №<span><?php echo $number; ?></span></div>
	<div class="bottom_line"></div>
	<div class="block">
		<div class="row">
			<div class="checkbox_cont"><span class="checkbox"></span><div class="name">МРТ</div></div><span class='contrast'>необходимость контрастирования</span><div class="checkbox_cont"><div class="checkbox"></div>Да<div class="checkbox"></div>Нет</div>
		</div>
		<div class="row">
			<div class="checkbox_cont"><span class="checkbox"></span><div class="name">КТ</div></div><span class='contrast'>необходимость контрастирования</span><div class="checkbox_cont"><div class="checkbox"></div>Да<div class="checkbox"></div>Нет</div>
		</div>
		<div class="row">
			<div class="checkbox_cont"><div class="checkbox"></div>Другой ремонт</div><div class="custom_gap_1"></div>
		</div>
	</div>
	<div class="block">
		<div class="block_heading">Название ремонта</div>
		<div class="obl_issled"><div class="gap_80"></div></div>
	</div>
	<div class="container_no_border">
		<div class="row gap_cont"><span class="gap_int">Фамилия:</span></div>
		<div class="row gap_cont"><span class="gap_int">Имя:</span></div>
		<div class="row gap_cont"><span class="gap_int">Отчество:</span></div>
		<div class="row">Дата рождения: "<span class="gap_tiny"></span>"<span class="custom_gap_2"></span>&nbsp20&nbsp<span class="gap_tiny"></span>&nbspг.</div>
		<div class="row diagn">Предварительный диагноз/Цель исследования:</div>
		<div class="row gap_cont"></div>
		<div class="row gap_cont"></div>
		<div class="row gap_cont"></div>
		<div class="row gap_cont w80"><span class="gap_int">Лечебное учреждение:</span></div>
		<div class="row gap_cont w80"><span class="gap_int">Врач:</span><?php echo $fio; ?></div>
	</div>
	<div class="assign block">
		Записаться на ремонт<br/>Вы можете по телефону: <?php echo $tel; ?>
	</div>
	<div class="block">
		<div class="heading">Пожалуйста, впишите адрес, дату и время Вашего исследования:</div>
		<div class="row">Дата: "<span class="gap_tiny"></span>"<span class="custom_gap_2"></span>&nbsp20&nbsp<span class="gap_tiny"></span>&nbspг. Время:<span class="gap_tiny"></span></div>
		<div class="row gap_cont addr"><span class="gap_int">Адрес:</span></div>
	</div>
	<div class="full_hor_line"></div>
	<img src ="<?php echo Yii::app() -> baseUrl; ?>/images/print_logo.png" class="logo" />
</div>