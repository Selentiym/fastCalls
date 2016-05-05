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

		/**
		 ****************************************************************************************
		 * @param $files_arr - $_FILES array
		 * Функция, в которой следует описать все операции, производимые с файлами при
		 * создании/изменении модели.
		 */
		public function fileOperations($files_arr) { return false; }
		/**
		 * @param $files_arr - $_FILES array
		 * @param string $imageProp - property that contains image name
		 */
		public function uploadImage($files_arr, $imageProp = 'image') {
			//Если передана нужная картинка, то делаем что-то
			if(!empty($files_arr[get_class($this)]['name'][$imageProp])){
				//Если данной моделью еще не получен id, то сохраняем промежуточный результат.
				if ($this -> FolderKey() == 'id') {
					if (!$this -> id) {
						//unset($this -> $imageProp);
						$this -> save();
					}
				}
				//Создаем личную папку модели, если ее нет
				$images_filePath = $this -> giveImageFolderAbsoluteUrl();
				if (!file_exists($images_filePath))
				{
					mkdir($images_filePath);
				}

				$image_old = $this->$imageProp;
				$this->$imageProp = CUploadedFile::getInstance($this,$imageProp);
				$image_unique_id = substr(md5(uniqid(mt_rand(), true)), 0, 5) . '.' .$this->$imageProp->extensionName;
				$fileName = $images_filePath . $image_unique_id;
				//echo $fileName;
				if ($this->validate()) {
					$this->$imageProp->saveAs($fileName);
					$this->$imageProp = $image_unique_id;
					if (strlen($image_old) > 0) @unlink ($images_filePath. DIRECTORY_SEPARATOR .$image_old);
				}
				else
					$this->$imageProp = $image_old;
			}
		}
		/**
		 ****************************************************************************************
		 */
		public function giveFileFolderAbsoluteUrl($seed = NULL, $fileClass = NULL)
		{
			return Yii::getPathOfAlias('webroot') . $this -> giveFileFolderRelativeUrl($seed, $fileClass, true);
		}
		public function giveFileFolderRelativeUrl($seed = NULL, $fileClass = NULL, $for_abs = false)
		{
			if ((isset($fileClass))&&(strlen($fileClass)>0))
			{
				$d = '/';
				$add = !$for_abs ? Yii::app() -> baseUrl : '';
				//$d = DIRECTORY_SEPARATOR;
				if (!isset($seed))
				{
					$attr_name = $this -> FolderKey();
					if (isset($this -> $attr_name))
					{
						//return $d.'..'.$d. $fileClass .$d. get_class($this) .$d . $this -> $attr_name . $d;
						return $add . $d . $fileClass .$d. get_class($this) .$d . $this -> $attr_name . $d;
						//return $d . $fileClass .$d. get_class($this) .$d . $this -> $attr_name . $d;
					} else {
						return false;
					}
				} else {
					return $add . $d . $fileClass .$d. get_class($this) .$d . $seed . $d;
					//return realpath(Yii::app() -> basePath.'/../').$d.'images'.$d. get_class($this) .$d . $seed . $d;
				}
			} else return false;
		}
		//Функция, которая создает название папки, в которую сохраняются картинки для конкретной модели
		public function giveImageFolderAbsoluteUrl($seed = NULL)
		{
			return $this -> giveFileFolderAbsoluteUrl($seed, 'images');
		}
		public function giveImageFolderRelativeUrl($seed = NULL)
		{
			return $this -> giveFileFolderRelativeUrl($seed, 'images');
		}
		//определяет какой атрибут является названием папки. Необходимо, чтобы он не повторялся, иначе плохо.
		//Переопределить в дочерних классах.
		public function FolderKey()
		{
			return 'id';
		}
		/**
		 * @param string $prop - property which contains the name of the image
		 * @return bool whether the image exists
		 */
		public function checkImage($prop){
			return (file_exists($this -> giveImageFolderAbsoluteUrl(). '/'. $this -> $prop) && (strlen($this -> $prop) > 0));
		}
	}
?>