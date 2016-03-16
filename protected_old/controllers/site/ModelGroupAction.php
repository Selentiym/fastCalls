<?php
	class ModelGroupAction extends CAction
	{
		/**
		 * @var callable action - the action to be taken
		 */
		public $action;
		/**
		 * @var array args - the array of args to be used
		 */
		public $args;
		/**
		 * @var string|array returnUrl - the Url or an array to generate url where the user will be redirected after update
		 */
		public $returnUrl = array('/cabinet');
		/**
		 * @param $arg string model argument to find it by customFind
		 * @throws CHttpException
		 */
		public function run()
		{
			if (is_callable($this -> action)) {
				array_map($this -> action,explode(';',$this->args));
				$this -> controller -> redirect($this->returnUrl);
			}
		}
	}
?>