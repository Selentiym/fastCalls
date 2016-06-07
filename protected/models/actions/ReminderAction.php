<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.06.2016
 * Time: 16:19
 */
class ReminderAction extends UserAction {
    const TYPE = 3;
    public function MakeAction($data){
        if ($data['postpone']) {
            $this -> Postpone($data['postponed'], false);
        } elseif ($data['report']) {
            $this -> report = $data['report'];
        }
        return;
    }
}