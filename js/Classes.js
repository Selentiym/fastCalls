parentDrag = $("#DragContainer");
parentAction = $("#ActionContainer");
parentCont = $("#parentDrag");
if (!baseUrl) {
    baseUrl = '';
}
parentDialog = $("#DialogContainer");

def = {};
def.cell = 1;
def.cellsPlus = 6;
def.cellsMinus = 7;


/**
 * Created by user on 19.04.2016.
 */
function Base () { return {}; }
/**
 * @param parameters = {
 * html -inner html,
 * target - DOM object the element to be appended to
 * position = {
 * left, top -coordinates to be given to offset()
 * },
 * show - whether to show element immediately
 * }
 * @constructor
 */
function Node(parameters){
    var html = parameters.html;
    var target = parameters.target;
    var position = chObj(parameters.position);
    var show = parameters.show;
    var css = chObj(parameters.css);
    var node = parameters.node;
    var nodeConfig = chObj(parameters.nodeConfig);

    //Наследуемся от базового объекта для получения prototype.
    var me =  Base();
    //Сохранили созданный объект в базу и нарастили инкремент.
    Base.prototype.inc ++;
    Base.prototype.objects[Base.prototype.inc] = me;

    //Сохраняем айдишник на всякий случай.
    me.id = Base.prototype.inc;
    console.log('Added node '+me.id);
    console.log(Base.prototype.objects[me.id]);
    if (!node) {
        node = '<div/>';
    }
    //Если айдишник элемента не задан внешне, то назначаем его по умолчанию.
    if (!nodeConfig.id) {
        nodeConfig.id = 'el' + me.id;
    }
    if (nodeConfig.element) {
        //nodeConfig.element.detach();
        me.element = nodeConfig.element;
    } else {
        //Создаем элемент, который будет отображать
        me.element = $(node, nodeConfig);
        me.element.append(html);
        /**
         * Задаем стили элемента.
         */
        if (css) {
            me.element.css(css);
        }
        /**
         * Задаем позицию вставляемого элемента.
         */
        if (!position.left) {
            position.left = 0;
        }
        if (!position.top) {
            position.top = 0;
        }
        if ((position.left) || (position.top)) {
            me.element.offset(position);
        }
        /**
         * Отображаем элемент на странице, если нужно.
         */
        if (!target) {
            target = $('body');
        }

        target.append(me.element);
        if (!show) {
            //alert('do not show!');
            me.element.hide();
        }
    }
    //Сохраняем на DOM-елементе ссылку на объект.
    me.element.data('object',me.id);
    //alert(me.id);


    /**
     * Задаем функцию уничтожения
     */
    me.destroy = function(){
        var ind = _.indexOf(Base.prototype.getObject(me.id));
        if (!ind) { return false; }
        //Base.prototype.objects.splice(ind, 1);
        //var obj = Base.prototype.objects[ind];
        //Меняем соответсвующее значениена undefined
        //delete Base.prototype.objects[ind];
        Base.prototype.objects[ind] = undefined;
        //delete me.objects[me.getObject(me.id)];
        //Удаляем элемент DOM
        me.element.remove();
        //Удаляем объект.
        delete me;
        console.log(Base.prototype.objects);
        return true;
    };
    return me;
}
/**
 * Содержит набор всех(!) объектов, унаследованных от Node, на странице
 * Далее будет использоваться для соответствия DOM element <-> объект Base
 * DOM element в .data('object') содержит ключ массива Base.prototype.objects
 * @type {Array}
 */
Base.prototype.objects = [];
/**
 * Содержит автоинкремент для айдишников. Полный аналог AI Mysql.
 * @type {number}
 */
Base.prototype.inc = 0;
/**
 * @return Base - an object with index id
 * @param id
 */
Base.prototype.getObject = function (id){
    //console.log(this);
    if (!this.objects[id]) {
        console.log(this);
        alert('mistake!');
    }
    return this.objects[id];
};
/**
 *
 */
