<?php
/**
 * require helper functions file.
 */
require_once(Yii::getpathOfAlias('application.components'). DIRECTORY_SEPARATOR . 'Helper.php' );
class SiteController extends Controller
{
	/**
	 * How long to wait for the data to be inserted into the google doc file
	 */
	const WAIT_MINS = 0;
	/**
	 * The length of an IVR number
	 */
	const LENGTH = 2;
	/**
	 * Declares class-based actions.
	 */
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/site.php';
	public $defaultAction = 'login';
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
			'statistics'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//statistics/numbers'
			),
			'viewMenu'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'partial' => true,
				'showScripts' => true,
				'view' => '//cabinet/menu'
			),
			'smsList'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//sms/list'
			),
			'reviewstat'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//review/review_stat'
			),
			'phonestat'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//phone_stat'
			),
			
			'allstat'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//adminStat'
			),
			'paystat'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//paystat'
			),
			'userlist'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//users/_info_all_users'
			),
			'activeuserlist'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//users/_userlistActive'
			),
			'addData'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//addToDataBase'
			),
			'entries'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//entries'
			),
			'errors'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//errors'
			),
			'telfinErrors'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//errors_telfin'
			),
			'chess'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//chess'
			),
			'userSmsForm'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//users/sendSms'
			),
			'userPropertyForm'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//users/addProperty'
			),
			'userActionForm'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//users/addAction'
			),
			'userCollection'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//users/collection'
			),
			'group'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//cabinet/group'
			),
			
			'cabinet' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//LK'
			),
			'patients' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//patients'
			),
			'printDirections' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//print/print',
				'partial' => true
			),
			'stat' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//stat'
			),
			'allCalls' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//allCalls'
			),
			'showReviews' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//review/list'
			),
			'payStatistics' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//users/payStatistics'
			),

			
			'userSendSms' => array(
				'class' => 'application.controllers.site.ModelCollectionAction',
				'modelClass' => 'User',
				'args' => $_POST["userGroup"],
				'action' => 'sendSms',
				'addArgs' => $_POST["smsText"],
				'redirect' => Yii::app() -> baseUrl.'/activeuserlist'
			),
			'userAddProperty' => array(
				'class' => 'application.controllers.site.ModelCollectionAction',
				'modelClass' => 'User',
				'args' => $_POST["userGroup"],
				'action' => 'addProperty',
				'preaction' => function($post, $users){
					$rez = $post['options'];
					if ((!((int)$post['options']))&&($post['options'])) {
						$opt = new UserOption();
						$opt -> name = $post['options'];
						if ($opt -> save()){
							$rez = $opt;
						} else {echo "not saved";
							var_dump($opt -> getErrors());
						}
					} else {
						$opt = UserOption::model() -> findByPk($post['options']);
						if ($opt) {
							$rez = $opt;
						}
					}
					return $rez;
				},
				'addArgs' => $_POST,
				'close' => true
			),
			'userAddAction' => array(
				'class' => 'application.controllers.site.ModelCollectionAction',
				'modelClass' => 'User',
				'args' => $_POST["userGroup"],
				'action' => 'addAction',
				'addArgs' => $_POST,
				'close' => true,
				'checkAccess' => Yii::app() -> user -> checkAccess('createAction')
			),
			
			'createUser' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'User',
				'view' => '//createUser',
				'scenario' => 'create'
			),
			'UserAddressCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'UserAddress',
				'view' => '//UserAddress/create',
				'scenario' => 'create'
			),
			'TestAddressCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'TestAddress',
				'view' => '//TestAddress/create',
				'scenario' => 'create'
			),
			'PhoneCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'UserPhone',
				'view' => '//PhoneCreate',
				'scenario' => 'create',
				'redirectUrl' => 'data'
			),
			'ReviewCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'Review',
				'view' => '//review/review_create',
				'scenario' => 'create'
			),
			'MentorCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'UserMentor',
				'view' => '//mentor/create_mentor'
			),
			'OptionCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'UserOption',
				'view' => '//option/create_option'
			),
			'PatientCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'Patient',
				'view' => '//patient/create_patient'
			),
			
			
			
			'updateUser' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'User',
				'view' => '//UpdateUser',
				'scenario' => 'updateByAdmins'
			),
			'settings' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'Setting',
				//'redirect' => '/data',
				'view' => '//_form_settings'
			),
			'UserAddressUpdate' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'UserAddress',
				'view' => '//UserAddress/update',
				'scenario' => 'update'
			),
			'TestAddressUpdate' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'TestAddress',
				'view' => '//TestAddress/update',
				'scenario' => 'update'
			),
			'updateSelf' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'User',
				'view' => '//SelfUpdate',
				'scenario' => 'SelfUpdate',
				'redirectUrl' => Yii::app() -> baseUrl . '/cabinet'
			),
			'PhoneUpdate' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'UserPhone',
				'view' => '//PhoneUpdate',
				'scenario' => 'update'
			),
			'ReviewUpdate' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'Review',
				'view' => '//review/_form_review',
				'scenario' => 'update'
			),
			'MentorUpdate' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'UserMentor',
				'view' => '//mentor/update_mentor'
			),
			'OptionUpdate' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'UserOption',
				'view' => '//option/update_option'
			),
			
			
			'PhoneDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'UserPhone'
			),
			'UserDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'User'
			),
			'UserAddressDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'UserAddress'
			),
			'TestAddressDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'TestAddress'
			),
			'ReviewDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'Review'
			),
			'CallDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'BaseCall',
				'returnUrl' => Yii::app() -> baseUrl.'/errors'
			),
			'TelfinCallDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'TelfinCall',
				'returnUrl' => Yii::app() -> baseUrl.'/errors/telfin'
			),
			'MentorDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'UserMentor'
			),
			'info' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//infoPage'
			),
			
			
			'statUpload' => array(
				'class' => 'application.controllers.site.FileUploadAction',
				'returnUrl' => array('site/settings'),
				'report' => true,
				'serverName' => Yii::app() -> basePath . '/../images/stat.jpg',
				'formFileName' => 'statImageUpload',
				'validate' => function ($file) {
					//print_r($file);
					return true;
					//return $file
				},
				'checkAccess' => function () {
					return Yii::app() -> user -> checkAccess('admin');
				}
			),
			'clientphonesupload' => array(
				'class' => 'application.controllers.site.FileUploadAction',
				'returnUrl' => array('site/data'),
				'report' => function ($name) {
					return Data::model() -> ImportNumberFile($name);
				},
				'serverName' => Yii::app() -> basePath . '/../files/inputClient.csv',
				'formFileName' => 'ClientPhoneUpload',
				'checkAccess' => function () {
					return Yii::app() -> user -> checkAccess('admin');
				}
			),
			'doctorsImport' => array(
				'class' => 'application.controllers.site.FileUploadAction',
				'returnUrl' => array('site/cabinet'),
				'report' => function ($name) {
					return DataFromCsvFile::model() -> ImportDoctors($name);
				},
				'serverName' => Yii::app() -> basePath . '/../files/doctors.csv',
				'formFileName' => 'DoctorsFile',
				'checkAccess' => function () {
					return Yii::app() -> user -> checkAccess('admin');
				}
			),
			'deleteGroup'=>array(
				'class'=>'application.controllers.site.ModelGroupAction',
				'action' => function($id){
					$call = BaseCall::model() -> findByPk($id);
					$call -> delete();
				},
				'args' => $_GET["group"],
				'returnUrl' => Yii::app() -> baseUrl.'/errors'
			),
			'deleteTelfinGroup'=>array(
				'class'=>'application.controllers.site.ModelGroupAction',
				'action' => function($ApiId){
					$call = TelfinCall::model() -> findByPk($ApiId);
					if (is_a($call,'TelfinCall')) {
						$call -> delete();
					}
				},
				'args' => $_GET["group"],
				'returnUrl' => Yii::app() -> baseUrl.'/errors/telfin'
			),
			'telfinSearchAgain' => array(
				'class'=>'application.controllers.site.ClassMethodAction',
				'modelClass' => 'TelfinCall',
				'method' => 'SearchAgain',
				'access' => function(){return Yii::app() -> user -> checkAccess('admin');},
				'redirectUrl' => Yii::app() -> baseUrl . '/errors/telfin',
				'args' => true
			),
			'unlinkCall' => array(
				'class'=>'application.controllers.site.ClassMethodAction',
				'modelClass' => 'BaseCall',
				'method' => 'unlink',
				'access' => function(){return Yii::app() -> user -> checkAccess('admin');},
				'redirectUrl' => Yii::app() -> baseUrl . '/',
				'args' => false
			),
			'usersByMD' => array(
				'class' => 'application.controllers.site.ClassMethodAction',
				'modelClass' => 'User',
				'method' => 'giveChildrenIds',
				'ajax' => true,
				'args' => false,
				'scenario' => 'searchById',
				'access' => function(){return Yii::app() -> user -> checkAccess('admin');},
			),
			'allMDs' => array(
				'class' => 'application.controllers.site.ClassMethodAction',
				'modelClass' => 'User',
				'method' => 'dumpMDs',
				'ajax' => true,
				'scenario' => 'giveModel',
				'access' => function(){return Yii::app() -> user -> checkAccess('admin');},
			),
			'usersByOption' => array(
				'class' => 'application.controllers.site.ClassMethodAction',
				'modelClass' => 'UserOption',
				'method' => 'dumpForJS',
				'ajax' => true,
				'scenario' => 'searchById',
				'access' => function(){return Yii::app() -> user -> checkAccess('admin');},
			),
			'usersBySpeciality' => array(
				'class' => 'application.controllers.site.ClassMethodAction',
				'modelClass' => 'UserSpeciality',
				'method' => 'dumpForJS',
				'ajax' => true,
				'scenario' => 'searchById',
				'access' => function(){return Yii::app() -> user -> checkAccess('admin');},
			),
			'allUsers' => array(
				'class' => 'application.controllers.site.ClassMethodAction',
				'modelClass' => 'User',
				'method' => 'AllUsersIdsDumpForJS',
				'ajax' => true,
				'scenario' => 'giveModel',
				'access' => function(){return Yii::app() -> user -> checkAccess('admin');},
			),
			'basicUserData' => array(
				'class' => 'application.controllers.site.ClassMethodAction',
				'modelClass' => 'User',
				'method' => 'basicDataDumpForJs',
				'ajax' => true,
				//'scenario' => 'searchById',
				'access' => function(){return Yii::app() -> user -> checkAccess('admin');},
			),
			'userStatDumpJS' => array(
				'class' => 'application.controllers.site.ClassMethodAction',
				'modelClass' => 'User',
				'method' => 'statDumpJS',
				'ajax' => true,
				//'scenario' => 'searchById',
				'access' => function(){return Yii::app() -> user -> checkAccess('admin');},
			),
			'cellHeaders' => array(
				'class' => 'application.controllers.site.ClassMethodAction',
				'modelClass' => 'User',
				'method' => 'cellHeaders',
				'ajax' => true,
				//'scenario' => 'searchById',
				'access' => function(){return Yii::app() -> user -> checkAccess('admin');},
			),
			'preciseStat' => array(
				'class' => 'application.controllers.site.ClassMethodAction',
				'modelClass' => 'User',
				'method' => 'preciseList',
				'ajax' => true,
				//'scenario' => 'searchById',
				'access' => function(){return Yii::app() -> user -> checkAccess('admin');},
			),
		);
	}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;
		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='loginForm')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if (!Yii::app() -> user -> isGuest) {
			$this -> redirect('site/cabinet');
		}
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->baseUrl.'/cabinet');
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	//public function actionAssignCall($call_id,$user_id){
	public function actionAssignCall(){
		if (Yii::app() -> user -> checkAccess('admin')) {
			$call = BaseCall::model() -> findByPk($_POST['id_call']);
			//$call = BaseCall::model() -> findByPk($call_id);
			if (($call)&&(!$call -> id_user)) {
				if (!$_POST['id_user']) {
					$_POST['id_user'] = array();
				}
				//Если явно указан пользователь, которому присвоить запись, то делаем это
				if ((int)current($_POST['id_user'])) {
					$call -> id_user = (int)current($_POST['id_user']);
				} else {
					//Иначе присваиваем телефон и пытаемся найти владельца
					if ($_POST["id_phone"]) {
						$id = $_POST["id_phone"];
						$phone = UserPhone::model() -> findByPk($id);
						//Ищем телефон
						if (is_a($phone, 'UserPhone')) {
							//Если телефон выдан только одному юзеру, то ок
							if (count($phone -> regular_users) == 1) {
								$user = current($phone -> regular_users);
								$call -> id_user = $user -> id;
								if ($call -> save()) {
									new CustomFlash('success','BaseCall','Ok','Присвоение телефона и пользователя успешно!',true);
								} else {
									new CustomFlash('error','BaseCall','Save','Ошибка при сохранении.',true);
								}
							} else {
								new CustomFlash('error','BaseCall','PhoneNotUnique','Выбранный телефон принадлежит более, чем одному пользователю или не принадлежит ни одному.',true);
							}
						} else {
							new CustomFlash('error','BaseCall','PhoneNotFound','Не найден номер телефона. Попробуйте еще раз.',true);
						}
					}
				}
				if (($_POST['id_user'])||($_POST['id_address'])) {
					if ($call -> id_user) {
						if ($call -> save()) {
						//if (false) {
							new CustomFlash('success','BaseCall','UserAssignSucc','Пользователь успешно присвоен',true);
						} else {
							new CustomFlash('error','BaseCall','SaveError','Ошибка при сохранении результата',true);
						}
					}
				} else {
					new CustomFlash('error','BaseCall','EmptyInput','Задайте параметры',true);
				}
			} else {
				new CustomFlash('error','BaseCall','UserAssignErr','Неуспешное присвоение пользователя: не найдена запись или у нее уже выбран пользователь',true);
			}
			//echo "123";
			//CustomFlash::ShowFlashes();
			$this -> redirect(Yii::app() -> baseUrl.'/errors');
		} else {
			$this -> renderPartial('//accessDenied');
		}
	}
	public function actionUploadFile(){
		if (Yii::app() -> user -> checkAccess('admin')) {
			//print_r($_FILES);
			if ((isset($_FILES['inputFile']))&&(isset($_POST["year"]))){
				if ($_POST["year"] > 2013) {
					$set = Setting::model() -> find();
					$set -> year = $_POST["year"];
					@$set -> save();
					$name = Yii::app() -> basePath.'/../files/temp_file.csv';
					if (move_uploaded_file($_FILES['inputFile']['tmp_name'],$name)) {
						if (Data::model() -> ImportDataToDatabase($name)) {
							new CustomFlash('success','Data','FileOpened','Файл успешно загружен на сервер и обработан.',true);
						} else {
							new CustomFlash('error','Data','FileDidNotOpen','Ошибка при загрузке файла.',true);
						}
					} else {
						new CustomFlash('error','Data','FileDidNotOpen','Ошибка при загрузке файла.',true);
					}
				} else {
					new CustomFlash('error','Data','IncorrectYear','Год должен быть выше 2013',true);
				}
			} else {
				if ((isset($_POST))||(isset($_GET))) {
					new CustomFlash('error','Data','NotEnoughData','Заполнены не все поля',true);
				}
			}
			$this -> redirect(Yii::app() -> baseUrl.'/data');
			//$this -> controller -> redirect('//site/cabinet');
		} else {
			$this -> render('//accessDenied');
		}
	}

	/**
	 * Send an sms to user with text from $_POST['text']. For ajax use only!
	 * @param $arg - username of the person to be sent sms to
	 */
	/*public function actionSendSms($arg) {
		if ((Yii::app()->request->isAjaxRequest)&&(Yii::app() -> user -> checkAccess('admin'))) {
			$user = User::model() -> CustomFind($arg);
			if (!$user) {
				echo "Не удалось найти нужного пользователя!";
				Yii::app() -> end;
			}
			if ($text = $_POST['text']) {
				$text = SmsPattern::prepareText($user, $text);
				//Yii::app() -> end();
				if ($rez = $user -> sendSms($text)) {
					echo $rez['delayed']."\n";
					echo "Текст: ".$rez['text']."\n";
					echo "Номер: ".$rez['number'];
				}
			}
			//var_dump($_POST);
		}else {
			echo "Это действие доступно только для AJAX запросов!";
		}
	}*/

	public function actionSendSms(){
		require_once(Yii::getPathOfAlias('webroot.vendor').'\autoload.php');
		//require_once(Yii::getPathOfAlias('webroot.vendor.zelenin.smsru.Api').'.php');
		//$client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\ApiIdAuth('1fb53119-4130-5524-4950-c8c74a627908'));
		$client = Yii::app() -> sms -> giveClient();
		var_dump($client);
		//var_dump($client -> myBalance());
		$sms = new \Zelenin\SmsRu\Entity\Sms('79523660187', 'проверка смс');
		//$sms = new \Zelenin\SmsRu\Entity\Sms('89523660187', 'Смс с сайта через класс Api. Дошла ли?');
		//var_dump($sms);
		$smsResp = $client->smsSend($sms);
		vardump($smsResp);
		//$smsId = current($smsResp -> ids);
		//var_dump($client -> smsStatus('201552-100000436'));
		//echo $smsId;
		//$bal = $client -> myBalance();
		//echo $bal -> balance;
		//$this -> renderPartial('//sms/form');
	}

	/*public function actiongiveImage($addr){
		
	}*/
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	/*public function actionDownloadcompanysList(){
		$reader = new CsvReader(Yii::app() -> basePath.'/../files/tests_companies.csv');
		echo Yii::app() -> basePath.'/files/tests_companies.csv';
		if ($reader -> file) {
			$reader -> saveHeader();
			$reader -> separator = ';';
			$i = 0;
			while($line = $reader -> line()) {
				$i++;
				$name = $line[0];
				$addr = $line[1];
				if (!($record = TestAddress::model() -> findByAttributes(array('name' => $name)))) {
					$record = new TestAddress;
				}
				$record -> name = $name;
				$record -> address = $addr;
				$record -> save();
			}
			echo "{$i} lines was executed";
		} else {
			echo "File not opened.";
		}
	}*/
	/* Do not uncomment! The following functions are for construction only. They should have been used feom a console but it didn't work out for some reason. */
	/*public function actionPss(){
		echo CPasswordHelper::hashPassword('admin');
	}*/
	// admin <=> $2a$13$70aJ2YIqjkbCFF3l3bS3r.7OVYaF8t.lgsQExJi0k6FGageWMFxfi
	/*public function actionAddRules() {
		//print_r(Yii::app() -> getAuthManager());
		$auth=Yii::app()->authManager;
		$auth -> clearAll();
		
		$isParent = 'return Yii::app() -> user -> getId()==$params["user"] -> id_parent';
		$bizRule = 'return Yii::app()->user->getId()==$params["user"]->id;';
		
		$auth -> createOperation('viewUserCabinet', 'view some user\'s cabinet.');
		$auth -> createOperation('viewChildUserCabinet', 'view your child user\'s cabinet.',$isParent);
		$auth -> createOperation('createDoctor','create an ordinary doctor');
		$auth -> createOperation('updateDoctor','update an ordinary doctor');
		$auth -> createOperation('updateChildDoctor','update an ordinary doctor that is your child',$isParent);
		//Вторым параметром в checkAccess передаем массив array('user' => <UserWhoseCabinetIsBeingDisplayedObject>)
		
		$auth -> createOperation('viewOwnUserCabinet', 'view your own cabinet.', $bizRule);
		$auth -> createOperation('updateOwnUser', 'update user\'s own profile.', $bizRule);
		//MD - main Doctor
		$auth -> createOperation('viewMDCabinet', 'view some MD\'s cabinet.');
		$auth -> createOperation('createMainDoc', 'create a main doctor.');
		$auth -> createOperation('updateMainDoc', 'update a main doctor.');
		
		$admin = $auth -> createRole('admin');
		$doctor = $auth -> createRole('doctor');
		$MD = $auth -> createRole('mainDoc');
		
		$doctor -> addChild('viewOwnUserCabinet');
		$doctor -> addChild('updateOwnUser');
		
		$MD -> addChild('viewChildUserCabinet');
		$MD -> addChild('updateChildDoctor');
		$MD -> addChild('createDoctor');
		
		$admin -> addChild('viewMDCabinet');
		$admin -> addChild('viewUserCabinet');
		$admin -> addChild('updateDoctor');
		$admin -> addChild('createMainDoc');
		
		$MD -> addChild('doctor');
		
		$admin -> addChild('mainDoc');
		
		$this -> AddAdminUser();
		//$auth -> createOperation('viewOwnUserCabinet', 'view your own cabinet.', $bizRule);
	}
 
	public function AddAdminUser() {
		$auth=Yii::app()->authManager;
		$admin = User::model() -> findByAttributes(array('username' => 'admin'));
		$doctor = User::model() -> findByAttributes(array('username' => 'doctor'));
		$mainDoc = User::model() -> findByAttributes(array('username' => 'maindoc'));
		$auth->assign('admin',  $admin -> id);
		$auth->assign('mainDoc',  $mainDoc -> id);
		$auth->assign('doctor',  $doctor -> id);
	}//*/
	public function actionCheckApi(){
		$api = new GoogleDocApiHelper();
		if ($api -> success) {
			$api -> setWorkArea('Ремонт СПб', 'May 2016');
			$data = $api -> giveData ();
			foreach($data -> getEntries() as $d){
				var_dump($d);
				echo "<br/><br/>";
			}
		}
	}
	public function actionTryFastCall(){
		$call = new FastCall();
		
	}

	/**
	 * Добавляет $toAdd звонков из $month месяца
	 * Используется стандартный метод добавления через
	 * actionTelfinHangup
	 */
	public function actionAddTestData (){
		function createReciever(){
			static $users = array();
			static $count = 0;
			static $recursion = 0;
			//Задаем список доступных полчаетелей
			if (empty($users)) {
				$criteria = new CDbCriteria();
				//Выбираем всех обычных партнеров
				$criteria -> compare('id_type',3);
				//Выбираем только среди определенных ремпредов!
				$criteria -> addInCondition('id_parent',array(574,575,576));
				$users = User::model() -> findAll($criteria);
				$count = count($users);
			}
			$key = mt_rand(0, $count - 1);
			$user = $users[$key];
			//todo remove!
			$user = User::model() -> findByPk(593);
			if ($phone = current($user -> phones)){
				$rez = array(
						"CallAPIID" => substr(md5(md5(time()).mt_rand(0,10000)),0,20)
				);
				$rez["CalledDID"] = $phone -> number;
				//Если номер содержит ivr, то создаем tCall
				if ($phone -> ivr) {
					$tCall = new TelfinCall();
					$tCall -> id_phone = $phone -> id;
					$tCall -> ApiId = $rez['CallAPIID'];
					if (!$tCall -> save()) {
						$recursion ++;
						createReciever();
					} else {
						var_dump($tCall -> getErrors());
					}
				}
				$recursion = 0;
			} else {
				$recursion ++;
				if ($recursion > 100){
					return array();
				} else {
					return createReciever();
				}
			}
			return $rez;
		}
		if (Yii::app() -> user -> checkAccess("admin")) {
			//Выбрали месяц, из которого тянуть звонки.
			$month = "May";
			$toAdd = 1;
			$api = new GoogleDocApiHelper();
			if ($api -> success) {
				$addr = 'http://fastcalls/public_html/site/telfinHangup';
				//$addr = Yii::app() -> baseUrl.'/telfinHangup';
				$api -> setWorkArea('Ремонт СПб', $month.' 2016');
				$data = $api -> giveData ();
				$entr = $data -> getEntries();
				if (!$entr) {
					return;
				}
				$mh = curl_multi_init();
				//var_dump($entr);
				$size = count($entr);
				$resources = array();
				$toJSON = array();
				for ($i = 0; $i < $toAdd; $i++) {
					$num = mt_rand(0, $size - 1);
					$e = $entr[$num] -> getValues();
					//var_dump($e -> getValues());
					//break;
					$curl = curl_init();

					curl_setopt($curl, CURLOPT_URL, $addr);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
					curl_setopt($curl, CURLOPT_POST, true);
					$called = createReciever();
					$request = array(
							'CallStatus' => 'ANSWER',
							'CallerIDNum' => $e['mangotalkerномер'],
							'CalledDID' => $called['CalledDID'],
							'CallAPIID' => $called['CallAPIID']
					);
					$toJSON[]=$request;
					curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($request));
					/*$_REQUEST = $request;
					$this -> actionTelfinHangup();*/


					curl_multi_add_handle($mh, $curl);
					//$out = curl_exec($curl);
					$resources[] = $curl;
					//echo $out;
				}
				$t = json_encode($toJSON);
				$this -> render('//statistics/testData',array('t' => $t));
				//echo json_encode($toJSON);
				/*$active = null;
				//execute the handles
				do {
					$mrc = curl_multi_exec($mh, $active);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);

				while ($active && $mrc == CURLM_OK) {
					if (curl_multi_select($mh) != -1) {
						do {
							$mrc = curl_multi_exec($mh, $active);
						} while ($mrc == CURLM_CALL_MULTI_PERFORM);
					}
				}*/
				//удаляем все запросы
				foreach ($resources as $curl){
					curl_multi_remove_handle($mh, $curl);
				}
				curl_multi_close($mh);
				/*foreach($data -> getEntries() as $d){
					var_dump($d);
					echo "<br/><br/>";
				}*/
			}
		} else {
			echo "Not allowed!";
		}
	}
	/**
	 * Script to process information from telfin api.
	 * On hangup telfin sends here information about the call.
	 * This script searches the google doc file for entries of such number
	 * and if finds one makes a record in the database.
	 */
	public function actionTelfinHangup(){
		sleep(60 * self::WAIT_MINS);
		//sleep(60*3);
		ob_start();
		//echo "start";
		$telfin = new TelfinApiHelper();
		/*$call = $telfin -> giveCallObject(array(
			'CallStatus' => 'ANSWER',
			'CallerIDNum' => '79521234561',
			'CalledDID' => '78123099736',
			'CallAPIID' => '2kapjk4gntjhbrngeihl'
		));//*/
		//vardump($_REQUEST);
		echo "ApiId: ".$_REQUEST['CallAPIID'].'<br/>';
		echo "CallerIdNum: ".$_REQUEST["CallerIDNum"]."<br/>";
		echo "CalledDID: ".$_REQUEST["CalledDID"]."<br/>";
		echo "CallSatus: ".$_REQUEST["CallStatus"]."<br/>";
		$call = $telfin -> giveCallObject($_REQUEST);//*/
		//vardump($call -> bCall);
		if (is_a($call, 'FastCall')) {
			//Сохраняем информацию, если изменился статус звонка или если звонок новый.
			$call -> MakeDatabaseChanges();
		}
		$out = ob_get_contents();
		ob_end_clean();
		$dateStr = date("_Y_M_d");
		$f = fopen(Yii::getPathOfAlias('application'). DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . "log{$dateStr}.txt",'a+');
		fwrite($f, 'There was a call '.date('j.m H:i:s').':<br/>'.PHP_EOL);
		fwrite($f, $out.'<br/><br/>'.PHP_EOL);
		fclose($f);
	}
	/**
	 * Used to obtain fast access to log.
	 */
	public function actionViewLog(){
		if (Yii::app() -> user -> checkAccess('admin')) {
			$dateStr = date("_Y_M_d");
			$filename = Yii::getPathOfAlias('application'). DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . "log{$dateStr}.txt";
			if (file_exists($filename)) {
				$read = file_get_contents($filename);
			} else {
				echo "No log for today yet.";
			}
			echo $read;
		}
	}
	/**
	 * Handles the CallInteractive request.
	 */
	public function actionDoctorNum(){
		ob_start();
		//code here! All output will be saved to log.txt
		echo "interactive:<br/>";
		//vardump($_REQUEST);
		echo "ApiId: ".$_REQUEST['CallAPIID'].'<br/>';
		
		$telfin = new TelfinApiHelper();
		$phone = $telfin -> givePhoneObject($_REQUEST, true);
		
		//Если 1, то партнер найден, если 0, то нет.
		$correctNum = $phone ? 1 : 0;
		if ($correctNum) {
			echo "PhoneId: ".$phone -> id.'<br/>';
		} else {
			echo "Phone not found<br/>";
		}
		
		echo "end of interactive<br/>";
		//end!
		$out = ob_get_contents();
		ob_end_clean();
		$dateStr = date("_Y_M_d");
		$f = fopen(Yii::getPathOfAlias('application'). DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . "log{$dateStr}.txt",'a+');
		fwrite($f, 'There was an interactive request '.date('j.m H:i:s').':<br/>'.PHP_EOL);
		fwrite($f, $out.'<br/>'.PHP_EOL);
		fclose($f);
		
		//Записали информацию в файл, теперь отправляем ответ серверу.
		echo '<?xml version="1.0" encoding="UTF-8"?><Response>';
		echo '<SetVar name="correctNum">'.$correctNum.'</SetVar>';
		echo '</Response>';
	}
	/**
	 * Validates the ivr. IVR must contain LENGTH digits.
	 */
	public function actionvalidatedoctornum(){
		//ob_start();
		
		
		$validated = preg_match('/^\d{'.self::LENGTH.'}$/',$_REQUEST["ivr"]) ? 1 : 0;
		
		
		/*$out = ob_get_contents();
		ob_end_clean();
		$f = fopen(Yii::getPathOfAlias('application'). DIRECTORY_SEPARATOR .'log.txt','a+');
		fwrite($f, 'There was an interactive request '.date('j.m H:i:s').':<br/>'.PHP_EOL);
		fwrite($f, $out.'<br/>'.PHP_EOL);
		fclose($f);*/
		
		
		echo '<?xml version="1.0" encoding="UTF-8"?><Response>';
		echo '<SetVar name="validated">'.$validated.'</SetVar>';
		echo '</Response>';
	}
	/**
	 * Stores information to a file store.txt
	 */
	public function actionStoreData(){
		ob_start();
		vardump($_REQUEST);
		$out = ob_get_contents();
		ob_end_clean();
		$f = fopen(Yii::getPathOfAlias('application'). DIRECTORY_SEPARATOR .'store.txt','a+');
		fwrite($f, date('j.m H:i:s').':<br/>'.PHP_EOL);
		fwrite($f, $out.'<br/>'.PHP_EOL);
		fclose($f);
	}
	public function actionCheckTelfinApi(){
		$telfin = new TelfinApiHelper('t~5Onv4Qkt.rBzqGzD5.jPHS94X5.OzP','I~RPFfV9dq~sGRjjDQn-7hTRHzpn-K5m');
		echo "<pre>";
		$telfin -> giveAllCalls();
		echo "</pre>";
	}
	/**
	 * Renews call types according to the SA field
	 */
	public function actionRenewStatus(){
		$months = array (1 => 'January',2 => 'February',3 => 'March',4 => 'April',5 => 'May',6 => 'June',7 => 'July',8 => 'August',9 => 'September',10 => 'October',11 => 'November',12 => 'December');
		$criteria = new CDbCriteria;
		$criteria -> compare('id_call_type',CallType::model() -> getNumber('assigned'));
		$time = new DateTime('2 months ago');
		$criteria -> addCondition('date > FROM_UNIXTIME('.$time -> getTimestamp().')');
		//Находим все звонки, статус которых "записан" и они не старше 2 месяцев.
		$assigned = BaseCall::model() -> findAll($criteria);
		if (count($assigned) > 0) {
			$api = new GoogleDocApiHelper();
			$types = CallType::model() -> findAll();
			foreach($types as $t) {
				$temp = array('count' => 0, 'name' => $t -> name);
				$changed[$t -> id] = $temp;
			}
			unset($temp);
			foreach($assigned as $bCall){
				$time = strtotime($bCall -> date);
				//Массив даты, на которую запись.
				$arr = getdate($time);
				$month = $arr['mon'];
				$year = $arr['year'];
				//День, месяц поступления звонка.
				$dateString_arr = array_filter(explode('.',$bCall -> dateString));
				$alterMon = end($dateString_arr);
				//В нормальной ситауции месяц звонка меньше месяца записи, но если не так, то звонок был в предыдущем году.
				if ($alterMon > $month) {
					$year --;
				}
				//Получили название листа гугл дока.
				$work = $months[$month].' '.$year;
				//Количество параметров, по которым происходит посик.
				$params = 1;
				$queryString = "дата = {$bCall -> dateString}";
				if ($bCall -> repair_type) {
					$queryString .= ' and типописаниеремонта="'.$bCall -> repair_type.'"';
					$params ++;
				}
				if ($bCall -> company) {
					$queryString .= ' and компания = "'.$bCall -> company.'"';
					$params ++;
				}
				if ($bCall -> fio) {
					$queryString .= ' and фио = "'.$bCall -> fio.'"';
					$params ++;
				}
				if ($bCall -> birth) {
					$queryString .= ' and датарождения = "'.$bCall -> birth.'"';
					$params ++;
				}
				$qArr = array('sq' => $queryString);
				//Ищем запись.
				$entry = $api -> searchEverywhere($qArr, $work);

				if ($entry) {
					$entry = $entry -> getValues();
					$tempCall = new Call(array());
					$tempCall -> report = $entry['отчетпозвонку'];
					$tempCall -> State = $entry['sa'];
					$newId = $tempCall -> ClassifyId();
					if ($newId != $bCall -> id_call_type) {
						$bCall -> id_call_type = $newId;
						$bCall -> save();
						//Наращиваем счетчик увеличенных статусов.
						$changed[$bCall -> id_call_type]['count'] ++;
					}
				}
			}
			$was = count($assigned);
			echo "Обновление статусов проведено. До начала процедуры было $was звонков со статусом Записан<br/>";
			echo "Ниже приведено количество записей, которые приобрели какой-либо статус.<br/>";
			unset($changed['Записан']);
			$ch = 0;
			foreach($changed as $arr){
				$status = $arr['name'];
				$num = $arr['count'];
				echo $status.": ".$num."<br/>";
				$ch += $num;
			}
			echo "Не удалось обновить статус у ".($was - $ch)." записей.";
			echo CHtml::link('OK',Yii::app() -> baseUrl.'/data');
		}
	}//*/
	/**
	 * dumps data to dump.txt
	 */
	public function actionDumpData() {
		ob_start();
		vardump(apache_request_headers());
		vardump($_REQUEST);
		$out = ob_get_contents();
		ob_end_clean();
		$f = fopen(Yii::getPathOfAlias('application'). DIRECTORY_SEPARATOR .'dump.txt','a+');
		fwrite($f, 'Dump at '.date('j.m H:i:s').':<br/>'.PHP_EOL);
		fwrite($f, $out.'<br/>'.PHP_EOL);
		fclose($f);
		echo "100";
	}
	/**
	 * views the dump
	 */
	 public function actionViewDump(){
		 if (Yii::app() -> user -> checkAccess('admin')) {
			$read = file_get_contents(Yii::getPathOfAlias('application'). DIRECTORY_SEPARATOR .'dump.txt');
			echo $read;
		 }
	 }
	 /**
	  * Handles the sms.ru reports
	  */
	 public function actionSmsHandler() {
		 //ob_start();
		 Yii::app() -> sms -> handleReport($_POST);
		 
		 /*$sms = new Sms('create',array(1,2,3));
		 vardump($sms);*/
		 //Yii::app() -> sms -> handleReport(array('data' => array(0 => "sms_status\n201606-1000021\n103")));
		 /*$out = ob_get_contents();
		 ob_end_clean();
		 $f = fopen(Yii::getPathOfAlias('application'). DIRECTORY_SEPARATOR .'dump.txt','a+');
		fwrite($f, 'Dump at '.date('j.m H:i:s').':<br/>'.PHP_EOL);
		fwrite($f, $out.'<br/>'.PHP_EOL);
		fclose($f);*/
		//echo "100";
	 }
	public function actionCheckDebug(){
		echo "start func";
		$a = Yii::getVersion();
		echo "Stop for a breakpoint here";
		$time = time();
		echo "Exit the function";
	}

	/**
	 * This is an ajax method that deletes the specified property.
	 */
	public function actionPropDelete () {
		$rez = array();
		if (Yii::app()->request->isAjaxRequest) {
			$data = $_POST;
			$modelClass = $data['modelClass'];
			/**
			 * @var UModel $model
			 */
			$model = $modelClass::model() -> customFind($data['arg']);
			//Если не нашлась модель, то ничего не деламем.
			if ((!$model)||($model -> getIsNewRecord())) {
				$rez['success'] = false;
				return;
			}
			if ($model -> checkUpdateAccess()) {
				$prop = $data['prop'];
				$model -> $prop = '';
				$rez['success'] = $model -> save();
				if (!$rez['success']) {
					var_dump($model -> getErrors());
				}
			} else {
				$rez['success'] = false;
				echo "Not enough rights";
			}
		} else {
			echo "Данное действие доступно только через ajax.";
			$rez ['success'] = false;
		}
		echo json_encode($rez, JSON_PRETTY_PRINT);
	}
	public function actionCheck() {
		//$this -> render('//check');
		require_once(Yii::getPathOfAlias('webroot.vendor'). DIRECTORY_SEPARATOR .'autoload.php');
		$mail = new PHPMailer();
		$mail -> setFrom('f.mrimaster.ru',SiteName);
		$mail -> addAddress("bondartsev.nikita@gmail.com");
		$mail -> Subject = "Уведомление от f.mri";
		$mail -> Body = "проверка";
		if (!$mail -> Send()) {
			echo $mail -> ErrorInfo;
		} else {
			echo "sent!";
		}
	}
}