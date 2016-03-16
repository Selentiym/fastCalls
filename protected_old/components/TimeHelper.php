<?php
class TimeHelper {
	const DAYS_ADD = 30;
	public $from;
	public $to;
	/**
	 * @property string field - the field of the criteria
	 */
	public $field;
	public $translate = array(
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
	/**
	 * @property string id - the html id of the container
	 */
	public $id = false;
	/**
	 * @property string url - the redirect address will be window.location.href = '".Yii::app() -> baseUrl."/".$url."/' + start.unix() + '/' + end.unix();
	 */
	public $url;
	/**
	 * @arg integer from - unix moment of the lower bound
	 * @arg integer to - unix moment of the upper bound
	 */
	public function __construct($url, $from = false, $to = false){
		Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/bundle-bundle_daterangepicker_defer.css');
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/bundle-bundle_daterangepicker_defer.js');
		$this -> url = $url;
		if ($from !== false) {
			$this -> from = (int)$from ? (int)$from : time() - 86400*5;
		}
		if ($to !== false) {
			$this -> to = (int)$to ? (int)$to : time() + 24 * 3600 * self::DAYS_ADD;
		}
		$this -> id = md5(time());
	}
	public function showMenu(){
		echo '
		<span id="reportrange'.$this -> id.'" style="border-bottom: dotted 1px; font-size: 150%">
		<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
		<span><small>с</small>';
		echo strtr(date("d F Y",$this -> from), $this -> translate);
		echo '<small>по</small>';
		echo strtr(date("d F Y",$this -> to),$this -> translate);
		echo '</span> <b class="caret"></b>
		</span>
		';
		Yii::app()->getClientScript()->registerScript('DatePickerRange',"
			$(function () {

				$('#reportrange".$this -> id."').daterangepicker({
					format: 'DD.MM.YYYY',
					startDate: '".date('d.m.Y', $this -> from)."',
					endDate: '".date('d.m.Y', $this -> to)."',
					
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
					window.location.href = '".Yii::app() -> baseUrl."/".$this -> url."/' + start.unix() + '/' + end.unix();
				});

			});
		", CClientScript::POS_END);
	}
	/**
	 * @return CDbCRiteria
	 */
	public function giveCriteria(){
		if ($this -> field) {
			$criteria = new CDbCriteria;
			if ((int)($this -> from)) {
				$criteria -> addCondition($this -> field.' >= FROM_UNIXTIME('.$from.')');
			}
			if ((int)($this -> to)) {
				$criteria -> addCondition($this -> field.' < FROM_UNIXTIME('.$to.')');
			}
			return $criteria;
		} else {
			echo "no field is set!!!";
			return false;
		}
	}
	/**
	 * @return string - SQL that makes the condition.
	 */
	public function giveSql(){
		if ($this -> field) {
			return $this -> field.' >= FROM_UNIXTIME('.$this -> from.') AND ' . $this -> field . ' < FROM_UNIXTIME('.$this -> to.')';
		} else {
			echo "no field is set!!!";
			return '';
		}
	}
	/**
	 * 
	 */
	public function toDate(){
		return date('c', $this -> to);
	}
	/**
	 * 
	 */
	public function fromDate(){
		return date('c', $this -> from);
	}
}
?>