Base.prototype.toString = function (){
    return this.element.attr("id");
};

/**
 * Создаем общий перетаскиваемый элемент.
 */
function Drag(parameters){
    var config = parameters.config;
    var me =  Node(parameters);
    /**
     * Содержит drop, к которому прикреплен данный drag.
     */
    me.holer = false;
    /**
     * Объект, который хранит различные переменные.
     * Выделен в отдельной свойство, чтобы не путать ни с чем стандартным.
     */
    me.vars = chObj(parameters.vars);
    //Запоминаем конфиг.
    me.vars.config = parameters;
    me.element.addClass('prefix_drag');
    me.element.draggable(chObj(config));
    /**
     * Как только начинается движение, удаляем драг из дропа.
     */
    me.element.on("dragstart",function(event, ui){
        if (me.holder) {
            me.holder.removeDrag(me);
        }
    });
    return me;
}
/**
 * Создаем общий принимающий элемент.
 */
function Drop(parameters){
    var config = parameters.config;
    //Наследуемся от Node
    var me =  Node(parameters);
    me.element.droppable(config);
    //Будет содежать драги, которые попали в данный дроп.
    me.guests = [];
    /**
     * Задаем обработчик попадания элемента в droppable
     */
    me.element.on("drop", function( event, ui ) {
        var el = Base.prototype.getObject(ui.draggable.data('object'));
        console.log(me + ': ' + el);
        //Сохраняем элемент, который был вброшен, в массив вброшенных.
        //me.guests.push(el);
        me.capture(el);
        console.log(me.guests);
    });
    /**
     * Задаем обработчик покидания элементом зоны droppable.
     */
    me.element.on("dropout",function(event, ui){
        var el = Base.prototype.getObject(ui.draggable.data('object'));
        //Удаляем drag, который покинул нас.
        me.guests.remove(el);
        console.log('text' + me.guests);
    });
    /**
     * Добавляем Drag в коллекцию.
     * @param drag
     */
    me.capture = function (drag) {
        //alert($.inArray(drag, me.guests));
        if ($.inArray(drag, me.guests) !== -1) {
            return;
        }
        //alert(me.element.offset());
        var pos = me.element.offset();
        if (pos.top != 20) {
            console.log(pos);
            console.log(me.element);
        }
        var sumWidth = 0;
        var maxHeight = 0;
        //Считаем ширину уже имеющихся элементов.
        _.map(me.guests,function(drag){
            sumWidth += drag.element.innerWidth();
            var h = drag.element.innerHeight();
            if (h > maxHeight) {
                maxHeight = h;
            }
        });
        var h = drag.element.innerHeight();
        if (maxHeight < h) { maxHeight = h; }
        maxHeight += 20;
        //Прибавляем расстояния между элементами
        sumWidth += (me.guests.length + 1) * 10;
        //Сдвиг нового элемента относительно родителя.
        var leftOffset = sumWidth;
        sumWidth += drag.element.innerWidth() + 10;
        //Если ширина дропа мала, то увеличиваем ее.
        if (sumWidth > me.element.width()) {
            me.element.width(sumWidth);
        }
        if (maxHeight > me.element.height()) {
            me.element.height(maxHeight);
        }

        drag.element.offset({
            top: pos.top + 10,
            left: pos.left + leftOffset
        });
        console.log(drag.element.offset());
        //Добавляем драг в массив содержащихся
        me.guests.push(drag);
        //Сохраняем информацию о хозяине в драге.
        drag.holder = me;
    };
    /**
     * Удаляем драг из имеющихся и обновляем позиции.
     * @param drag
     */
    me.removeDrag = function(drag) {
        var ind = $.inArray(drag, me.guests);
        if (ind === -1) {
            return;
        }
        //Сдвигаем более правые элементы.
        var offset = drag.element.innerWidth() + 10;
        _.each(me.guests,function(dr,i){
            if (i > ind) {
                var pos = dr.element.offset();
                dr.element.offset({
                    left: pos.left - offset
                });
            }
        });
        //Удаляем из массива со сдвигом индекса
        me.guests.splice(ind,1);
    };
    return me;
}
/**
 * Создает элемент Drag, используя некоторые стандартные настройки.
 * @param parameters
 * @returns {Drag|*}
 * @constructor
 */
