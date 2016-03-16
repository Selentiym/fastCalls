<?php
//To return to only csv files, rename this class to be Data
class DataFromCsvFile {
	/**
	 * @var string filename - the filename of a csv input file
	 */
	public $filename = '/..//files//input.csv';
	/**
	 * @arg object user - the user to get data for.
	 * @return array - an array containing all information about assignments corresponding to given user.
	 */
	public function giveAllCalls($user) {
		if ($reader = new CsvReader(Yii::app() -> basePath . $this -> filename)) {
			$reader -> separator = ',';
			$header = $reader -> giveHeader();
			$rez = array();
			//Пробегаем по всем записям.
			while($line = $reader -> line()) {
				$call = new Call($line, $header);
				if ($call -> belongsTo($user)) {
					$rez[] = $line;//Возможно, лучше возвращать сам $call, но потом мы считаем, что там не объект, а массив, переделать надо, но позже.
				}
				//Выдаем строку, если она совпадает по телефонному номеру или по номеру листа записи.
				/*if ($user -> checkIIdentificator($line[2])|| $user -> checkJIdentificator($line[3])) {
					$rez[] = $line;
				}*/
			}
			return $rez;
		} else {
			return false;
		}
	}
	/**
	 * @arg string filename - the filename of the csv-encoded file to be imported
	 * @return boolean - whther the import is successful or not.
	 */
	public function ImportDataToDatabase($filename){
		//Если адрес относительный, то меняем его на абсолютный, иначе просто оставляем
		//$filename = strpos($filename, '/') == 0 ? Yii::app() -> basePath . '/..' .$filename : $filename;
		$reader = new CsvReader($filename);
		if ($reader -> file) {
			$reader -> separator = ',';
			$reader -> exportFileEncoding = 'utf-8';
			$header = $reader -> saveHeader();
			//print_r($header);
			new CustomFlash('undefined','Data','ImportDataToDatabase','Проверьте как отображается строка ниже. Если она очень длинная или содержит непонятные символы, то велика вероятность ошибки в кодировке.<br/>'.implode(' - ',$header),true);
			while($line = $reader -> line()) {
				//print_r($line);
				$call = new Call($line,$header);
				//Проверяем, есть ли запись, соответствующая этому звонку, в базе.
				if ($record = $call -> record()) {
					//var_dump($record);
					//echo "found";
					//Если есть, то обновляем ее статус при надобности
					$status = $call -> ClassifyId();
					$owner = $call -> giveOwner();
					//Перезаписываем только если тчо-то реально изменилоось.
					if (($status != $record -> id_call_type)||((!$record -> id_user)&&($record -> id_error != $call -> id_error))) {
						//И ищем хозяина записи, если он еще не найден
						if (!$record -> id_user) {
							//echo "tried to set owner";
							//Если не смогли найти владельца, то не заполняем это поле.
							$record -> id_user = $owner ? $owner -> id : NULL;
						}
						$record -> id_call_type = $status;
						$record -> id_error = $call -> id_error;
						/*echo "<br/>".$status.' - '.$record -> id_call_type;
						echo "save<br/>";*/
						if ($record -> id_error != CallError::model() -> giveText('no_data')) {
							$record -> save();//uncomment
							/*if (!$record -> save()) {
								print_r($record -> getErrors());
							}*/
						}
					}/* else {
						
						echo "<br/>".$status.' - '.$record -> id_call_type;
						echo "not save<br/>";
					}*/
				} else {
					//Если нет записи в БД, то создаем ее.
					$record = new BaseCall();
					$call -> setRecordAttributes($record);
					//echo "not found";
					//Сохраняем только если хоть какая-то информация о звонке задана
					//$record -> save();
					//echo $call -> i.' <- i';
					if ($record -> id_error != CallError::model() -> giveText('no_data')) {
						//echo "try to save";
						if (!$record -> save()) {
							print_r($record -> getErrors());
						}
						
					} /*else {
						var_dump($record);
					}//*/
					/*if (!$record -> save()) {
						print_r($record -> getErrors());
					}*/
					//print_r($record -> getErrors());
					//$call -> setOwnerId();
					//$call -> saveToDatabase();
				}
			}
			return true;
		} else {
			return false;
		}
	}
	public function generateTimeRanges($from){
		$first = getdate($from);
		$start = $first;
		$start['mday'] = $first['mday'] - ($first['mday'] % 7) + 1;
		//$from = mktime($start);
		$from = mktime(0,0,0,$start['mon'], $start['mday'],$start['year']);
		return $from;
	}
	public function giveArrayKeys(){
		$command = Yii::app()->db->createCommand('SELECT MIN(`create_time`) FROM {{user}}');
		$earliest = $command -> queryScalar();
		$time = strtotime($earliest);
		//echo $time;
		//Определили начальный момент.
		$lower = $this -> generateTimeRanges($time,time());
		//echo "<br/>".$lower;
		$to = time();
		$add = 604800;
		$rez = array();
		while ($lower < $to) {
			$rez[] = $lower;
			$lower += $add;
		}
		return $rez;
	}
	public function giveWeekedStats($user){
		
		$command = Yii::app()->db->createCommand('SELECT MIN(`create_time`) FROM {{user}}');
		$earliest = $command -> queryScalar();
		$time = strtotime($earliest);
		//Определили начальный момент.
		$lower = $this -> generateTimeRanges($time);
		$to = time();
		$add = 604800;
			//$calls = $this -> giveAllCalls($user);
		$rez = array();
		while ($lower < $to) {
			$higher = $lower + $add;
			if (strtotime($user -> create_time) > $higher) {
				$rez[] = -1;
			} else {
				$rez[] = $this -> countCallsInRange($lower, $higher, $user);
			}
			$lower = $higher;
		}
		return $rez;
	}
	/**
	 * @arg object user - the user to get data for.
	 * @return array - an array that is keyed by month numbers (1 - 12) and contains Call objects corresponding to the keying month.
	 */
	public function giveMonthedCalls($user){
		$create_date_arr = getdate(strtotime($user -> create_time));
		//print_r($create_date_arr);
		$calls = Data::model() -> giveAllCalls($user);
		//echo count($calls);
		//Начинаем с того месяца, когда был создан пользователь.
		$month = $create_date_arr['mon'];
		$rez[$month] = array();
		foreach ($calls as $call) {
			$call = new Call($call);
			$call_date_arr = $call -> giveDate();
			//Если звонок поступил не в том месяце, что прошлый, то изменяем месяц.
			if ($call_date_arr['mon'] != $month) {
				//echo $call_date_arr['mon']."new month <br/>";
				//Создаем нужное кол-во массивов, соответствующих месяцам.
				for ($i = 1; $i < $call_date_arr['mon'] - $month + 1; $i++) {
					//Если произошел "перескок", т.е. есть пустой месяц(ы), то создаем пустой массив звонков для него.
					$rez[$month + $i] = array();
				}
				$month = $call_date_arr['mon'];
			}
			//Сохраняем объект звонка в массив нужного месяца.
			$rez[$month][] = $call;
		}
		$cur_date_arr = getdate();
		for ($i = 1; $i <= $cur_date_arr['mon'] - $month; $i++){
			$rez[$month + $i] = array();
		}
		//print_r(current($rez));
		/*foreach($rez as $k => $val) {
			echo $k.": ";
			print_r($val);
			echo "<br/>";
		}*/
		ksort($rez);
		return $rez;
	}
	/**
	 * @arg array calls - the array with calls to be classifyed and counted
	 * @return array - an array array(<className> => <number of calls classified to be className>)
	 */
	public function countArray($calls){
		$counted = array(
			'verifyed' => 0,
			'missed' => 0,
			'cancelled' => 0,
			'side' => 0,
			'declined' => 0,
			'assigned' => 0
		);
		foreach($calls as $call) {
			$counted[$call -> Classify()]++;
		}
		return $counted;
	}
	/**
	 * @arg integer from - the lower boundary of a call time in second from 1 Jan 1970
	 * @arg integer to  - the higher boundary of a call time in second from 1 Jan 1970
	 * @arg object user - the user whose calls are to be returned
	 * @return array - an array of call objects that correspond to the specified user and lie between the lower and higher boundary
	 */
	public function giveCallsInRange($from, $to, $user){
		//Можно было бы перебрать то, что вернет giveAllCalls, но будем экономить время, тк, скорее всего, 
		//нам нужно значительно меньше записей, так что отдельная функция.
		if ($reader = new CsvReader(Yii::app() -> basePath . $this -> filename)) {
			$rez = array();
			$header = $reader -> saveHeader();
			//перебираем строки файла, пока не наткнемся на ту, где дата нам подходит.
			while($line = $reader -> line()) {
				//Более долгая, но универсальная версия кода (с созданием объектов)
				$call = new Call($line, $header);
				//echo $call -> giveCallTime();
				//echo "ya tomat";
				//break;
				
				if ($call -> checkLowerBoundary($from)) {
					if (($call -> BelongsTo($user))&&($call -> checkUpperBoundary($to))) {
						$rez[] = $call;
					}
					break;
				}//*/
				/*//Более быстрая, но корявая версия кода
				if (strtotime($line[0].'.2015') >= $from) {
					$call = new Call($line);
					if ($call -> BelongsTo($user)) {
						$rez[] = $call;
					}
					break;
				}*/
			}
			$checkTime = $to + 1;
			//Теперь перебираем и выбираем те, что подходят юзеру.
			while($line = $reader -> line()) {
				$call = new Call($line, $header);
				//Если звонок попадает в интервал после верхней границы, то мы нашли все нужные звонки, заканчиваем перебор.
				if ($call -> checkLowerBoundary($checkTime)) {
					break;
				}
				if ($call -> BelongsTo($user)) {
					$rez[] = $call;
				}
			}
			return $rez;
		} else {
			throw new Exception('File does not open. From Data::giveCallsInRange');
		}
	}
	public function countCallsInRange($from, $to, $user){
		//Можно было бы перебрать то, что вернет giveAllCalls, но будем экономить время, тк, скорее всего, 
		//нам нужно значительно меньше записей, так что отдельная функция.
		if ($reader = new CsvReader(Yii::app() -> basePath . $this -> filename)) {
			$counted = array(
				'common' => 0,
				'verifyed' => 0,
				'missed' => 0,
				'cancelled' => 0,
				'side' => 0,
				'declined' => 0,
				'assigned' => 0
			);
			$header = $reader -> saveHeader();
			//перебираем строки файла, пока не наткнемся на ту, где дата нам подходит.
			while($line = $reader -> line()) {
				//Более долгая, но универсальная версия кода (с созданием объектов)
				$call = new Call($line, $header);
				//echo $call -> giveCallTime();
				//echo "ya tomat";
				//break;
				
				if ($call -> checkLowerBoundary($from)) {
					if (($call -> BelongsTo($user))&&($call -> checkUpperBoundary($to))) {
						$counted[$call -> Classify()] ++;
						$counted['common'] ++;
					}
					break;
				}//*/
				/*//Более быстрая, но корявая версия кода
				if (strtotime($line[0].'.2015') >= $from) {
					$call = new Call($line);
					if ($call -> BelongsTo($user)) {
						$rez[] = $call;
					}
					break;
				}*/
			}
			$checkTime = $to + 1;
			
			//Теперь перебираем и выбираем те, что подходят юзеру.
			while($line = $reader -> line()) {
				$call = new Call($line, $header);
				//Если звонок попадает в интервал после верхней границы, то мы нашли все нужные звонки, заканчиваем перебор.
				if ($call -> checkLowerBoundary($checkTime)) {
					break;
				}
				if ($call -> BelongsTo($user)) {
					$counted[$call -> Classify()] ++;
					$counted['common'] ++;
				}
			}
			return $counted;
		} else {
			throw new Exception('File does not open. From Data::giveCallsInRange');
		}
	}
	public function giveMonthName($n){
		$arr = $this -> giveMonthNamesArray();
		return $arr[$n];
	}
	public function giveMonthNamesArray() {
		return array(
			1 => 'Январь',
			2 => 'Февраль',
			3 => 'Март',
			4 => 'Апрель',
			5 => 'Май',
			6 => 'Июнь',
			7 => 'Июль',
			8 => 'Август',
			9 => 'Сентябрь',
			10 => 'Октябрь',
			11 => 'Ноябрь',
			12 => 'Декабрь'
		);
	}
	public function TransformGivenGetArrayToTimeRange($get, $user){
		$from = (int)$get['from'] ? (int)$get['from'] : strtotime($user -> create_time);
		//$to = (int)$get['to'] ? (int)$get['to'] : time();
		$to = (int)$get['to'];
		return array('from' => $from, 'to' => $to);
	}
	//helper
	public function DateAdd($interval, $number, $date) {
		$date_time_array = getdate($date);
		$hours = $date_time_array['hours'];
		$minutes = $date_time_array['minutes'];
		$seconds = $date_time_array['seconds'];
		$month = $date_time_array['mon'];
		$day = $date_time_array['mday'];
		$year = $date_time_array['year'];

		switch ($interval) {
		
			case 'yyyy':
				$year+=$number;
				break;
			case 'q':
				$year+=($number*3);
				break;
			case 'm':
				$month+=$number;
				break;
			case 'w':
				$day+=$number;
				break;
			case 'ww':
				$day+=($number*7);
				break;
		}
		   $timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
		return $timestamp;
	}
	/**
	 * Import mangoTalker number => user identificator links from file
	 * @arg string filename - the filename of the csv-encoded file to be imported
	 * @return boolean - whther the import is successfu or not.
	 */
	public function ImportNumberFile($filename){
		$reader = new CsvReader($filename);
		//echo 'ok';
		if ($reader -> file) {
			$reader -> separator = ';';
			$header = $reader -> saveHeader();
			//print_r($header);
			$num = 0;
			while($line = $reader -> line()) {
				$num ++;
				//print_r($line);
				$input = new Input($line);
				//var_dump($input);
				if ($input -> from) {
					//Если указанный в файле номер уже занесен в базу, то ищем запись. Если же нет в базе, то создаем.
					if (!$record = $input -> record()) {
						$record = new ClientPhone();
					}
					$record = $input -> setRecordAttributes($record);
					//var_dump($record);
					//Если удачно присвоили атрибуты, то сохраняем.
					if ($record) {
						$record -> save();
						//echo "to save!<br/>";
					}
				}/* else {
					echo "no from attr";
				}*/
			}
			//echo "Обработано {$num} строк.";
			return true;
		} else {
			new CustomFlash('error','Data','ImportNumberFileDidNotOpen','Файл не удалось открыть.',true);
			return false;
		}
	}
	/**
	 * @arg string filename - path to file on the server to input data from
	 * Imports specified doctors to database
	 */
	public function ImportDoctors($filename){
		$reader = new CsvReader($filename);
		if ($reader -> file) {
			$reader -> exportFileEncoding = 'utf-8';
			$header = $reader -> saveHeader();
			$i = 0;
			set_time_limit(1000);
			while($line = $reader -> line()) {
				//Изменить на что-то, относящееся к логину
				if (!User::model() -> findByAttributes(array('username' => $line[3]))) {
					//Создаем промежуточный объект
					$u = new UserFromFile($line, $header);
					//var_dump($u);
					$user = new User();
					$u -> setUserAttributes($user);
					//var_dump($user);
					//echo 'stop';
					//Yii::app() -> end();
					//Раскомментить
					if (!$user -> save()) {
						new CustomFlash('error', 'User','Could not save','Не удалось добавить пользователя с логином '.$user -> username,true);
					}/* else {
						print_r($user -> getErrors());
					}*/
					//CustomFlash::ShowFlashes();
					/*if ($user -> save()) {
						echo '';
						//echo "saved ".$user -> username."<br/>";
					} else {
						echo "could not save ".$user -> username."<br/>";
					}*/
					//Yii::app() -> end();
				} /*else {
					echo "obj found, did not import";
				}*/
				
				//print_r($line);
				//echo "<br/>";
				$i ++;
			}		
		} else {
			new CustomFlash('error','Data','FileDidNotOpen','Файл для импорта не открыт или открыт некорректно.',true);
		}
	}
	public static function model(){
		return new self;
	}
}
?>