<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.06.2016
 * Time: 20:04
 */
/**
 * @type UserAction $model
 * @type CController $this
 * @type bool $notInitial - показывает, основное ли действие отображается
 */
?>
<div class="container-fluid">
    <div class="well">
        <div class="form-group"><label>Статус</label><div><?php echo $model -> statusText(); ?></div></div>
        <div class="form-group" style="<?php echo (!$model -> period) ? 'display:none' : ''; ?>"><label>Повторять</label><div><?php echo $model -> period; ?></div></div>
        <div class="form-group" style="<?php echo (!$model -> comment) ? 'display:none' : ''; ?>"><label>Комментарий</label><?php echo CHtml::PrettyText($model -> comment); ?></div>
        <div class="form-group" style="<?php echo (!$model -> report) ? 'display:none' : ''; ?>"><label>Отчет</label><?php echo CHtml::PrettyText($model -> report); ?></div>
        <div class="form-group" style="<?php echo (!$model -> text) ? 'display:none' : ''; ?>"><label>Текст сообщения</label><?php echo CHtml::PrettyText($model -> text); ?></div>
    </div>
</div>
<?php
if (is_a($model -> parent, 'UserAction')) {
    if (!$notInitial) {
        echo "<h3>История</h3>";
    }
    $this -> renderPartial('//actions/show', array('model' => $model -> parent, 'notInitial' => true));
}
?>