function FastDrag(parameters){
    var defaultParam = {
        html: '<span style="color:red">blabla</span>',
        target: parentDrag,
        show: true,
        config: {
            cancel:'.noDrag',
            //helper:'clone',
            //opacity:'0.35',
            revertDuration:500,
            snap:true,
            snapMode: 'outer',
            snapTolerance:5,
            containment:"parent",
            stack:'.drag',
            distance:10
            /*start:function(event, ui){
             alert('start!');
             }*/
        },
        nodeConfig:{
            'class':'drag'
        }
    };
    var param = $.extend(true, defaultParam, parameters);
    return Drag(param);
}
function UserDrag(parameters){
    var users = chArr(parameters.users);
    if (!users.length) {
        alert('Не выбрано ни одного пользователя!');
        return;
    }
    //alert(users);
    var name = parameters.name;
    if (!name) {
        name = 'Без названия';
    }
    var closeButton = $('<span/>', {
        'class':'closeDrag'
    });
    var enlargeButton = $('<span/>',{
        'class':'enlargeDrag'
    });
    var html = $('<div/>',{
        'class':'headMenu'
    }).append(enlargeButton).append(closeButton);
    html.after($('<h2/>',{
        "class":"DragName"
    }).append(name));
    html.after($('<div/>',{
        'class':'body'
    }).append($('<p/>',{
        text:users.toString()
    })));
    parameters.html = html;
    var me = FastDrag(parameters);
    //Вешаем обработчик закрытия окна. Только сейчас, чтобы можно было сохранить
    // в замыкание функцию закрытия.
    closeButton.click(function(){
        if (me.vars.notTrivial) {
            if (!confirm('Набор пользователей в выбранной группе нетривиален. Вы действительно хотите удалить элемент?')) {
                return;
            }
        }
        me.destroy();
    });
    //Запоминаем какое было задано имя.
    me.vars.name = name;
    me.element.addClass('userDrag');
    //Сохраняем юзеров, которых отображает данный блок.
    me.users = users;
    /**
     * Добавляем обработчики, если доступна функция с главной страницы.
     */
    if (typeof DragAddListeners == 'function') {
        DragAddListeners(me.element);
    }
    /**
     * Перименовывает драг
     */
    me.rename = function(newName){
        if (!newName) {
            var entered = prompt('Введите новое имя:', me.vars.name);
            if (entered !== null) {
                if (!entered) {
                    alert('Элемент не может иметь пустое имя');
                    return;
                }
                me.element.children('h2').html(entered);
                me.vars.name = entered;
            }
        }
    };
    me.showDialog = function() {
        if (me.dialog) {
            me.dialog.open();
            console.log('has dial');
        } else {
            console.log('new dial');
            me.dialog = new Dialog(me, {});
        }
    };
    //Не забываем поставить обработчик на открытие окошка
    enlargeButton.click(function(){
        me.showDialog();
    });
    return me;
}
function MedPredDrag(parameters){
    var param = chObj(parameters);
    var users = [];
    var MDid = param.MDid;
    if (!MDid) {
        MDid = param.data;
    }
    if (!MDid) { return {success:false}; }
    $.ajax({
        url: baseUrl + 'site/usersByMD/'+ MDid,
        dataType:'json'
    }).done(function(data){
        me = UserDrag({
            users:data.users,
            name:'MD: ' + data.fio
        });
    });
    param.users = users;
}
function OptionDrag(parameters){
    var param = chObj(parameters);
    var id = param.OptionId;
    if (!id) {
        id = param.data;
    }
    if (!parseInt(id)) { return {success:false}; }
    $.ajax({
        url: baseUrl + 'site/usersByOption/' + id,
        dataType: 'json'
    }).done(function(data){

        console.log(data);
        var name = data.name;
        if (data.image) {
            name = $('<img/>',{
                src:data.image,
                css: {
                    height:'30px',
                    border:'1px solid green'
                }
            }).after(data.name);
        }
        me = UserDrag({
            users:data.users,
            name:name
        });
    });
}
function ActionDrop(parameters){
    var me =  Drop(parameters);
    me.action = parameters.action;
    //При попадании нового Drag'а в ActionDrop пытаемся совершить действие.
    me.element.on("drop",function(){
        me.action.go();
    });
    /**
     * Функция для выдачи юзеров. В первый аргумент можно передать
     * callable, чтобы применить к массиву массивов пользователей
     * всех Drag'ов, которые лежат в этом дропе.
     * Во втором аргументе передается bool, убить ли drag'и потом.
     * В третьем аргументе передается bool, использовать ли первый массив в качестве
     * основы.
     */
    me.getUsers = function(callable, destroy, init){
        if (callable !== undefined) {
            try {
                var users = [];
                if (init === true) {
                    drag1 = me.guests.shift();
                    users = drag1.users;
                }
                _.map(me.guests,function(drag){
                    users = callable(users,drag.users);
                    if (destroy === true) {
                        drag.destroy();
                    }
                });
                if (destroy === true) {
                    me.guests = [];
                }
                return users;
            } catch (e) {
                console.log(e);
                alert('Ошибка при получении юзеров.');
                return [];
            }
        }
    };
    me.removeGuests = function(){
        _.map(me.guests, function(drag){
            drag.destroy();
        });
        me.guests = [];
    };
    return me;
}
/**
 * Базовый класс для действий.
 * @param parameters
 * @returns {Node}
 * @constructor
 */
