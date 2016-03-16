<?php
	class ModelUpdateAction extends CAction
	{
		/**
		 * @var string model class for action
		 */
		public $modelClass;

		/**
		 * @var string view for render
		 */
		public $view;
		/**
		 * @var string scenario - scenario that is to be assigned to the model
		 */
		public $scenario = false;
		/**
		 * @var string|array redirectUrl - the Url or an array to generate url where the user will be redirected after update
		 */
		public $redirectUrl = array('/cabinet');
		/**
		 * @param $id string model id
		 * @throws CHttpException
		 */
		public function run($arg_update)
		{
			if (!Yii::app() -> user -> isGuest) {
				//Получаем модель, которую нужно обновить
				$model = CActiveRecord::model($this->modelClass)->customFind($arg_update);
				//Если не получилось ее найти, то сообщаем об ошибке
				if(!$model)
					throw new CHttpException(404, "{$this->modelClass} not found");
				//Если у зашедшего достаточно прав, чтобы редактировать модель, то делаем это, иначе выводим сообщение, что юзер не прав.
				if ($model -> checkUpdateAccess()) {
					//Если указан, какой должен быть у модели сценарий, то задаем его.
					if ($this -> scenario) {
						$model -> setScenario($this -> scenario);
					}
					//Сохраняем атрибуты
					if (isset($_POST[$this -> modelClass])) {
						$model -> attributes = $_POST[$this -> modelClass];
						if ($model -> save()) {
							$this->controller->redirect($model -> redirectAfterUpdate($this -> redirectUrl));
						}				
					}
					
					$this->controller->layout = '//layouts/site';
					$this->controller->render($this->view, array('model' => $model));
				} else {
					$this -> controller -> render('//accessDenied');
				}
				//}
			} else {
				$this -> controller -> redirect(Yii::app() -> baseUrl.'/login');
			}
		}
	}
?>