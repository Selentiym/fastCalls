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
        $mail = new PHPMailer();
        $mail -> setFrom('f.mrimaster.ru',SiteName);
        $mail -> addAddress($this -> email);
        $mail -> Subject = "Уведомление от f.mri";
        //todo добавить тему в форму отправки писем!
        $mail -> Body($text);
    }
}