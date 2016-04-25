<?php
	class ModelViewAction extends CAction
	{
		/**
		 * @var string $model class for action
		 */
		public $modelClass;

		/**
		 * @var string $view for render
		 */
		public $view = false;
		/**
		 * @var boolean $partial - whether to user render partial.
		 */
		public $partial = false;
		/**
		 * @var bool $ajax - whether this is an ajax action
		 */
		public $ajax = false;
		/**
		 * @param $arg string model argument to be taken into customFind
		 * @throws CHttpException
		 */
		public function run($arg = false)
		{
			if (!Yii::app() -> user -> isGuest) {
				$model = CActiveRecord::model($this->modelClass)->customFind($arg);
				//echo $arg."<br/>";
				//return;
				//if(Yii::app()->user->checkAccess('createPost')) {
					if(!$model)
						throw new CHttpException(404, "{$this->modelClass} not found");
					//echo $this -> view;
					//var_dump($model);
					$this->controller->layout = '//layouts/site';
					if ($this -> partial) {
						$this->controller->renderPartial($this->view, array('model' => $model, 'get' => $_GET), false, true);
					} else {
						$this->controller->render($this->view, array('model' => $model, 'get' => $_GET));
					}
				//}
			} else {
				$this -> controller -> redirect(Yii::app() -> baseUrl.'/login');
			}
		}
	}
?>