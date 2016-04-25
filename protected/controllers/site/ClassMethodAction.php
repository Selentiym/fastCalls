<?php
	class ClassMethodAction extends CAction
	{
		/**
		 * @var string modelClass -  class for action
		 */
		public $modelClass;
		/**
		 * @var string method - method to be called. 
		 */
		public $method;
		/**
		 * @var mixed args - arguments for the method
		 */
		public $args = false;
		/**
		 * @var bool/callable access - access
		 */
		public $access = false;
		/**
		 * @var bool $ajax - whether this method has to be invoked by an ajax request.
		 */
		public $ajax = false;
		/**
		 * @var bool/string view - view for render
		 */
		public $view = false;
		/**
		 * @var boolean partial
		 */
		public $partial = false;
		/**
		 * @var string scenario - scenario that is to be assigned to the model
		 */
		public $scenario = false;
		/**
		 * @var string|array redirectUrl - the Url or an array to generate url where the user will be redirected after update
		 */
		public $redirectUrl = array('/cabinet');
		/**
		 * @param $arg string  - the argument of customFind
		 * @throws CHttpException
		 */
		public function run($arg = false)
		{
			if (!Yii::app() -> user -> isGuest) {
				$access = false;
				if (is_callable($this -> access)) {
					$name = $this -> access;
					if ($name()) {
						$access = true;
					} else {
						$access = false;
					}
				} else {
					$access = $this -> access ? true : false;
				}
				//Если у зашедшего достаточно прав, чтобы выполнить метод.
				if ($access) {
					//Ищем объект.
					$modelClass = $this -> modelClass;
					$method = $this -> method;
					//Поиск производим с уже заданным сценарием, чтобы
					// адекватно искать в CustomFind
					$toSearch = $modelClass::model();
					if ($this -> scenario) {
						$toSearch -> setScenario($this -> scenario);
					}
					$object = $toSearch -> customFind($arg);
					
					if (is_a($object, $modelClass)) {
						if ($this -> scenario) {
							$object -> setScenario($this -> scenario);
						}
						
						if (method_exists($object, $method)) {
							$object -> $method ($this -> args);
							if ($this -> ajax) {
								//if this is an ajax method, stop any other output.
								return;
							}
							if ($this -> view) {
								if ($this -> partial) {
									$this -> controller -> renderPartial(array('model' => $object));
								} else {
									$this -> controller -> render(array('model' => $object));
								}
							} else {
								$this -> controller -> redirect($this -> redirectUrl);
							}
						} else {
							new CustomFlash('error',$this -> modelClass, 'MethodNotFound','Метод не найден!',true);
						}
					} else {
						new CustomFlash('error',$this -> modelClass, 'notFound','Объект не найден!',true);
					}
				} else {
					$this -> controller -> render('//accessDenied');
				}
				//}
			} else {
				$this -> controller -> redirect(Yii::app() -> baseUrl.'/login');
				//$this -> controller -> redirect($this -> redirectUrl);
			}
		}
	}
?>