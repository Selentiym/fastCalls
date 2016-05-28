<?php
	class ModelCollectionAction extends CAction
	{
		/**
		 * @var array args - array that specifyes the objects
		 */
		public $args;
		/**
		 * @var mixed addArgs - additional arguments to the action function
		 */
		public $addArgs;
		/**
		 * @var string model class for action
		 */
		public $modelClass;
		/**
		 * @var mixed - action to be done on every member of the collection. If callable, then it will be called
		 * if string, then it wil be interpreted as an object method.
		 */
		public $action;
		/**
		 * @var mixed redirect - will be used in CController -> redirect() method.
		 */
		public $redirect = false;
		/**
		 * @var bool close - if true, the window is closed right after actions
		 */
		public $close = false;
		/**
		 * @var string view for render
		 */
		public $view;
		/**
		 * @var boolean partial - whether to user render partial.
		 */
		public $partial = false;

		/**
		 * @var bool|callable - function to be executed before everything.
		 */
		public $preaction = false;

		public function run()
		{
			if (!Yii::app() -> user -> isGuest) {
				//Результат работы $this -> preaction
				$thirdArg = false;
				//Выполняем подготовительную функцию, если таковая имеется.
				if ($this -> preaction) {
					$preaction = $this -> preaction;
					if (is_callable($preaction)) {
						$thirdArg = $preaction($this -> addArgs, $this -> args);
					}
				}
				$modelClass = ($this -> modelClass);
				//Получаем CList коллекции
				$list = $modelClass::model() -> giveCollection($this -> args);
				$action = $this -> action;
				//Если нам передана функция, то вызываем ее с от каждого слена коллекции
				if (is_callable($action)) {
					foreach($list as $mem){
						$action($mem, $this -> addArgs, $thirdArg);
					}
				//Если же нам дана строка, то вызываем ее как метод каждого элемента коллекции
				} elseif (method_exists($modelClass, $action)) {
					foreach($list as $mem){
						$mem -> $action($this -> addArgs, $thirdArg);
					}
				}
				if ($this -> close) {
					Yii::app() -> getClientScript() -> registerScript("close","window.close();",CClientScript::POS_BEGIN);
					echo "close!";
					Yii::app() -> end();
				}
				//После окончания действий либо перенаправляем, либо рендерим вьюшку.
				if ($this -> redirect) {
					$this -> controller -> redirect($this -> redirect);
				} else {
					if ($this -> view) {
						if ($this -> partial) {
							$this -> controller -> renderPartial($this -> view);
						} else {
							$this -> controller -> render($this -> view);
						}
					}
				}
			} else {
				$this -> controller -> redirect(Yii::app() -> baseUrl.'/login');
			}
		}
	}
?>