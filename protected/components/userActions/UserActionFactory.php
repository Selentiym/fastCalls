<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 03.06.2016
 * Time: 10:40
 */

/**
 * Class UserActionFactory
 * В основном будет использоваться для создания нового действия,
 *  поскольку в дальнейшем из базы будет вытаскиваться объект нужного класса.
 */
class UserActionFactory {
    const DefaultClass = 'UserAction';
    /**
     * @var UserActionFactory
     */
    private static $instance;
    /**
     * @var string[] $types содержит типы доступных действий
     */
    public static $types = [
        1 => 'sms',
        2 => 'email',
        3 => 'reminder'
    ];
    //Содержит логику создания или доставания из базы действия. Выбирает
    // какой класс дйствия создавать в зависимости от $data.
    /**
     * @param $data
     * @return iUserAction
     */
    public function giveAction($data) {
        //По умолчанию класс модели - просто UserAction, но
        // нежелательно, чтобы так было.
        $className = self::DefaultClass;
        $type = strtolower(trim($data['actionType']));
        //Если явно указан тип действия, то используем его.
        if (in_array($type, self::$types)) {
            $className = ucfirst($type) . "Action";
            if (!class_exists($className)) {
                $className = self::DefaultClass;
            }
        }
        /**
         * @type UserAction|null $act
         */
        $act = null;
        //Если задан id модели, то ищем ее в базе.
        if ($data['id']) {
            $act = $className::model() -> findByPk($data['id']);
        }
        //Если модель не принадлежит основному классу, то создаем новую.
        if (!is_a($act, 'UserAction')) {
            $act = new $className();
        }
        //Инициилизируем модель. К данному моменту, что бы ни было передано,
        // будет определен объект $act.
        $act -> initialize($data);
        $time = time();
        if (($data['makeNow'])||($act -> time < $time)) {
            $act -> time = $time;
            $act -> MakeAction();
        }
        return $act;
    }

    /**
     * using a singleton pattern
     */
    private function __construct() { }
    public static function getInstance() {
        if ( empty( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}