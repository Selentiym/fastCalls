<?php if (Yii::app() -> user -> checkAccess('admin')) :  

	$admin = User::model() -> findByPk(Yii::app() -> user -> getId());
	$this -> renderPartial('//navBar',array('user' => $admin, 'button' => 'no'));
	$data = new Data();
	$command = Yii::app()->db->createCommand('SELECT UNIX_TIMESTAMP(MIN(`create_time`)) FROM {{user}}');
	$earliest = $command -> queryScalar();
	$from = $_GET['from'] ? $_GET['from'] : $earliest;
	
	$to = $_GET['to'] ? $_GET['to'] : time();
	/*$criteria = new CDbCriteria();
	$criteria -> with = 'calls';
	$criteria -> addNotInCondition('id_type', array(UserType::model() -> getNumber('admin')));//1 заменить на что-то, что относится к админам
	$users = User::model() -> findAllByMainDocs($criteria);*/
	$users = User::model() -> findAllByMainDocs('','');
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/bundle-bundle_daterangepicker_defer.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/bundle-bundle_daterangepicker_defer.js');


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
                window.location.href = '".Yii::app() -> baseUrl."/paystat/' + start.unix() + '/' + end.unix();
            });

        });
	", CClientScript::POS_END);
	
	
	
	
	$data = new Data();
	//print_r($get);

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
<div class="panel panel-default">
	<div class="panel-heading">Статистика по записям</div>

	<div class="panel-body">
		Показана статистика
		<span id="reportrange" style="border-bottom: dotted 1px; font-size: 150%">
			<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
			<span><small>с</small>
			<?php echo strtr(date("d F Y",$from), $translate); ?>
			<small>по</small>
			<?php echo strtr(date("d F Y",$to),$translate); ?>
			</span> <b class="caret"></b>
		</span>


		<br>
	</div>
</div>

<table class="table table-bordered" id="admin_table">
<tr>
<th class="fio">Номер</th>
<th class="fio">ФИО</th>
<th class="stat">Звонки->записи</th>
<th class="assign">Ожидают</th>
<th class="miss">Не пришли</th>
<th class="verify">Пришли</th>
<th class="money">Оплата</th>
<th class="tel">Контакты</th>
<th class="cond">Условия</th>
</tr>
<?php
	$num = 0;
	foreach($users as $user){
		$num ++;
		$this -> renderPartial('//_paystat_single',array('user' => $user, 'data' => $data, 'from' => $from, 'to' => $to,'num'=>$num));
	}
?>
</table>

<?php
else:
	$this -> renderPartial('//accessDenied');
endif;
?>