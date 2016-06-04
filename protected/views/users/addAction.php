<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.06.2016
 * Time: 9:37
 */
Yii::app() -> getClientScript() -> registerCoreScript('jquery');
Yii::app() -> getClientScript() -> registerScriptFile(Yii::app() -> baseUrl.'/js/jquery.datetimepicker.full.min.js',CClientScript::POS_END);
Yii::app() -> getClientScript() -> registerScriptFile(Yii::app() -> baseUrl.'/js/actionScenario.js',CClientScript::POS_END);
Yii::app() -> getClientScript() -> registerCssFile(Yii::app() -> baseUrl.'/css/jquery.datetimepicker.min.css');

//Запускаем скрипт обработчиков
Yii::app() -> getClientScript() -> registerScript('datetimepicker','
pageReady();
',CClientScript::POS_READY);
Yii::app() -> getClientScript() -> registerScript('applyText','

',CClientScript::POS_READY);
?>

<form method="post" action="<?php echo Yii::app() -> baseUrl; ?>/userAddAction" style="padding-left:20px">
    <div class="row">
        <?php
        User::model() -> showUserList($_POST['userGroup']);
        ?>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <div id="datepickerButtons">
            <input type="hidden" name="time" id="purposeTime"/>
            <span id="today">today</span>
            <span id="tomorrow">tomorrow</span>
            <span id="threeDays">3 days</span>
            <span id="week">week</span>
            </div>
            <input type="text" id="datepicker"/>
        </div>
        <div class="col-xs-4">
            <div class="row">
                <?php
                    CHtml::activeDropDownListChosen2(
                        ActionType::model(),
                        'id',
                        CHtml::listData(ActionType::model() -> findAll(),'alias','name'),
                        array('id'=>'actionType', 'name'=>'actionType'),
                        array(),
                        '{}');
                ?>
            </div>
        </div>
        <div class="col-xs-4">
            <input id="repeat" type="checkbox" name="repeat"/> Повторять регулярно
            <select name="repeatPeriod" id="repeatPeriod" style="display:none">
                <option value="year">Раз в год</option>
                <option value="month">Раз в месяц</option>
                <option value="week">Раз в неделю</option>
                <option value="day">Каждый день</option>
            </select>
        </div>
    </div>
    <div class="row" style="margin-top:20px;">
        <div id="mesTextContainer">
            <textarea name="text" placeholder="Текст сообщения" id="text" style="width:300px; height:200px;display:inline-block"></textarea>
            <div id="texts" style="display:inline-block">
                <?php
                $patterns = SmsPattern::model() -> findAll();
                foreach ($patterns as $pt) {
                    echo "<button data-text='{$pt -> text}'>{$pt -> value}</button>";
                }
                ?>
                <!--<div><button data-text="text1">Поздравление с др</button></div>
                <div><button data-text="text2">Текст2</button></div>
                <div><button data-text="text3">Текст3</button></div>-->
            </div>
        </div>
        <textarea name="comment" placeholder="Комментарий к действию" id="comment" style="width:300px; height:200px"></textarea>
    </div>
    <div class="row">
        <input type="submit" value="Создать действие"/>
    </div>
</form>

