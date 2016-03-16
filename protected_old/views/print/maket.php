<div id="wrapper">
<?php
	if (($user -> jMin)&&($user -> jMax)&&($user -> jMax >= $user -> jMin)) {
		$min = $user -> jMin;
		$max = $user -> jMax;
	}
	if (($user -> jMin_add)&&($user -> jMax_add)&&($user -> jMax_add >= $user -> jMin_add)) {
		$min = $user -> jMin_add;
		$max = $user -> jMax_add;
	}
	if ($min && $max) {
		$tel = '407 11 86';
		$this -> renderPartial('//print/oneDirection', array('fio' => $user -> fio, 'number' => mt_rand($min, $max), 'tel' => $tel));
		echo "<div class='separator'></div>";
		$this -> renderPartial('//print/oneDirection', array('fio' => $user -> fio, 'number' => mt_rand($min, $max), 'tel' => $tel));
		echo "<div class='separator'></div>";
		$this -> renderPartial('//print/oneDirection', array('fio' => $user -> fio, 'number' => mt_rand($min, $max), 'tel' => $tel));
	} else {
		echo "Проблемы с интервалом направлений доктора.";
	}
	
?>
</div>