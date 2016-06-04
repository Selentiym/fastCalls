<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.components.CHtml',
		'application.models.*',
		'application.models.actions.*',
		'application.components.*',
		'application.components',
		'application.components.googleDoc.*',
		'application.components.telfin.*',
		'application.components.userActions.*',
		//'webroot.vendor.autoload'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Selentiym_1705',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'sms' => array(
			'class' => 'application.components.smsClient',
			'authId' => '1fb53119-4130-5524-4950-c8c74a627908'
		),
		//authManager to enable RBAC - authorization. All info about roles, operations and tasks is stored in a database db.
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				//'images/<addr:.+>' => 'site/giveImage',
			//gii block			
				'gii'=>'gii',
				'gii/<controller:\w+>'=>'gii/<controller>',
				'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
				
				'finance/<arg_update:\d+>' => 'site/updateSelf',
				
				//'assignCall/<call_id:\d+>/<user_id:\d+>' => 'site/assignCall',
				'assignCall' => 'site/assignCall',
			//end of gii block
				'cabinet' => 'site/cabinet',
				'cabinet/<arg:\w+>' => 'site/cabinet',
				'cabinet/<arg:\d+>' => 'site/cabinet',
				
				'print' => 'site/printDirections',
				'print/<arg:\w+>' => 'site/printDirections',
				
				'unlinkCall/<arg:\d+>' => 'site/unlinkCall',

				'sendSms/<arg:\w+>' => 'site/sendSms',

				'payStat/user/<arg:\w+>' => 'site/payStatistics',
				'payStat/user/<arg:\w+>/<from:\d*>' => 'site/payStatistics',
				'payStat/user/<arg:\w*>/<from:\d*>/<to:\d*>' => 'site/payStatistics',

				'stat/<from:\d*>' => 'site/stat',
				'stat/<arg:\w+>' => 'site/stat',
				'stat/<from:\d*>/<to:\d*>' => 'site/stat',
				'stat/<arg:\w+>/<from:\d*>' => 'site/stat',
				'stat/<arg:\w*>/<from:\d*>/<to:\d*>' => 'site/stat',
				
				'allCalls/<from:\d*>/<to:\d*>' => 'site/allCalls',
				'allCalls/<from:\d*>' => 'site/allCalls',
				
				'errors/telfin/p<page:\d+>' => 'site/telfinErrors',
				'errors/telfin' => 'site/telfinErrors',
				'errors/p<page:\d+>' => 'site/errors',
				'smsList/p<page:\d+>' => 'site/smsList',
				
				
				'<arg:\w+>/info' => 'site/info',
				'info' => 'site/info',
				
				'deletereview/<arg\w+>' => 'site/ReviewDelete',
				
				'deleteCall/<arg\w+>' => 'site/CallDelete',
				'deleteTelfinCall/<arg\w+>' => 'site/TelfinCallDelete',
				
				'reviews' => 'site/showReviews',
				'<arg:\w+>/reviews' => 'site/showReviews',
				
				'mentor' => 'site/MentorCreate',
				'mentor/update/<arg_update:\w+>' => 'site/MentorUpdate',
				'mentor/delete/<arg:\w+>' => 'site/MentorDelete',

				'option' => 'site/optionCreate',
				'option/update/<arg_update:\w+>' => 'site/optionUpdate',
				'option/delete/<arg:\w+>' => 'site/optionDelete',
				
				'allstat/<from:\d+>/<to:\d+>' => 'site/allstat',
				'allstat/<from:\d+>' => 'site/allstat',
				
				'paystat/<from:\d+>/<to:\d+>' => 'site/paystat',
				'paystat/<from:\d+>' => 'site/paystat',
				
				'data' => 'site/addData',
				
				'<arg:\w+>/patients'=>'site/patients',
				
				'addPatient/<id:\d+>' => 'site/PatientCreate',
				
				//'<arg:\w+>/addreview' => 'site/ReviewCreate',
				'addreview/' => 'site/ReviewCreate',
				'addreview/<id_call:\d+>' => 'site/ReviewCreate',
				//'<arg:\w+>/addreview/<id_call:\d+>' => 'site/ReviewCreate',
				//'<arg:\w+>/editreview/<arg_update:\w+>' => 'site/ReviewUpdate',
				'editreview/<arg_update:\w+>' => 'site/ReviewUpdate',
				//'<arg:\w+>/addreview/<id_call:\d+>' => 'site/ReviewCreate',
				
				'<arg:\w+>/create/<type:(doctor|maindoc)>' => 'site/createUser',
				'create/<type:(doctor|maindoc)>' => 'site/createUser',
				
				'edit/<arg_update:\d+>' => 'site/updateUser',
				'<arg:\w+>/edit/<arg_update:\d+>' => 'site/updateUser',
				
				
				'searchAgain/<arg:\w+>' => 'site/telfinSearchAgain',
				
				
				'PhoneUpdate/<arg_update:\d+>' => 'site/PhoneUpdate',
				'PhoneDelete/<arg:\w+>' => 'site/PhoneDelete',
				'PhoneCreate' => 'site/PhoneCreate',
				
				'delete/<arg:\w+>' => 'site/UserDelete',
				
				//'UserAddressUpdate/<update_arg:\d+>' => 'site/<modelName>Update',
				'<modelName:\w+>Update/<arg_update:\d+>' => 'site/<modelName>Update',
				'<modelName:\w+>Delete/<arg:\d+>' => 'site/<modelName>Delete',
				
				'<action:\w+>' => 'site/<action>',
				'<action:\w+>/<id:\d+>' => 'site/<action>',

				'site/usersByMD/<arg:\d+>' => 'site/usersByMD',
				'site/<action:\w+>/<arg:\d+>' => 'site/<action>',

				'<action:\w+>/<from:\d+>/<to:\d+>' => 'site/<action>',
				
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				//'<controller:\w+>/<action:\w+>/<arg:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
		/* Generated automatically:
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		/*'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=calls',
            'tablePrefix' => 'tbl_',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),*/
		'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=cq85254_calls2',
            'tablePrefix' => 'tbl_',
            'emulatePrepare' => true,
            'username' => 'cq85254_calls2',
            'password' => 'kicker',
            'charset' => 'utf8',
			
			'nullConversion' => PDO::NULL_EMPTY_STRING,
        ),
		
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		/* Not default, added by me. To have a simple access way. */
		'authManager'=>array(
            'class'=>'CDbAuthManager',
            'connectionID'=>'db',
        ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);