function Action(parameters){
    var DOMparams = chObj(parameters.DOMparams);
    DOMparams = $.extend(true,{
        show:true,
        target:parentAction,
        css:{
            background:'#18FF96'
        },
        nodeConfig:{
            'class':'ui-corner-error'
        }
    },DOMparams);
    var me =  Node(DOMparams);
    me.drops = [];
    /**
     * Хранит контейнер, для помещения в него результата действия.
     */
    me.resultDrop = false;
    if (parameters.resultDrop) {
        var param = parameters.resultDrop;
        //Не хотим, чтобы кто-то что-то кидал в результирующий дроп.
        param.config = $.extend(param.config, {accept:'nothing'});
        me.resultDrop = new Drop(param);
        console.log(me.resultDrop.element);
    }
    /**
     * Выводит результат действия.
     */
    me.showRezult = function (drag) {
        drag.vars.notTrivial = true;
        if (me.resultDrop) {
            drag.element.show();
            me.resultDrop.capture(drag);
        } else {

        }
        drag.element.show();
    };
    /**
     * Эта функция будет вызываться дропами при попадании в последнего какого-то драга.
     */
    me.go = function(){
        return me.validate();
    };
    /**
     * Показывает, можно ли выполнить действие (учитывает драги в дропах).
     */
    me.validate = function (){
        return false;
    };
    /**
     * Добавляет дроп к списку аргументов действия.
     */
    me.addDrop = function(conf){
        var config = chObj(conf);
        //Задаем в качестве контейнера для Drop'а элемент действия.
        config.target = me.element;
        config.action = me;
        var drop =  ActionDrop(config);
        me.drops.push(drop);
    };
    return me;
}
/**
 * Класс для действия сложения, содержит один drop, в который набираются операнды
 * @param parameters
 * @constructor
 */
