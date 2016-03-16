<?php
	class FileUploadAction extends CAction
	{
		/**
		 * @var string returnUrl - where to redirect
		 */
		public $returnUrl = array('site/cabinet');

		/**
		 * @var string|callable serverName - the name of file on the server.
		 */
		public $serverName = '/files/uploaded';
		
		/**
		 * @var boolean|callable report - the status of upload.
		 */
		public $report = true;
		
		/**
		 * @var string formFileName - the name of input form.
		 */
		public $formFileName;
		/**
		 * @var boolean|callable checkAccess function to be called to access page
		 */
		public $checkAccess = true;
		/**
		 * @var string accessDenied - the name of the view to be rendered if access is denied
		 */
		public $accessDenied = '//accessDenied';
		
		public function run(){
			//Если функция проверки доступа существует, то проверяем его.
			if (is_callable($this -> checkAccess)) {
				$name = $this -> checkAccess;
				if (!($name())) {
					$this -> controller -> renderPartial($this -> accessDenied);
					Yii::app() -> end();
				}
			}
			//print_r($_FILES);
			//Если задан массив файла с нужным именем
			if (isset($_FILES[$this -> formFileName])){
				//Если в свойстве имени файла функция, то вызываем ее.
				if (is_callable($this -> serverName)) {
					$fname = $this -> serverName;
					$this -> serverName = $fname($_GET);
				}
				//echo $this -> serverName;
				//И если удалось его переместить
				if (move_uploaded_file($_FILES[$this -> formFileName]['tmp_name'],$this -> serverName)) {
					if (is_callable($this -> report)) {
						$fname = $this -> report;
						$this -> report = $fname($this -> serverName);
					}
					if ($this -> report) {
						new CustomFlash('success','Data','FileOpened','Файл успешно загружен на сервер и обработан.',true);
					} else {
						new CustomFlash('error','Data','FileDidNotOpen','Ошибка при обработке файла.',true);
					}
				} else {
					new CustomFlash('error','Data','FileDidNotOpen','Ошибка при загрузке файла.',true);
				}
				$this -> controller -> redirect($this -> returnUrl);
			}
		}

		
	}
?>