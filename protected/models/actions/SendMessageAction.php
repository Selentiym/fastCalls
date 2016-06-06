<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.06.2016
 * Time: 17:06
 */
abstract class SendMessageAction extends UserAction {
    /**
     * Отравляет сообщение.
     */
    abstract protected function sendIt();

    /**
     * @param mixed[] $data
     */
    public function initialize($data){
        parent::initialize($data);
        $this -> text = $this -> prepareText($data['text']);
        //Предполагается, что отправка происходит автоматически.
        $this -> auto = true;
        return;
    }
    /**
     * Готовит текст к отправке пользователю.
     * По умолчанию использует стандартные подстановки.
     */
    protected function prepareText(){
        return SmsPattern::prepareText($this -> user, $this -> text);
    }
    public function MakeAction() {
        $this -> sendIt();
        $this -> save();
    }
}