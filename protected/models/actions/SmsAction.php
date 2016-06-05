<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 03.06.2016
 * Time: 11:40
 */
/**
 * This is the model class for table "{{actions}}".
 *
 * The followings are the available columns in table '{{actions}}':
 * @property string $id
 * @property integer $id_owner
 * @property integer $id_user
 * @property integer $id_chain
 * @property integer $id_type
 * @property integer $id_status
 * @property string $created
 * @property string $time
 * @property string $period
 * @property string $comment
 * @property string $report
 * @property integer $auto
 * @property integer $id_sms
 */
class SmsAction extends SendMessageAction {
    const TYPE = 1;
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return parent::rules();
    }
    public function sendIt() {
        $arr = $this -> user -> sendSms($this -> text);
        //Если ошибка, добавляем в конец коммента ее текст.
        if ($arr['error']) {
            $this -> comment .= PHP_EOL . $arr['error'];
            $this -> id_status = self::ERROR;
        }
    }
}