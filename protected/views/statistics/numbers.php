<?php
	$this -> renderPartial('//navBar', array('user' => User::model() -> giveLogged(), 'button' => 'no'));
	$time = new TimeHelper('statistics',$_GET["from"],$_GET["to"]);
	$time -> field = 'time';
	$time -> showMenu();
?>
<div class="row">
    <div class="col-xs-4">
        <div class="panel panel-default">
            <div class="panel-heading">Количество звонков (tCall)</div>
            <div class="panel-body">
                <div class="list-group-item">
					<?php
						$numbers = Yii::app()->db->createCommand() -> select('COUNT(caller)')  -> from('{{telfin_call}}') -> where($time -> giveSql()) -> queryScalar();
						echo 'Всего: '.$numbers;
					?>
				</div>
				<div class="list-group-item">
					<?php
						$uniqueNumbers = Yii::app()->db->createCommand() -> select('COUNT(DISTINCT caller)') -> where($time -> giveSql()) -> from('{{telfin_call}}') -> queryScalar();
						echo 'Уникальных: '.$uniqueNumbers;
					?>
				</div>
				<div class="list-group-item">
					<?php
						$uniqueNumbersErrors = Yii::app()->db->createCommand() -> select('COUNT(DISTINCT caller)')  -> from('{{telfin_call}}') -> where('id_call IS NULL AND '. $time -> giveSql()) -> queryScalar();
						echo 'Уникальных, не привязанных к гугл док: '.$uniqueNumbersErrors;
					?>
				</div>
                
            </div>
        </div>
    </div>
	<div class="col-xs-4">
        <div class="panel panel-default">
           <div class="panel-heading">Количество исследований (bCall)</div>
		   <?php 
				$time -> field = 'dateString_timestamp';
			?>
            <div class="panel-body">
                <div class="list-group-item">
					<?php
					$allCalls = Yii::app()->db->createCommand() 
					-> select('COUNT(id)')  
					-> from('{{call}}') 
					-> where($time -> giveSql() . BaseCall::notDeletedSql()) //id_user = 1 - это удаленный пользователь. Ни к чему нам учитывать его в статистике.
					-> queryScalar(); 
					echo 'Всего исследований: '.$allCalls;
					?>
				</div>
                <div class="list-group-item">
					<?php 
					$goodCalls = Yii::app()->db->createCommand() 
					-> select('COUNT(id)')  
					-> from('{{call}}') 
					-> where('id_call_type IN ('.CallType::model() -> getNumber("assigned").','.CallType::model() -> getNumber("verifyed").') AND ' . $time -> giveSql() . BaseCall::notDeletedSql()) 
					-> queryScalar(); 
					echo 'Записанных и подтверженных (хороших) исследований: '.$goodCalls;
					?>
				</div>
                <div class="list-group-item">
					<?php 
					$badCalls = Yii::app()->db->createCommand() 
					-> select('COUNT(id)')  
					-> from('{{call}}') 
					-> where('id_call_type  NOT IN ('.CallType::model() -> getNumber("assigned").','.CallType::model() -> getNumber("verifyed").') AND ' . $time -> giveSql() . BaseCall::notDeletedSql()) 
					-> queryScalar(); 
					echo 'Остального типа (плохих) исследований: '.$badCalls;
					?>
				</div>
                <div class="list-group-item">
					<?php 
					$notErrorCalls = Yii::app()->db->createCommand() 
					-> select('COUNT(id)')  
					-> from('{{call}}') 
					-> where('id_user IS NOT NULL AND ' . $time -> giveSql() . BaseCall::notDeletedSql()) 
					-> queryScalar(); 
					echo 'Всего привязанных: '.$notErrorCalls;
					?>
				</div>
                <div class="list-group-item">
					<?php 
					$errorCalls = Yii::app()->db->createCommand() 
					-> select('COUNT(id)')  
					-> from('{{call}}') 
					-> where('id_user IS NULL AND ' . $time -> giveSql() . BaseCall::notDeletedSql()) 
					-> queryScalar(); 
					echo 'Ошибок (не привязался доктор): '.$errorCalls;
					?>
				</div>
                <div class="list-group-item">
					<?php 
					$goodErrorCalls = Yii::app()->db->createCommand() 
					-> select('COUNT(id)')  
					-> from('{{call}}') 
					-> where('id_user IS NULL AND id_call_type IN ('.CallType::model() -> getNumber("assigned").','.CallType::model() -> getNumber("verifyed").') AND ' . $time -> giveSql() . BaseCall::notDeletedSql()) 
					-> queryScalar(); 
					echo 'Хороших в ошибках: '.$goodErrorCalls;
					?>
				</div>
            </div>
        </div>
    </div>
</div>