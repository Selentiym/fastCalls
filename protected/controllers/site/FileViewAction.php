<?php
	class FileViewAction extends CAction
	{
		
		/**
		 * @var callable access function to be called to access page
		 */
		public $access;
		/**
		 * @var string view for render
		 */
		public $view;
		/**
		 * @var bool $partial whether to use renderPartial or Render
		 */
		public $partial = false;
		/**
		 * if $this ->partial is true, then this option shows whether to show scripts
		 */
		public $showScripts = false;
		/**
		 * @param $arg string model argument to be taken into customFind
		 * @throws CHttpException
		 */
		public function run()
		{
			if (!Yii::app() -> user -> isGuest) {
				if (is_callable($this -> access)) {
					$name = $this -> access;
					if ($name()) {
						$this->controller->layout = '//layouts/site';
						if (!$this->partial){
							$this->controller->render($this->view, array('get' => $_GET));
						} else {
							$this->controller->renderPartial($this->view, array('get' => $_GET), true, $this -> showScripts);
						}
					} else {
						$this -> controller -> render('//accessDenied');
					}
				}
				//}
			} else {
				$this -> controller -> redirect(Yii::app() -> baseUrl.'/login');
			}
		}
	}
?>