<?php $this -> renderPartial('//navBar',array('user' => $model, 'button' => 'rev')); ?>
<?php $user = $model; ?>
<?php if ((Yii::app() -> user -> checkAccess('viewOwnUserCabinet', array('user' => $user)))||(Yii::app() -> user -> checkAccess('viewChildUserCabinet', array('user' => $user)))||(Yii::app() -> user -> checkAccess('viewUserCabinet'))) : ?>
<?php $reviews = $user -> id_type != UserType::model() -> getNumber('doctor') ? Review::model() -> findAll() : $user -> reviews; ?>
<?php Yii::app()->getClientScript()->registerScript('deleteScript','$(".delete-review").click(function(){
	if (!confirm("Вы уверены, что хотите удалить отзыв о "+$(this).attr("clinic")+"?")) {
		return false;
	} else {
		location.replace($(this).attr("goto"));
	}
});',CClientScript::POS_END); ?>
<?php echo CHtml::link('Добавть отзыв', Yii::app() -> baseUrl . '/addreview'); ?>
<div class="well">
	<table class="table table-bordered">
		<tr>
			<th>Клиника</th>
			<th>Доктор</th>
			<th>Тип исследования</th>
			<th>Рейтинг</th>
			<th>Комментарий</th>
			<th>Автор</th>
			<th>Принадлежность</th>
			<th>Кнопки</th>
		</tr>
		<?php
			foreach($reviews as $review) {
				$this -> renderPartial('//review/_shortcut', array('review' => $review, 'user' => $user));
			}
		?>
	</table>
</div>
<?php endif; ?>