/**
 * Created by user on 02.06.2016.
 */
/**
 * Вешает обработчики, готовит страницу, в общем.
 */
//удобно, чтобы селект висел в общем доступе
select = $("#actionType");
function pageReady() {
    /**
     * немного функций-помощников для работы с календарем
     */
    function roundMinutes(date) {
        var quant = 60;
        date.setMinutes(Math.round(date.getMinutes() / quant) * quant);
        return date;
    }
    function unixTime(date) {
        return Math.floor(date.getTime() / 1000);
    }
    function getTodayWithTime() {
        var now = new Date();
        now.setSeconds(0);
        var D = roundMinutes(picker.datetimepicker("getValue"));
        now.setHours(D.getHours());
        now.setMinutes(D.getMinutes());
        return now;
    }
    function toPicker(date) {
        hidden.val(unixTime(date));
        picker.datetimepicker({value: date});
    }
    /**
     * календарь
     */
    $.datetimepicker.setLocale('ru');
    //DOM календаря
    var picker = $("#datepicker");
    //Будет хранить реальную дату, которую нужно передать на сервер
    var hidden = $("#purposeTime");
    //формат отображения даты
    var format = "d.m.Y H:i";
    //По умолчанию выбран настоящий момент, округленный по минутам.
    var startDate = new Date();
    startDate = roundMinutes(startDate);
    hidden.val(unixTime(startDate));
    picker.datetimepicker({
        //lazyInit: true,
        step: 60,
        weeks: true,
        dayOfWeekStart: 1,
        format: format,
        value: startDate,
        onChangeDateTime: function (date, inp) {
            console.log(date.getTime());
            hidden.val(unixTime(date));
        }
    });
    /**
     * Кнопки для работы с календарем
     */
    $("#today").click(function () {
        var now = getTodayWithTime();
        toPicker(now);
    });
    $("#tomorrow").click(function () {

        var now = getTodayWithTime();
        now.setDate(now.getDate() + 1);
        toPicker(now);
    });
    $("#threeDays").click(function () {
        var now = getTodayWithTime();
        now.setDate(now.getDate() + 3);
        toPicker(now);
    });
    $("#week").click(function () {
        var now = getTodayWithTime();
        now.setDate(now.getDate() + 7);
        toPicker(now);
    });
    /**
     * Работа с селектом выбора типа действия
     */
    //common comment
    var comment = $("#comment");
    console.log(select);
    select.on("change", function(e) {
        var chosen = $(e.target).val();
        modifyForm(chosen);
        //alert(e.target.val());
    });
    /**
     * Если ставим регулярный повтор.
     */
    var repeat = $("#repeat");
    function changeRepeat(val){
        repeat.prop(val);
        repeatChanged(val);
    }
    function repeatChanged(val) {
        if (val) {
            repeatPeriod.show(500);
        } else {
            repeatPeriod.hide(500);
        }
    }
    var repeatPeriod = $("#repeatPeriod");
    $("#repeat").bind("click change",function(e){
        repeatChanged($(e.target).prop("checked"));
    });
    /**
     * События, связанные с применением шаблонов
     */
    var textCont = $("#text");
    $("#texts").children("button").click(function(event){
        event.preventDefault();
        var subs = true;
        if (textCont.attr("data-changed") == 1) {
            subs = confirm("Применить шаблон? Все изменения будут потеряны.");
        }
        if (subs) {
            textCont.val($(this).attr("data-text"));
            textCont.attr("data-changed","0");
        }
    });
    textCont.change(function(){
        $(this).attr("data-changed","1");
    });
}
function selectType(type){
    select.select2("val",type);
    modifyForm(type);
}
function modifyForm(chosen){
    //text for sms or email.
    var text = $("#mesTextContainer");
    //common comment
    var comment = $("#comment");
    switch (chosen){
        case "sms":
            text.show();
            break;
        case "email":
            text.show();
            break;
        case "reminder":
            text.hide();
            break;
    }
}
