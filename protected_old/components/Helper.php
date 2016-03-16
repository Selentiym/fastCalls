<?php
	/**
	 * makes var_dump look pretty
	 */
	function vardump($var){
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
	};
	/**
	 * @arg associative array attrs
	 * generates parameterstring.
	 * @return string - the string with parameters
	 */
	function MakeRequestString($attrs) {
		$body = '';
		$ind = 0;
		foreach ($attrs as $key => $value) {
			if ((strlen($value) > 0)&&(strlen($key) > 0)) {
				if ($ind > 0) {
					$body .= '&';
				}
				$body .= $key.'='.urlencode($value);
				$ind ++;
			}
		}
		return $body;
	}
?>