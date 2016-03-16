<?php
/**
 * UModel is the customized CActiveRecord class.
 * All model classes for this application should extend from this base class.
 */
	class UModel extends CActiveRecord {
		/**
		 * Function to be used in ViewModel action to have more flexibility
		 * @arg mixed arg - the argument populated from the controller.
		 */
		public function customFind($arg){
			return $this -> model() -> findByPk($arg);
		}
		/**
		 * Returnes the string that consists of $props or just elements delimited by $del.
		 * @arg string del - the delimeter
		 * @arg array array - the array of objects or elements
		 * @arg string/callable prop - the name of the property of an object to be concated
		 * @return string - look higher
		 */
		public function giveStringFromArray($array = array(),$del = ',', $prop = false){
			$rez = '';
			if ((is_array($array))&&(!empty($array))) {
				if ($prop) {
					if (is_callable($prop)) {
						foreach($array as $element) {
							$rez .= $prop($element) . $del . ' ';
						}
					} else {
						foreach($array as $element) {
							$rez .= $element -> $prop . $del . ' ';
						}
					}
				} else {
					foreach($array as $element) {
						$rez .= $element . $del . ' ';
					}
				}
				$rez = substr($rez, 0, strrpos($rez, $del));
			}
			return $rez;
		}
		public function checkCreateAccess(){
			return true;
		}
		public function checkUpdateAccess(){
			return true;
		}
		public function checkDeleteAccess(){
			return true;
		}
		/**
		 * @arg array get - the $_GET variable. 
		 * This function is used to set some initial properties of the model 
		 * that are populated from the url along with modelClass
		 */
		public function readData($get){
			return;
		}
		public function redirectAfterCreate($external){
			return $external;
		}
		public function redirectAfterUpdate($external){
			return $external;
		}
		/**
		 * Sets CustomFlash with information about errors;1
		 */
		public function explainErrors(){
			return;
		}
		/**
		 * Return an array of objects that are specified in the input array
		 * @arg array args - an array that contains something that identifies the models
		 * @return object[CList] - a list containing all selected objects
		 */
		public function giveCollection($args){
			if (!empty($args)) {
				return new CList($this -> findAllByPk($args));
			} else {
				return new CList();
			}
		}
		/**
		 * @arg mixed action - what to do with the collection
		 * @arg object[CList] list - list to be used
		 * @return mixed - modified list or true\false;
		 */
		public function collectionAction($action, $list){
			//if the $action contains a function, apply it. Else return the initial array.
			if (is_callable($action)) {
				foreach($list as $index => $obj) {
					$list[$index] = $action($obj);
				}
			}
			return $list;
		}
	}
?>