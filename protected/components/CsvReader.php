<?php
class CsvReader {
	/**
	 * @var string encoding of export|import file
	 */
	public $exportFileEncoding = 'windows-1251';
	/**
	 * @var string encoding of file with the code
	 */
	public $codeFileEncoding = 'utf-8';
	/**
	 * @var handle file - the csv file handle
	 */
	public $file;
	/**
	 * @var integer number - the max length of an element
	 */
	public $number = 0;
	/**
	 * @var string separator - the csv separator
	 */
	public $separator = ',';
	public $header = array();
	/**
	 * @arg string filename - the filename of the csv-encoded file.
	 */
	public function __construct($filename){
		@$this -> file = fopen($filename, 'r');
	}
	public function __destruct(){
		if ($this -> file) {
			fclose($this -> file);
		}
	}//*/
	public function saveHeader(){
		$this -> header = $this -> line();
		return $this -> header;
	}
	/**
	 * Tansforms data into correct encoding and writes it to a file.
	 * @arg handle file - handle to a write-opened file
	 * @arg array array - an array that represents one line
	 * @arg string separetor - the csv separator
	 */
	public function my_fputcsv($file, $array, $separator) {
		if ($this -> exportFileEncoding != $this -> codeFileEncoding) {
			foreach ($array as $key => $string) {
				$array[$key] = mb_convert_encoding($string, $this -> exportFileEncoding, $this -> codeFileEncoding);
			}
		}
		fputcsv($file, $array, $separator);
	}
	/**
	 * Takes data into from the file and transforms it into the correct encoding.
	 * @arg handle file - handle to a read-opened file
	 * @arg array array - max length of an element. 0 means that it is not limited.
	 * @arg string separetor - the csv separator
	 */
	public function my_fgetcsv($file, $number, $separator) {
		$array = fgetcsv($file, $number, $separator);
		if (is_array($array))
		{
			if ($this -> exportFileEncoding != $this -> codeFileEncoding) {
				foreach ($array as $key => $string) {
					$array[$key] = mb_convert_encoding($string, $this -> codeFileEncoding, $this -> exportFileEncoding);
				}
			}
			return $array;
		} else {
			return false;
		}
	}
	/**
	 * @return the next line of a csv file or false
	 */
	public function line(){
		if ($this -> file) {
			return $this -> my_fgetcsv($this -> file, $this -> number, $this -> separator);
		} else {
			return false;
		}
	}
}
?>