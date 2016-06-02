<?php
if (Yii::app() -> user -> checkAccess('admin')) :

	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/bundle-bundle_daterangepicker_defer.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/bundle-bundle_daterangepicker_defer.js');

	$admin = User::model() -> findByPk(Yii::app() -> user -> getId());
	$this -> renderPartial('//navBar',array('user' => $admin, 'button' => 'no'));
	$data = new Data();
	$command = Yii::app()->db->createCommand('SELECT UNIX_TIMESTAMP(MIN(`create_time`)) FROM {{user}}');
	$earliest = $command -> queryScalar();
	$from = $_GET['from'] ? $_GET['from'] : $earliest;
	$to = $_GET['to'] ? $_GET['to'] : time();
	
	
	$sortable = array('fio','create_time');
	$frompage = $_POST;
	//$frompage["sortby"] = 'create_time';
	if (in_array($frompage["sortby"],$sortable)) {
		$sortby = $frompage["sortby"];
	} else {
		$sortby = '';
	}
	$users = User::model() -> findAllByMainDocs($frompage["medPreds"],$sortby);
	//Сортировка в браузере
	Yii::app()->clientScript->registerScript('SortingFunc','
	var ch = "hello";
	
	function myFunc (node){
		node = $(node);
		if (node.hasClass("month")) {
			var text = node.html();
			
			//return text.substr(text.lastIndexOf(\'-&gt;\')+6);
			//alert(text.substr(text.lastIndexOf(\'-&gt;\')+6));
			var rez = text.substr(text.lastIndexOf(\'-&gt;\')+6);
			var maxl = rez.length;
			for (var i = 0; i < 5-maxl; i++) {
				rez = "0" + rez;
			}
			//alert(rez);
			//node.html(rez);
			return rez;
		} else {
			return node.html();
		}
	}
	//alert($("#admin_table tr:nth-child(2) td:nth-child(9)").html());
	//myFunc();
	',CClientScript::POS_END);
	CHtml::setTableSorting('admin_table',"{textExtraction:myFunc}");
	//Сортировка по параметрам.
	/*Yii::app()->getClientScript()->registerScript('SortingClick',"
		$('#admin_table tr:first-child th').each(function () {
			if ($(this).attr('sortby')) {
				$(this).css('cursor','pointer');
			}
		});
		$('#admin_table tr:first-child th').click(function (){
			if ($(this).attr('sortby')) {
				//alert($(this).attr('sortby'));
				$('#controlForm input[name=\"sortby\"]').val($(this).attr('sortby'));
				$('#controlForm').submit();
			}
		});
		$('#pager .pageNum').click(function (){
			$('#controlForm input[name=\"page\"]').val($(this).html());
			$('#controlForm').submit();
		});
		$('#goTo input[type=\"button\"]').click(function() {
			$('#controlForm input[name=\"page\"]').val($('#goTo input[type=\"text\"]').val());
			$('#controlForm').submit();
		});
	",CClientScript::POS_READY);*/
	
	
	//Календарик для выбора дат.
	Yii::app()->getClientScript()->registerScript('DatePickerRange',"
		$(function () {
            $('#reportrange').daterangepicker({
                format: 'DD.MM.YYYY',
                startDate: '".date('d.m.Y', $from)."',
                endDate: '".date('d.m.Y', $to)."',
                maxDate: ".strtotime("+1 month").",
                dateLimit: { months: 3 },
                ranges: {
                    'Сегодня': [moment(), moment()],
                    'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
                    'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
                    'Текущий месяц': [moment().startOf('month'), moment().endOf('month')],
                    'Предыдущий месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-primary',
                cancelClass: 'btn-default',
                separator: ' по ',
                locale: {
                    applyLabel: 'Показать',
                    cancelLabel: 'Отмена',
                    fromLabel: 'С',
                    toLabel: 'По',
                    customRangeLabel: 'Выбрать даты',
                    daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июлю', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                    firstDay: 1
                }
            }, function (start, end, label) {
                //end.subtract(1, 'days');
                window.location.href = '".Yii::app() -> baseUrl."/allstat/' + start.unix() + '/' + end.unix();
            });

        });
	", CClientScript::POS_END);
	
	Yii::app()->getClientScript()->registerScript('deleteScript','$(".delete-doctor").click(function(){
		if (!confirm("Вы уверены, что хотите удалить партнера "+$(this).attr("name")+"?")) {
			return false;
		} else {
			location.replace($(this).attr("goto"));
		}
	});',CClientScript::POS_END); 	
	
	
	$data = new Data();
	//print_r($get);
?>
<!-- Form to contain technical information and to be submitted if the changes are to take place -->
<form id="controlForm" method = "post">
	<input type="hidden" name="sortby" value = "<?php echo $frompage["sortby"]; ?>"/>
	<input type="hidden" name="sortDirection" value = "<?php echo $frompage["sortDirection"]; ?>"/>
	<input type="hidden" name="page" value = "<?php echo $frompage["page"]; ?>"/>

	<div class="panel panel-default">
		<div class="panel-heading">Статистика по записям</div>

		<div class="panel-body">
			Показана статистика
			<span id="reportrange" style="border-bottom: dotted 1px; font-size: 150%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
				<span><small>с</small>
				<?php echo LanguageHelper::engTranslateDate(date("d F Y",$from)); ?>
				<small>по</small>
				<?php echo LanguageHelper::engTranslateDate(date("d F Y",$to)); ?>
				</span> <b class="caret"></b>
			</span>


			<br>
		</div>
	</div>

	<table class="table table-bordered" id="admin_table">
	<thead>
	<tr>
	<th class="fio">Номер</th>
	<th class="fio" sortby="fio">ФИО</th>
	<th class="tel">Телефон</th>
	<th class="mail">email</th>
	<th class="create" sortby="create_time">Начало работы</th>
	<th class="del">del</th>

	<?php
		//print_r($frompage);
		$data = new Data;
		/*$user = User::model() -> findByAttributes(array('username' => 'doctor99'));
		print_r($user -> calls);*/
		$add = 604800;
		//print_r($data -> giveArrayKeys());
		//Вывд заголовка таблицы. Можно сделать посимпатичнее.
		foreach ($data -> giveArrayKeys($from, $to) as $moment) {
			echo "<th>";
			echo date('d M',$moment);
			echo " - ";
			echo date('d M',$moment+$add);
			echo "</th>";
		}
	?>
	<th class="del">Общее</th>
	</tr>
	</thead>
	<?php
		$num = 0;
		//$sortby = 'create_time';
		//$sortby = 'fio';
		/*uasort($users, function ($u1, $u2) use ($sortby){
			return (strcasecmp($u1 -> $sortby, $u2 -> $sortby));
		});*/
		$pageSize = 10;
		$maxPage = ceil(count($users) / $pageSize);
		if (($frompage["page"])&& ($frompage["page"] > 0)) {
			$page = $frompage["page"];
			if ($maxPage < $frompage["page"]) {
				$page = $maxPage;
			}
		} else {
			$page = 1;
		}
		foreach($users as $key => $user){
		//for ($num = ($page - 1) * $pageSize; $num <= $page * $pageSize; $num ++) {
			$num ++;
			/*if ($num <= ($page - 1) * $pageSize) {
				continue;
			}
			if ($num > $page * $pageSize) {
				break;
			}//*/
			$this -> renderPartial('//_showStat',array('user' => $user, 'data' => $data, 'from' => $from, 'to' => $to, 'num' => $num));
		}
	?>
	</table>
	<?php
		/* Вывод менюшки с главными партнерами */
		$initials = function($name){
			$words = explode (' ',$name);
			$rez = $words[0].' ';
			for ($i = 1; $i < count($words); $i++) {
				$rez .= mb_substr($words[$i],0,1,'utf-8').'.';
			}
			return $rez;
		};
		echo "<div id='medPreds'>";
		if (!is_array($frompage["medPreds"])) {
			$frompage["medPreds"] = array();
		}
		foreach (CHtml::listData(User::GiveMedPreds(),'id','fio') as $id => $name) {
			echo '<div>';
			echo "<input type='checkbox' value='".$id."' name='medPreds[]'";
			if (in_array($id,$frompage["medPreds"])) {
				echo " checked='checked' ";
			}
			echo "/>";
			echo "<span>".$initials($name)."</span>";
			echo '</div>';
		}
		echo "</div>";
		
	?>
	<input type="submit" value="Применить"/>
	<!--<div id="pager">
		<?php
			$start = max(1,$page - 4);
			$stop = min($page + 4, $maxPage);
			if ($start != $page) {
				echo "<div id='list_left'></div>";
			}
			for ($i = $start; $i <= $stop; $i++){
				$active = $page == $i ? 'active' : '' ;
				echo "<div class='pageNum ".$active."'>".$i."</div>";
			}
			if (($stop != $page)&&($stop > 0)) {
				echo "<div id='list_right'></div>";
			}
		?>

	</div>
	<div id="goTo" style="display:inline-block; vertical-align:middle;">
		<input type="text" style="width:50px;"/>
		<input type="button" value="Перейти"/>(1-<?php echo $maxPage; ?>)
	</div>-->
	<?php
	else:
		$this -> renderPartial('//accessDenied');
	endif;
	?>
</form>