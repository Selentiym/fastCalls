<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.06.2016
 * Time: 19:17
 */
Yii::app() -> getClientScript() -> registerCoreScript('jquery');
Yii::app() -> getClientScript() -> registerScriptFile(Yii::app() -> baseUrl.'/js/jquery.datetimepicker.full.min.js',CClientScript::POS_END);
Yii::app() -> getClientScript() -> registerScriptFile(Yii::app() -> baseUrl.'/js/actionScenario.js',CClientScript::POS_END);
Yii::app() -> getClientScript() -> registerCssFile(Yii::app() -> baseUrl.'/css/jquery.datetimepicker.min.css');
//Запускаем скрипт обработчиков
Yii::app() -> getClientScript() -> registerScript('datetimepicker','
pageReady();
',CClientScript::POS_READY);
if (!$name) {
    $name = 'time';
}
?>

<div class="col-xs-4">
    <div id="datepickerButtons">
        <input type="hidden" name="<?php echo $name; ?>" id="purposeTime"/>
        <span id="today">today</span>
        <span id="tomorrow">tomorrow</span>
        <span id="threeDays">3 days</span>
        <span id="week">week</span>
    </div>
    <input type="text" id="datepicker"/>
    <?php if ($button): ?>
    <input type="checkbox" name="makeNow" id="makeNow"/>Выполнить сразу
    <?php endif; ?>
</div>

