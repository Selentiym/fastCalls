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
		$tel = '407 29 81';
		$num = mt_rand($min, $max);
		$this -> renderPartial('//print/oneDirectionImage', array('fio' => $user -> fio, 'number' => $num, 'tel' => $tel));
		echo "<div class='separator'></div>";
		$this -> renderPartial('//print/oneDirectionImage', array('fio' => $user -> fio, 'number' => $num, 'tel' => $tel));
		echo "<div class='separator'></div>";
		$this -> renderPartial('//print/oneDirectionImage', array('fio' => $user -> fio, 'number' => $num, 'tel' => $tel));
	} else {
		echo "Проблемы с интервалом направлений партнера.";
	}
	
?>
</div>