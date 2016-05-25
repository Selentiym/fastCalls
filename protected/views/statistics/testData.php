<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.05.2016
 * Time: 18:40
 */
Yii::app()->getClientScript() -> registerScript('send',"
					var requests = $t;
					alert('start!');
					console.log(requests);
					for (var i = 0; i < requests.length; i++){
						console.log(requests[i]);
						$.ajax({
							url:'".Yii::app() -> baseUrl."/site/telfinHangup',
							type:'POST',
							data:requests[i]
						});
					}
				",CClientScript::POS_READY);
?>