function ActionAdd(parameters){
    var me = Action(parameters);
    var dropConfig = chObj(parameters.dropConfig);
    me.addDrop(dropConfig);
    me.validate = function(){
        var drop = me.drops[0];
        return (drop.guests.length > 1);
    };
    me.go = function () {
        if (me.validate()) {
            var drop = me.drops[0];
            //Будет хранить результирующих пользователей
            var users = [];
            //Удаляем все операнды.
            _.map(drop.guests, function (obj) {
                if ((obj.users instanceof Array)&&(obj.users.length)) {
                    users = _.union(users, obj.users);
                }
                obj.destroy();
                return undefined;
            });
            //обнуляем содержащиеся Drag-и.
            drop.guests = [];
            console.log(Base.prototype.objects);
            //Создаем результат
            me.showRezult(UserDrag({
                //html: 'sum: ' + text
                users:users,
                name:'sum',
                show:false
            }));
            return true;
        }
        return false;
    };
    return me;
}
function ActionSubtract(parameters){
    var me = Action(parameters);
    /**
     * Создаем дропы
     */
    var minuendConfig = parameters.menuend;
    var subtrahendConfig = parameters.subtrahend;
    me.addDrop(minuendConfig);
    me.addDrop(subtrahendConfig);
    //Будем пользоваться ими внутри функций, для удобства выносим.
    var min = me.drops[0];
    var subtr = me.drops[1];
    //Задаем триггер
    me.validate = function(){
        return ((min.guests.length)&&(subtr.guests.length));
    };
    me.go = function() {
        if (me.validate()) {
            //Получили объединение пользователей
            var minUs = min.getUsers(_.union, true, false);
            var subtrUs = subtr.getUsers(_.union, true, false);
            //А теперь вычитаем их
            var users = _.difference(minUs, subtrUs);
            //Создаем результат
            me.showRezult(UserDrag({
                //html: 'sum: ' + text
                users:users,
                name:'subtract',
                show:false
            }));
            return true;
        }
    };
    return me;
}
/**
 * @param drag
 * @constructor
 */
function Dialog(drag, parameters){
    /**
     * @type {UserDrag} drag
     */
    if (!drag.users.length) {
        alert('Ошибка, врачей не обнаружено.');
        return;
    }
    var bodyTemp = $("<div/>",{
        "class":"dialogBody"
    });
    var wrapButton = $("<span/>",{
        "class":"wrapDialog"
    });
    var html = $("<div/>",{
        "class":"headMenu"
    }).append(wrapButton);
    html.after(bodyTemp);
    //Создаем объект на странице
    var me = new Node($.extend(parameters, {
        target: parentDialog,
        css:{
            width:'900px',
            height:'900px',
            overflow:'auto',
            background:'lightyellow',
            borderRadius:'10px'
        },
        nodeConfig: {
            "class": "dialog"
        },
        show:true,
        borderRadius:'10px',
        html:html
    }));
    //Сохраняем в себе ссылку на DOM элемент внутренности - будет полезно.
    me.body = bodyTemp;
    //И в себе тоже ссылку на драг.
    me.drag = drag;
    //Функция открытия далогового окна
    me.open = function(){
        //parentDialog.css('z-index',1000);
        parentCont.hide();
        $.sidr('close', 'sidr-left');
        me.element.show();
    };
    //Функция закрытия диалогового окна
    me.close = function(){
        $.sidr('open', 'sidr-left');
        me.element.hide();
        parentCont.show();
    };
    me.updateData = function (){
        console.log('updateFunction');
        //alert('updated');
    };
    /**
     * Функция для изменения минимальной ячейки юзера.
     */
    me.setCell = function(newCell){
        if (newCell != me.cell) {
            me.cell = newCell;
            me.updateData();
        }
    };
    me.open();
    /**
     * Генерируем внутренность окна.
     * Заранее внутри должна была быть повешена гифка загрузки.
     */
    function toggleButtons(){
        $(this).parent().children().removeClass('active');
        $(this).addClass('active');
    }
    var cellSize = $('<div/>',{
        "class":"CellSize",
        css:{"display":"inline-block"}
    }).append(MakeButton({text:"День",handler:function(){
        me.setCell(1);
        toggleButtons.call(this);
    }})).append(MakeButton({text:"Неделя",handler:function(){
        me.setCell('week');
        toggleButtons.call(this);
    }})).append(MakeButton({text:"Месяц",handler:function(){
        me.setCell('month');
        toggleButtons.call(this);

    }}));
    //Сохраняем на будущее заголовок страницы.
    me.tableHead = $('<tr/>');
    //Сохраняем разделитель заголвочной строки.
    me.separator = $('<td/>',{
        'class':'tableSeparator'
    });
    me.tableHead.append(me.separator);
    //Сохраняем ссылку на тело таблицы.
    me.tbody = $('<tbody/>');
    var body = $('<div/>',{
        'class':'commonDialogInfo'
    }).append($('<h2/>',{
        'class':'DragName',
        html:drag.vars.name
    })).after(
        MakeCalender()
    ).after(
        cellSize
    ).after($('<table/>',{
        "class":"DialogUsersTable"
    }).attr('border',1)
        .append($('<thead/>')
            .append(me.tableHead)
            .after(me.tbody)
        )
    );
    //Делаем шапку таблицы
    InsertBasicData(me.separator,{
        fio:'ФИО',
        tel:'Телефон',
        email:'Почта'
    });
    me.body.append(body);

    var userObj = _.map(drag.users, function(id){
        var temp = new User({id:id});
        me.tbody.append(temp.element);
    });

    //Задаем ячейку по умолчанию и тем самым запускаем процесс
    // подгрузки информации о юзерах
    me.setCell(def.cell);
    //Добавляем обработчик на сворачивание окна
    wrapButton.click(function(){
        me.close();
    });
    //Сохраняем в драге ссылку на себя
    drag.dialog = me;
    console.log(drag.dialog);
    return me;
}
/**
 * @return DOM элемент, на который повешен календарь.
 */
