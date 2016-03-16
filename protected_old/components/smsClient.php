<?php
	require_once(Yii::getPathOfAlias('webroot.vendor'). DIRECTORY_SEPARATOR .'autoload.php');
	class smsClient extends CApplicationComponent {
		public $authId;
		protected $client;
		/**
		 * @return object[\Zelenin\SmsRu\Api] - the client that can send sms'
		 */
		public function giveClient() {
			if ((!$this -> client)&&($this -> authId)) {
				$this -> client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\ApiIdAuth($this -> authId));
			}
			return $this -> client;
		}
		/**
		 * @arg integer num - the telephone number of the receiver of the sms
		 * @arg string text - the text of the sms
		 * @arg integer time - moment when the sms has to be sent
		 * @return object[smsrespond] - respond object
		 */
		public function sendSms($num, $text, $time = false){
			$sms = new \Zelenin\SmsRu\Entity\Sms($num, $text);
			if (abs($time - time()) < 7 * 24 * 3600) {
				$sms -> time = $time;
			}
			
			//Вместо отправки выдадим извещение об отправке.
			//echo "";
			//new CustomFlash('success','User','smsSend'.$num,'Сообщение успешно отправлено на номер '.$num.' : <div>'.$text.'</div>',true);
			return $this -> giveClient() -> smsSend($sms);
		}
		/**
		 * @retrun float - balance of the client
		 */
		public function balance(){
			$bal = $this -> giveClient() -> myBalance();
			return $bal -> balance;
		}
		/**
		 * @var array report - the response array
		 */
		public function handleReport($report){
			//Получаем массив ответов в виде строк.
			$data = $report['data'];
			foreach ($data as $record) {
				$lines = array_map('trim',explode("\n",$record));
				
				//Если вызов посвещен изменению статуса смс, то обрабатываем его
				if ($lines[0] == "sms_status") {
					
					$smsId = $lines [1];
					$smsStatus = $lines [2];
					//Находим смс, к которой относится этот запрос.
					$sms = Sms::model() -> customFind($smsId);
					if (is_a($sms,'Sms')) {
						//Если смс нашлась, то меняем статус в соответствии с полученной информацией.
						$sms -> changeStatus($smsStatus);
					} elseif ((!$sms)&&($smsStatus == 102)){
						//Если смс не нашлась и статус "в отправке", то создаем новую модель смс.
						/*$sms = new Sms();
						$sms -> ApiId = $smsId;
						$sms -> status = 102;
						$sms -> save();*/
						echo "have to save";
					}
				}
			}
			echo "100";
		}
	}
?>