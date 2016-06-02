<?php if ((Yii::app() -> user -> checkAccess('viewOwnUserCabinet',array('user' => $model)))||((Yii::app() -> user -> checkAccess('viewUserCabinet')))) :  
	$user = $model;
	if($user -> id_type == UserType::model() -> getNumber('maindoc')) {
		$user -> prepareCalls();
	}
	$this -> renderPartial('//navBar', array('user' => $user));
	$data = new Data();
	//print_r($get);
	$range = $data -> TransformGivenGetArrayToTimeRange($get, $user);
	//print_r($range);
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/bundle-bundle_daterangepicker_defer.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/bundle-bundle_daterangepicker_defer.js');
	$username = (Yii::app() -> user -> getId() == $user -> id) ? '': '/'.$user -> username ;
	
	
	
	Yii::app()->getClientScript()->registerScript('DatePickerRange',"
		$(function () {

            $('#reportrange').daterangepicker({
                format: 'DD.MM.YYYY',
                startDate: '".date('d.m.Y', $range['from'])."',
                endDate: '".date('d.m.Y', $range['to'])."',
                minDate: '".date('d.m.Y', strtotime($user -> create_time))."',
                
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
                window.location.href = '".Yii::app() -> baseUrl."/stat".$username."/' + start.unix() + '/' + end.unix();
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
<?php $calls = Data::model() -> giveCallsInRange($range['from'],$range['to'], $user); ?>
<div class="panel panel-default">
	<div class="panel-heading">Статистика по записям</div>

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
		Всего записей: <?php $counted = Data::model() -> countArray($calls); echo $counted['verifyed'] + $counted['assigned']; ?>
	</div>
</div>
<table class="table table-stripped" style="margin-top: 10px">
    <tbody><tr>
        
        
        <th>Номер</th>
        <th>Статус</th>
        <th>Дата</th>
        <th>Партнер</th>
        <th>Телефон клиента</th>
        <th>Отчет</th>
        <th>ФИО</th>
        <th>Тип/Описание ремонта</th>
        <th>Отзыв</th>
    </tr>
	<?php
	/*usort($calls, function ($c1, $c2) {
		return mktime($c1 -> date) - mktime($c2 -> date);
	});*/
	$num = 0;
	foreach($calls as $call){
		$num ++;
		$this -> renderPartial('//oneCall', array('call' => $call, 'num' => $num));
	}
	?>
	</tbody>
</table>
<?php
else:
	$this -> renderPartial('//accessDenied');
endif;
?>