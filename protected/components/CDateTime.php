<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.05.2016
 * Time: 19:09
 */
class CDateTime extends DateTime {
    /**
     * Формат даты со временем.
     */
    const longFormat = "Y-m-d H:i";
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