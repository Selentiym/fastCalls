<?php
	class ModelDeleteAction extends CAction
	{
		/**
		 * @var string model class for action
		 */
		public $modelClass;
		/**
		 * @var string|array redirectUrl - the Url or an array to generate url where the user will be redirected after update
		 */
		public $returnUrl = array('/cabinet');
		/**
		 * @param $arg string model argument to find it by customFind
		 * @throws CHttpException
		 */
		public function run($arg = false)
		{
			if (!Yii::app() -> user -> isGuest) {
				$model = CActiveRecord::model($this -> modelClass) -> findByPk($arg);
				if ($model) {
					if ($model -> checkDeleteAccess($arg)) {
						try {
							$model -> delete();
						} catch (Exception $e) {
							new CustomFlash('error',$this -> modelClass,'Delete'.$arg,'Ошибка при удалении.', true);
						}
					} else {
						$this -> controller -> render('//accessDenied');
						Yii::app() -> end();
					}
				}
				if (!Yii::app()->request->isAjaxRequest) {
					$this -> controller -> redirect($this -> returnUrl);
				}
				//}
			} else {
				if (!Yii::app()->request->isAjaxRequest) {
					$this -> controller -> redirect(Yii::app() -> baseUrl.'/login');
				}
			}
		}
	}
?>