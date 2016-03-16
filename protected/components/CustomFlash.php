<?php
	/*
	 * Попытался реализовать удобный класс для работы с флеш сообщениями с возможностью вывода всех флеш-сообщений одной командой в view.
	 * Проблема в том, что плохо понимаю, что такое static и как правильно обращаться к классу, не создавая его экземпляр.
	 */
	class CustomFlash
	{
		private $_type;
		private $_modelname;
		private $_topic;
		private $_meassage;
		public static $types = Array(
			'success',
			'warning',
			'error',
			'undefined'
		);
		private $_types;
		public function __construct($type = NULL , $modelname = NULL, $topic = NULL, $meassage = NULL, $set = false)
		{
			$this -> _types = self::$types;
			if (isset($type)&&isset($modelname)&&isset($topic)&&isset($meassage))
			{
				if (in_array($type, $this -> _types))
				{
					$temp_arr = array_flip($this -> _types);
					$this -> _type = $temp_arr[$type];
				} elseif (in_array($type, array_keys($this -> _types))) {
					$this -> _type = $type;
				} else {
					$this -> _type = 3;
				}
				$this -> _modelname = $modelname;
				$this -> _topic = $topic;
				$this -> _meassage = $meassage;
			}
			if ($set)
			{
				$this -> setOneself();
			}
			return $this;
		}
		public function giveName()
		{
			$name =  ''.(string)$types[$this -> _type] . (string)$this -> _modelname . (string)$this -> _topic;
			return $name;
		}
		public function giveContent()
		{
			return $this -> _types[$this -> _type].";".$this -> _meassage;
		}
		public function setOneself()
		{
			Yii::app() -> user -> setFlash($this -> giveName(), $this -> giveContent());
		}
		public function decodeContent($content = NULL)
		{
			if (strlen($content) > 0)
			{
				$cont_arr = explode(';', $content, 2);
				$type = trim($cont_arr[0]);
				$text = trim($cont_arr[1]);
				if ($type != $content)
				{
					if (in_array($type, self::$types))
					{
						return CHtml::tag('div', array('class' => 'flash_'.$type), $text);
					} else {
						return CHtml::tag('div', array('class' => 'flash_undefined'), $content);
					}
				} else {
					return CHtml::tag('div', array('class' => 'flash_undefined'), $content);
				}
			}
		}
		public function showAll($flashes = Array(), $show = true)
		{
			if ($show)
			{
				foreach($flashes as $flash)
				{
					echo CustomFlash::decodeContent($flash);
				}
			} else {
				$html_arr = Array();
				foreach($flashes as $flash)
				{
					$html_arr[] = CustomFlash::decodeContent($flash);
				}
				return $html_arr;
			}
		}
		public function showFlashes()
		{
			$flashes = Yii::app() -> user -> getFlashes();
			CustomFlash::showAll($flashes, true);
		}
	}
?>