function MakeCalender(){
    return $('<span/>',{
        html:"calender"
    });
}
/**
 *
 * @return DOM элемент, который содержит кнопку с обработчиком handler и текстом text
 */
function MakeButton(param){
    param = $.extend({
        "node":"<span/>",
        "text":"button",
        "class":"button",
        handler: function(){return;}
    }, param);
    var temp = $(param.node,{
        css:param.css,
        "class":param.class
    }).append(param.text);
    temp.click(param.handler);
    return temp;
}
/**
 * Класс пользователя. В дальнейшем можно в него напихать всякого интересного
 * типа кэширования информации о пользователе, преобразования разных интервалов
 * вывода направлений, но сейчас всего лишь методы для показа.
 * @constructor
 */
function User(parameters){
    var me = {};
    me.id = parameters.id;
    //Ставим объекту в соответствие его айдишник
    me.valueOf = function(){
        return me.id;
    };
    //Создаем элемент отображения пользователя
    me.element = $('<tr/>',{
        'class':'userLine'
    });
    me.separator = $('<td/>',{
        'class':'tableSeparator',
        css:{disply:'none'}
    });
    me.element.append(me.separator);
    me.collectBaseInfo = function(){
        $.ajax({
            url: baseUrl + 'basicUserData',
            type:'post',
            data:{id:me.id},
            dataType:"json"
        }).done(function(data){
            //По непонятной причине возвращается массив, содержащий
            // нужный объект в качестве единственного элемента.
            data = data[0];
            InsertBasicData(me.separator, data);
        });
    };
    me.collectBaseInfo();
    return me;
}
/**
 * Перед separator вставляет данные по ячекам. Нужна для удобства генерации заголовка
 * @param separator
 * @param data
 */
function InsertBasicData(separator, data){
    separator.before($('<td/>',{
        html:data.fio,
        "class":"fio tableCell"
    })).before($('<td/>',{
        html:data.tel,
        "class":"tel tableCell"
    })).before($('<td/>',{
        html:data.email,
        "class":"email tableCell"
    }));
}