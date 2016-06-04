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
     * @param mixed $data[] arguments to init model with.
     */
    public function initialize($data);
    /**
     * Sets model attributes that are set only once according to data
     * @param mixed $data[] arguments to init model with.
     */
    public function firstTimeInitialize($data);
}