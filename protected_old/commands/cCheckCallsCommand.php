<?php
	/**
	 * require helper functions file.
	 */
	require_once(Yii::getpathOfAlias('application.components'). DIRECTORY_SEPARATOR . 'Helper.php' );
	
	
	class cCheckCallsCommand extends CConsoleCommand {
		public function actionTelfin() {
			//echo "telfin";
			$telfin = new TelfinApiHelper('t~5Onv4Qkt.rBzqGzD5.jPHS94X5.OzP','I~RPFfV9dq~sGRjjDQn-7hTRHzpn-K5m');
			$handler = function ($tCall) {
				//Если звонок не нашелся, то создаем его.
				if (!(TelfinCall::model() -> findByPk($tCall -> ApiId))) {
					$tCall -> save();
					echo "<br/>";
					vardump($btCall);
					echo "<br/>";
					//Пытаемся собрать данные о звонке.
					$fCall = new FastCall($tCall -> caller, new UserPhone(), $tCall);
					vardump($fCall);
					break;
					//Сохраняем информацию, если изменился статус звонка или если звонок новый.
					//$fCall -> MakeDatabaseChanges();
				}
			};
			$time = new DateTime('1 day ago');
			$telfin -> giveAllCalls($handler,$time -> getTimestamp());
		}
		/**
		 * Это действие должно проверить, все ли телфиновские звонки корректно прицеплены. Если нет, то нужно выдать об этом информацию.
		 */
		public function actionDatabase(){
			echo Yii::getPathOfAlias('application').'/../vendor';
			$criteria = new CDbCriteria;
			$day = 3600*24*3;
			$time = time();
			$from = $time - ($time % $day);//Начало дня.
			$criteria -> addCondition('time >= FROM_UNIXTIME('.$from.')');
			$criteria -> addCondition('id_call IS NULL');
			//Нашли все звонки за сегодняшний день, к которым не прицепились записи
			$tCalls = TelfinCall::model() -> findAll($criteria);
			foreach ($tCalls as $tCall) {
				$tCall -> SearchAgain();
				//break;
			}//*/
		}
	}
?>