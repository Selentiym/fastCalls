<html>
<body>
<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.05.2016
 * Time: 14:14
 */
Yii::app() -> getClientScript() -> registerCoreScript('jquery');
Yii::app() -> getClientScript() -> registerScriptFile(Yii::app() -> baseUrl.'/js/jquery.datetimepicker.full.min.js',CClientScript::POS_END);
Yii::app() -> getClientScript() -> registerCssFile(Yii::app() -> baseUrl.'/css/jquery.datetimepicker.min.css');
Yii::app() -> getClientScript() -> registerScript('datetimepicker','
    function roundMinutes(date) {
        var quant = 60;
        date.setMinutes(Math.round(date.getMinutes() / quant) * quant);
        return date;
    }
    function unixTime(date){
        return Math.floor(date.getTime()/1000);
    }
    function getTodayWithTime(){
        var now = new Date();
        now.setSeconds(0);
        var D = roundMinutes(picker.datetimepicker("getValue"));
        now.setHours(D.getHours());
        now.setMinutes(D.getMinutes());
        return now;
    }
    $.datetimepicker.setLocale(\'ru\');
    var picker = $("#datepicker");
    var hidden = $("#purposeTime");
    var format = "d.m.Y H:i";
    var startDate = new Date();
    startDate = roundMinutes(startDate);
    hidden.val(unixTime(startDate));
    picker.datetimepicker({
        //lazyInit: true,
        step: 60,
        weeks: true,
        dayOfWeekStart:1,
        format:format,
        value:startDate,
        onClose: function(date,inp){
            console.log(date.getTime());
        }
    });
    $("#today").click(function(){
        var now = getTodayWithTime();
        picker.datetimepicker({value:now});
    });
    $("#tomorrow").click(function(){

        var now = getTodayWithTime();
        now.setDate(now.getDate() + 1);
        picker.datetimepicker({value:now});
    });
    $("#threeDays").click(function(){
        var now = getTodayWithTime();
        now.setDate(now.getDate() + 3);
        picker.datetimepicker({value:now});
    });
    $("#week").click(function(){
        var now = getTodayWithTime();
        now.setDate(now.getDate() + 7);
        picker.datetimepicker({value:now});
    });
',CClientScript::POS_READY)
?>
<form>
    <input type="text" id="datepicker"/>
    <input type="hidden" name="purposeTime" id="purposeTime"/>
    <button id="today">today</button>
    <button id="tomorrow">tomorrow</button>
    <button id="threeDays">3 days</button>
    <button id="week">week</button>
</form>
</body>
</html>