

<?php 
	if (Yii::app() -> user -> checkAccess('admin')) :  
	$this -> renderPartial('//navBar', array('user' => User::model() -> giveLogged(), 'button' => 'no'));
	//$this -> renderPartial('//navBar');
	$data = new Data();
	//print_r($get);
	$from = (int)$get['from'] ? (int)$get['from'] : time() - 86400*5;
	$to = (int)$get['to'] ? (int)$get['to'] : time() + 24 * 3600 * 30;
	//$to = (int)$get['to'];
	$range = array('from' => $from, 'to' => $to);
	//print_r($range);
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/bundle-bundle_daterangepicker_defer.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/bundle-bundle_daterangepicker_defer.js');
	
	
	
	Yii::app()->getClientScript()->registerScript('DatePickerRange',"
		$(function () {

            $('#reportrange').daterangepicker({
                format: 'DD.MM.YYYY',
                startDate: '".date('d.m.Y', $range['from'])."',
                endDate: '".date('d.m.Y', $range['to'])."',
                minDate: '01.05.2015',
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
                window.location.href = '".Yii::app() -> baseUrl."/allCalls/' + start.unix() + '/' + end.unix()+'?call_types=".$_GET["call_types"]."';
            });

        });
	", CClientScript::POS_END);
 $translate = array(
    "am" => "дп",
    "pm" => "пп",
    "AM" => "ДП",
    "PM" => "ПП",
    "Monday" => "Понедельник",
    "Mon" => "Пн",
    "Tuesday" => "Вторник",
    "Tue" => "Вт",
    "Wednesday" => "Среда",
    "Wed" => "Ср",
    "Thursday" => "Четверг",
    "Thu" => "Чт",
    "Friday" => "Пятница",
    "Fri" => "Пт",
    "Saturday" => "Суббота",
    "Sat" => "Сб",
    "Sunday" => "Воскресенье",
    "Sun" => "Вс",
    "January" => "Января",
    "Jan" => "Янв",
    "February" => "Февраля",
    "Feb" => "Фев",
    "March" => "Марта",
    "Mar" => "Мар",
    "April" => "Апреля",
    "Apr" => "Апр",
    "May" => "Мая",
    "May" => "Мая",
    "June" => "Июня",
    "Jun" => "Июн",
    "July" => "Июля",
    "Jul" => "Июл",
    "August" => "Августа",
    "Aug" => "Авг",
    "September" => "Сентября",
    "Sep" => "Сен",
    "October" => "Октября",
    "Oct" => "Окт",
    "November" => "Ноября",
    "Nov" => "Ноя",
    "December" => "Декабря",
    "Dec" => "Дек",
    "st" => "ое",
    "nd" => "ое",
    "rd" => "е",
    "th" => "ое"
    );
	
?>
<?php 
	$criteria = BaseCall::model() -> giveCriteriaForTimePeriod($range['from'], $range['to']);
	$criteria -> order = 'date DESC';
	//$criteria -> compare('id_call_type',CallType::model() -> getNumber('verifyed'));
	if ($_GET["call_types"]) {
		$typeStr = $_GET["call_types"];
		switch ($typeStr) {
			case 'ver':
				$criteria -> compare('id_call_type',CallType::model() -> getNumber('verifyed'));
			break;
			case 'assigned_ver':
				$criteria -> addInCondition('id_call_type',array(CallType::model() -> getNumber('verifyed'),CallType::model() -> getNumber('assigned'),CallType::model() -> getNumber('missed')));
			break;
		}
	}
	$calls = BaseCall::model() -> findAll($criteria);
?>
<div class="panel panel-default">
	<div class="panel-heading">Все звонки в указанный период</div>

	<div class="panel-body">
		Показаны все звонки
		<span id="reportrange" style="border-bottom: dotted 1px; font-size: 150%">
			<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
			<span><small>с</small>
			<?php echo strtr(date("d F Y",$range['from']), $translate); ?>
			<small>по</small>
			<?php echo strtr(date("d F Y",$range['to']),$translate); ?>
			</span> <b class="caret"></b>
		</span>


		<br>
		Всего звонков: <?php echo count($calls); ?><br>
		Всего записей: <?php $counted = Data::model() -> countArray($calls); echo $counted['verifyed'] + $counted['assigned'] + $counted["missed"]; ?><br>
		Всего подтвержденных: <?php echo $counted['verifyed']; ?><br>
		Всего не пришло: <?php echo $counted['missed']; ?>
		<div id="all_call_types">
			<div id="ver" class="btn btn-default navbar-btn active">Только подтвержденные</div>
			<div id="assigned_ver" class="btn btn-default navbar-btn active">Подтвержденные, не пришедшие и записанные </div>
			<div id="all_calls" class="btn btn-default navbar-btn active">Все</div>
		</div>
		<?php
			Yii::app() -> getClientScript() -> registerScript('call_types','
				var hid = $("#call_types_hidden");
				$("#all_call_types").children().click(function(){
					hid.val($(this).attr("id"));
					hid.parent().submit();
				});
			',CClientScript::POS_END);
		?>
		<form id="call_types">
			<input type="hidden" id="call_types_hidden" value="<?php echo $_GET["call_types"]; ?>" name="call_types"/>
		</form>
	</div>
</div>
<?php CHtml::setTableSorting('allCallsTable',""); ?>
<table class="table table-stripped" style="margin-top: 10px" id="allCallsTable">
    <thead><tr>
        
        
        <th>Номер</th>
        <th>Статус</th>
        <th>Дата</th>
        <th>Медпред</th>
        <th>Доктор</th>
        <th>Телефон клиента</th>
        <th>mangoTalker</th>
        <th>Линия</th>
        <th>Отчет</th>
        <th>ФИО</th>
        <th>Тип исследования</th>
        <th>Отзыв</th>
    </tr>
	</thead>
	<?php
	/*usort($calls, function ($c1, $c2) {
		return mktime($c1 -> date) - mktime($c2 -> date);
	});*/
	$num = 0;
	foreach($calls as $call){
		$num ++;
		$this -> renderPartial('//oneCall_full', array('call' => $call, 'num' => $num));
	}
	?>
	</tbody>
</table>
<?php
else:
	$this -> renderPartial('//accessDenied');
endif;
?>