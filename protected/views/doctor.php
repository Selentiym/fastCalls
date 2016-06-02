<?php if ((Yii::app() -> user -> checkAccess('viewOwnUserCabinet',array('user' => $user)))||((Yii::app() -> user -> checkAccess('viewUserCabinet')))||(Yii::app() -> user -> checkAccess('viewChildUserCabinet',array('user' => $user)))) : ?>
<?php
	$this -> renderPartial('//navBar', array('user' => $user));
?>
<div class='row'>
	<div class="col-sm-4">
		<?php $this -> renderPartial('//info', array('user' => $user)); ?>
		<?php $this -> renderPartial('//_conditions', array('user' => $user)); ?>
		<?php //$this -> renderPartial('//_meassage'); ?>
		<?php //$this -> renderPartial('//_statImage'); ?>
		
	</div>
	<div class="col-sm-8">
		<?php $this -> renderPartial('//statOneUser',array('user' => $user));?>
	</div>
</div>
<?php else: ?>
<?php $this -> renderPartial('//accessDenied'); ?>
<?php endif; ?>