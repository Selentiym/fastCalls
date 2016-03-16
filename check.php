<?php
	//$f = fopen(Yii::getPathOfAlias('application'). DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . "log{$dateStr}.txt",'a+');
	$f = fopen('log','a+');
	fwrite($f,date('Y_M_d H:i:s: ').'TelfinCheck started<br/>'.PHP_EOL);
	//fwrite($f, $out.'<br/>'.PHP_EOL);
	fclose($f);
?>