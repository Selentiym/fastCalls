<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.06.2016
 * Time: 16:19
 */
class EmailAction extends SendMessageAction {
    const TYPE = 2;
    //Можно рассмотреть вариант Disposition-Notification-To
    public function sendIt(){
        $rez = $this -> user -> sendEmail($this -> text);
        if ($rez['report']) {
            $this -> id_status = UserAction::GOOD;
            $this -> log($rez['report'], false);
        } elseif ($rez['error']) {
            $this -> id_status = UserAction::ERROR;
            $this -> log($rez['error'], false);
        } else {
            $this -> id_status = UserAction::ERROR;
            $this -> log('Возникла неизвестная ошибка.', false);
        }
    }
}