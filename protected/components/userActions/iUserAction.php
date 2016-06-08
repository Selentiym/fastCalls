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
     * @param mixed[] $data - содержит информацию со страницы о том,
     * как действие выполнить.
     */
    public function MakeAction($data);
    /**
     * Переносим действие на заданное время
     * @param int $time - unix time when  to make action
     * @param bool $save
     * @return bool whether the action was postponed
     */
    public function Postpone($time, $save = true);
    /**
     * @return string[] - массив названий вьюх, с помощью который отобразить действие
     * Вьюхи вызываются по очереди, внутрь передается $model, содержащий модель действия
     */
    public function giveViews();

    /**
     * Обновляет отчет и статус действия в зависимости от переданной пользователем
     * информации. Актуально скорее для действий с auto = 0.
     * @param $data
     * @return mixed
     */
    public function addReport($data);
}