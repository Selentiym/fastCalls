<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.05.2016
 * Time: 14:54
 */
class TimePeriod {
    public $from;
    public $to;
    /**
     * @param CDateTime $from
     * @param CDateTime $to
     */
    public function __construct(CDateTime $from = null, CDateTime $to = null){
        if (!$to) {
            $to = new CDateTime();
            $to -> setTime(24,0,0);
        }
        if (!$from) {
            $from = new CDateTime();
            $from -> setTime(0,0,0);
        }
        $this -> to = $to;
        $this -> from = $from;
    }

    /**
     * @param string|int $cellType
     * @return TimePeriod
     */
    public function nextCell($cellType = ''){
        //То, что раньше было концом интервала, теперь начало
        $this -> from = clone $this -> to;
        if (($cellType == 'week')||($cellType == 7)) {
            //Окончание сдвигаем на нужное количество времени.
            $this -> to = $this -> to -> setTime(24*7,0,0);
        } elseif ($cellType == 'month') {
            $this -> to -> setDate($this -> from -> year(), $this -> from -> month() + 1, 1);
        } else {
            $this -> to -> setTime(24,0,0);
        }
        return $this;
    }
    /**
     * @param int $cellOffset
     * @param string $cellType
     * @return TimePeriod
     */
    public static function fromCell ($cellOffset = 0, $cellType = ''){
        $fromTime = new CDateTime();
        $toTime = new CDateTime();
        $fromTime -> setTime(0,0,0);
        $toTime -> setTime(0,0,0);
        switch($cellType){
            case 'week':
                //Получаем начало недели
                $fromTime = self::weekStart($fromTime);
                $temp = $fromTime -> getTimestamp() + $cellOffset * 604800;
                //Сдвигаем на нужное количество недель вперед
                $fromTime -> setTimestamp($temp);
                //А конечный интервал еще на единичку дальше
                $toTime -> setTimestamp($temp + 604800);
                break;
            case 'month':
                //устанавливаем дату на первое число месяца
                $fromTime -> setDate($fromTime -> year(), $fromTime -> month() + $cellOffset, 1);
                //А конечную дату на месяц позже
                $toTime -> setDate($fromTime -> year(), $fromTime -> month() + $cellOffset + 1, 1);
                break;
            case 7:
                //просто прибавляем нужное количество сдвигов
                $fromTime -> setDate($fromTime -> year(), $fromTime -> month(), $fromTime -> day() + ($cellOffset - 1) * 7);
                //а к конечному времени прибавляем на 7 дней больше
                $toTime -> setDate($fromTime -> year(), $fromTime -> month(), $fromTime -> day() + 7);
                break;
            default:
                //просто прибавляем нужное количество сдвигов
                $fromTime -> setDate($fromTime -> year(), $fromTime -> month(), $fromTime -> day() + $cellOffset);
                //а к конечному времени прибавляем на 7 дней больше
                $toTime -> setDate($fromTime -> year(), $fromTime -> month(), $fromTime -> day() + 1);
                break;
        }
        return new self($fromTime, $toTime);
    }

    /**
     * @param CDateTime|null $time время, относительно которого искать понедельник
     * @return DateTime
     */
    public static function weekStart(DateTime $time = null) {
        if (!$time) {
            $time = new CDateTime();
        }
        if (!is_a($time, 'CDateTime')) {
            $save = $time -> getTimestamp();
            $time = new CDateTime();
            $time -> setTimestamp($save);
        }
        $time -> setTime(0,0,0);
        $date = getdate($time -> getTimestamp());
        $interval = DateInterval::createFromDateString((($date['wday'] - 1 + 7) % 7).' days ago');
        $time -> add($interval);
        return $time;
    }
    public function show(){
        echo "From {$this -> from -> format(DateTime::W3C)} to {$this -> to -> format(DateTime::W3C)}<br/>";
    }
}
class CDateTime extends DateTime {
    /**
     * @var array $date - contains the output of date
     */
    public $date;
    public function date() {
        //if (empty($this -> date)) {
            $this -> date = getdate($this -> getTimestamp());
        //}
        return $this -> date;
    }
    public function weekDay(){
        $d = $this -> date();
        return $d['wday'];
    }
    public function day(){
        $d = $this -> date();
        return $d['mday'];
    }
    public function year(){
        $d = $this -> date();
        return $d['year'];
    }
    public function month(){
        $d = $this -> date();
        return $d['mon'];
    }
}