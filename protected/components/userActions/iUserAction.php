<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 03.06.2016
 * Time: 11:03
 */
interface iUserAction {
    /**
     * Sets often changed model attributes according to data
     * @param mixed[] $data arguments to init model with.
     */
    public function initialize($data);
    /**
     * Sets model attributes that are set only once.
     * @param mixed $data[] arguments to init model with.
     */
    public function firstTimeInitialize($data);
    /**
     * Сохраняет в историю действия что-то.
     * @param string $logText
     */
    public function log($logText);
    /**
     * Выполняет действие. Может быть отправка смс, запрос отчета
     * у пользователя, отправка письма и так далее.
     */
    public function MakeAction();
}