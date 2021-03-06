var key;
body = $("body");
body.keydown(function(e){
    key = e.keyCode;
});
body.keyup(function(e){
    key = null;
});
parentDrag = $("#DragContainer");
parentAction = $("#ActionContainer");
parentCont = $("#parentDrag");
if (!baseUrl) {
    baseUrl = '';
}
parentDialog = $("#DialogContainer");

//def <=> default
def = {};
def.cell = 'week';
def.cellsPlus = 0;
def.cellsMinus = 0;


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
     * Задаем функцию уничтожения`
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
    me.holder = false;
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
    //Чтобы не париться со смещением остальных драгов при удалении какого-то
    var cont = $('<div/>',{
        "class":"smallCont"
    });
    parentDrag.append(cont);
    var defaultParam = {
        html: '<span style="color:red">blabla</span>',
        target: cont,
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
    //Будущее тело драга
    var body = $('<div/>',{
        'class':'body'
    });
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
    var tagButton = $('<span/>',{
        "class": 'tagDrag'
    });
    var actionButton = $('<span/>',{
        "class": 'addAction'
    });
    var html = $('<div/>',{
        'class':'headMenu'
    }).append(actionButton).append(tagButton).append(enlargeButton).append(closeButton);
    html.after($('<h2/>',{
        "class":"DragName"
    }).append(name));
    html.after(body);
    parameters.html = html;
    var me = FastDrag(parameters);
    //Сохраняем юзеров, которых отображает данный блок.
    me.users = users;
    //Сохраняем ссылку на тело
    me.body = body;
    /**
     * Заново генерирует тело драга
     */
    me.remakeBody = function(){
        me.body.html($('<p/>',{
            text:me.users.join(', ')
        }));
    };
    me.remakeBody();
    tagButton.click(function(){
        me.addTag();
    });
    actionButton.click(function(){
        //На драге только неподробные кнопки.
        me.addAction();
    });
    //Вешаем обработчик закрытия окна. Только сейчас, чтобы можно было сохранить
    // в замыкание функцию закрытия.
    closeButton.click(function(){
        if (me.vars.notTrivial) {
            if (!confirm('Набор пользователей в выбранной группе нетривиален. Вы действительно хотите удалить элемент?')) {
                return;
            }
        }
        me.destroy(true);
    });
    //Запоминаем какое было задано имя.
    me.vars.name = name;
    me.element.addClass('userDrag');
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
            }
        } else {
            entered = newName;
        }
        //Изменяем имя только если оно не пустое!
        if (entered) {
            me.element.children('h2').html(entered);
            me.vars.name = entered;
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
    //Как-то криво, получается, работает наследование.
    // хотя как-то работает :D
    var oldDestr = me.destroy;
    me.destroy = function(doSplice){
        if (doSplice) {
            if (me.holder) {
                //Удаляем элемент из дропа, которому он принадлежит
                me.holder.guests.remove(me);
            }
        }
        //Удаляем запись о том, что этот драг где-то лежит
        if (me.dialog) {
            me.dialog.destroy();
        }
        oldDestr();
    };
    //Не забываем поставить обработчик на открытие окошка
    enlargeButton.click(function(){
        me.showDialog();
    });
    /**
     * Удаляет пользователя из драга
     * @param id
     */
    me.deleteUser = function(id){
        //Удаляем непосредтвенно из массива пользователей.
        me.users.remove(id);
        //Удаляем из диалогового окошка
        if (me.dialog) {
            me.dialog.deleteUser({id:id});
        }
        me.nowChanged();
    };
    /**
     * Выполняет стандартные действия при модификации драга
     */
    me.nowChanged = function(){
        me.vars.notTrivial = true;
        me.remakeBody();
        if (me.vars.name.charAt(me.vars.name.length - 1) == "*") {
            return;
        }
        me.rename(me.vars.name+"*");
    };
    /**
     * Вызывает всплывающее окно с добавлением нового свойства
     */
    me.addTag = function(){
        var fields = {
            selected: users.join(';'),
            "return":"_close",
            action: 2,
            data:{
                dragName:me.vars.name
            }
        };
        if (!me.vars.notTrivial) {
            //Если драг обычный, то спрашиваем, точно ли сохранить
            if (!confirm('Группа пользователей может быть получена из стандартной панели. Вы уверены, что хотите сохранить?')) {
                return;
            }
        }
        window.open(baseUrl + 'userCollection?' + $.param(fields),'','Toolbar=1,Location=0,Directories=0,Status=0,Menubar=0,Scrollbars=0,Resizable=0');
    };
    /**
     * Вызывает всплывающее окно с добавлением действия
     */
    me.addAction = function(inpAction){
        var fields = {
            //selected: users.join(';'),
            userGroup: users,
            "return":"_close",
            action: 3,
            data:{
                action:inpAction
            }
        };
        window.open(baseUrl + 'userCollection?' + $.param(fields),'','Toolbar=1,Location=0,Directories=0,Status=0,Menubar=0,Scrollbars=0,Resizable=0');
    };
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
function AllUsersDrag(){
    var me;
    $.ajax({
        url: baseUrl + 'allUsers',
        type:"POST",
        dataType:"json"
    }).done(function(data){
        me = new UserDrag({
            users:data.users,
            name:'Все пользователи'
        });
    });
    return me;
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
function OneUserDrag(parameters){
    var param = chObj(parameters);
    var id = param.userId;
    $.ajax({
        url: baseUrl + 'site/basicUserData',
        dataType: "json",
        type:"POST",
        data:{id:id}
    }).done(function(data){
        new UserDrag({
            name:data[0].fio,
            users:[id]
        });
    });
}
function SpecialityDrag(parameters){
    var param = chObj(parameters);
    var id = param.SpecId;
    if (!id) {
        id = param.data;
    }
    if (!parseInt(id)) { return {success:false}; }
    $.ajax({
        url: baseUrl + 'site/usersBySpeciality/' + id,
        dataType: 'json'
    }).done(function(data){

        console.log(data);
        var name = data.name;

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
    if (parameters.resultDrop.dropNumber) {
        //Хотим, чтобы результат сложения снова становился аргументом сложения
        me.resultDrop = me.drops[parameters.resultDrop.dropNumber];
    }
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
            console.log(drop.guests.length);
            _.each(drop.guests, function (obj) {
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
            "max-width":"100%",
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
    //Устанавливаем точку, относительно которой считать ячейки
    var date = new Date();
    me.setReper = function(time){
        //Если время передано в милисекундах
        if (time > 1354253200000) {
            //Время какое-то нарандом выбранное.
            time = parseInt(time / 1000);
        }
        me.reper = time;
    };
    me.reper = me.setReper(date.getTime());
    //Сохраняем в себе ссылку на DOM элемент внутренности - будет полезно.
    me.body = bodyTemp;
    //И ссылку на драг тоже.
    me.drag = drag;
    //Будет хранить ссылку на объект пользователя, который был выбран последним.
    me.lastSelected = null;

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
    }).append(MakeButton({text:"День",activeOn:def.cell,activeAttr: 1,handler:function(){
        me.setCell(1);
        toggleButtons.call(this);
    }})).append(MakeButton({text:"Неделя",activeOn:def.cell,activeAttr: 'week',handler:function(){
        me.setCell('week');
        toggleButtons.call(this);
    }})).append(MakeButton({text:"Месяц",activeOn:def.cell,activeAttr: 'month',handler:function(){
        me.setCell('month');
        toggleButtons.call(this);
    }}));
    var groupActionButtons = $("<div/>",{
        "class":"actionButtons"
    })
        .append(MakeButton({
            "class":"selectAll",
            handler: function(){
                me.selectAll();
            },
            text:$("<img/>",{
                src:baseUrl + "images/tick.png"
            })
        }))
        .append(MakeButton({
            "class":"unSelectAll",
            handler: function(){
                me.unSelectAll();
            },
            text:$("<img/>",{
                src:baseUrl + "images/remove.png"
            })
        }))
        .append(MakeButton({
            "class":"sendSms",
            "node":"<div/>",
            handler: function(){
                me.sendSms();
            },
            text:$("<img/>",{
                src:baseUrl + "images/mailicon.png"
            })
        }))
        .append(MakeButton({
            "class":"addProperty",
            "node":"<div/>",
            handler: function(){
                me.addUserProperty();
            },
            text:$("<img/>",{
                src:baseUrl + "images/tag.png"
            })
        }));
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
        MakeCalender(function(date){
            //Меняем реперную точку для показа ячеек.
            me.setReper(date.getTime());
            me.updateData();
        })
    ).after(
        cellSize
    ).after(
        MakeButton({text:'+ ячейка в прошлом', handler:function(){
            var num = prompt('Сколько ячеек добавить слева?',1);
            if (parseInt(num)) {
                me.addCellsLeft(parseInt(num));
            }/* else {
                alert(parseInt(num));
            }*/
        }})
    ).after(
        MakeButton({text:'+ ячейка в будущем', handler:function(){
            var num = prompt('Сколько ячеек добавить справа?',1);
            if (parseInt(num)) {
                me.addCellsRight(parseInt(num));
            }/* else {
                alert(parseInt(num));
            }*/
        }})
    ).after(groupActionButtons).after($("<div/>",{"class":"space"})).after($('<table/>',{
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
    //задаем стандартные значения параметров
    me.startCell = - def.cellsMinus;
    me.stopCell = def.cellsPlus;
    //Создаем из массива id пользователей массив объектов пользователей
    me.usersObj = _.map(drag.users, function(id){
        var temp = new User({id:id, dialog: me});
        me.tbody.append(temp.element);
        return temp;
    });
    /**
     * Возвращает объекты выбранных пользователей
     */
    me.selectedObj = function(){
        return _.where(me.usersObj,{selected:true});
    };
    /**
     * Возвращает массив идентификаторов выбранных пользователей
     */
    me.selectedIds = function(){
        return _.map(me.selectedObj(), function (obj) { return obj.id; });
    };
    /**
     * Открывает новое окно с интерфейсом отправки смс выбранным пользователям
     */
    me.sendSms = function(){
        //window.open(baseUrl + '/group');
        var users = me.selectedIds();
        window.open(baseUrl + 'userCollection?selected='+users.join(';')+'&action=1&return=_close','','Toolbar=1,Location=0,Directories=0,Status=0,Menubar=0,Scrollbars=0,Resizable=0,Width=693,Height=629');
    };
    /**
     * Открывает новое окно с интерфейсом добавления свойства
     * группе пользователей
     */
    me.addUserProperty = function(){
        //window.open(baseUrl + '/group');
        var users = me.selectedIds();
        window.open(baseUrl + 'userCollection?selected='+users.join(';')+'&action=2&return=_close','','Toolbar=1,Location=0,Directories=0,Status=0,Menubar=0,Scrollbars=0,Resizable=0');
    };
    /**
     *
     * @param from номер ячейки, с которой начинать составление заголовка
     * @param to на какой заканчивать
     * @param appendAfter
     */
    me.addStatHeader = function(from,to,appendAfter){
        if (!appendAfter) {
            appendAfter = me.separator;
        }
        //Сохраняем позицию, куда вставлять ответ сервера.
        me.appendAfter = appendAfter;
        //Сохраняем идентификатор
        me.awaiting = generateId();
        var data = {
            fromOffset:from,
            toOffset:to,
            cellType:me.cell,
            reper: me.reper,
            dataId: me.awaiting
        };
        $.ajax({
            url: baseUrl + 'cellHeaders',
            //type:"POST",
            data:data,
            dataType:"json"
        }).done(function(data) {
            _.each(data.response,function(el){
                var temp = $('<td/>',{"class":"data"});
                temp.append(el);
                me.appendAfter.after(temp);
                me.appendAfter = temp;
            });
        });
    };
    /**
     * Вовращает последнюю ячейку статистики или separator, если ее нет.
     */
    me.lastHeaderElement = function(){
        return me.tableHead.children().filter(":last");
    };
    /**
     * Добавляет number ячеек слева
     * @param number
     */
    me.addCellsLeft = function(number){
        var old = me.startCell - 1;
        me.startCell -= number;
        me.addStatHeader(me.startCell, old,me.separator);
        _.each(me.usersObj, function(user){
            user.collectStatInfo(me.startCell, old,'first');
        });
    };
    /**
     * Добавляет number ячеек справа
     * @param number
     */
    me.addCellsRight = function(number){
        var old = me.stopCell + 1;
        me.stopCell += number;
        me.addStatHeader(old, me.stopCell,me.lastHeaderElement());
        _.each(me.usersObj, function(user){
            user.collectStatInfo(old, me.stopCell,'last');
        });
    };
    /**
     * Чистит заголовок статистики
     */
    me.clearStatHeader = function(){
        me.separator.nextAll().remove();
    };
    me.updateData = function (){
        console.log('updateFunction');
        me.clearStatHeader();
        me.addStatHeader(me.startCell,me.stopCell,me.separator);
        _.each(me.usersObj,function(user){
            user.clearStat();
            user.collectStatInfo(me.startCell,me.stopCell);
        });
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
    /**
     * Удаляем первым делом DOM элемент!
     */
    me.destroy = function(){
        me.element.remove();
        me.drag.dialog = null;
    };
    /**
     *
     */
    me.selectAll = function () {
        _.each(me.usersObj, function(user){
            if (!user.selected) {
                user.toggleSelected();
            }
        });
        me.lastSelected = null;
    };
    me.unSelectAll = function () {
        _.each(me.usersObj, function(user){
            if (user.selected) {
                user.toggleSelected();
            }
        });
        me.lastSelected = null;
    };
    /**
     * Делает toggle пользователей по переданного.
     */
    me.selectUsersTo = function(user) {
        var indFrom = me.usersObj.indexOf(user);
        //alert(indFrom);
        var indFor = me.usersObj.indexOf(me.lastSelected);
        //alert(indFor);
        if (indFor > indFrom) {
            for (var i = indFrom; i < indFor; i++) {
                me.usersObj[i].toggleSelected();
            }
        } else {
            for (var i = indFrom; i > indFor; i--) {
                me.usersObj[i].toggleSelected();
            }
        }
        me.lastSelected = null;
    };
    /**
     * Удаляет пользователя с param.id или пользователя param.user.
     */
    me.deleteUser = function(param){
        param = chObj(param);
        var user = null;
        //Если задан сам объект, то ничего не нужно искать
        if (param.user) {
            user = param.user;
        } else if (param.id) {
            console.log('findById');
            //Ищем объект среди пользователей в диалоге
            user = _.findWhere({id:param.id});
        }
        //Если объект нашелся, то удаляем
        if (user.id) {
            me.usersObj.remove(user);
            //throw "stop";
            //Уничтожаем объект пользователя.
            user.destroy();
            //Обратная связь с драгом. Засчет того, что код находится в условии
            //нахождения объекта user среди объектов диалога, не будет рекурсии
            me.drag.deleteUser(user.id);
        }
    };
    return me;
}
/**
 * @param handler
 * @return DOM элемент, на который повешен календарь.
 */
function MakeCalender(handler){
    var cont = $('<input/>',{
        type:"text"
    });
    cont.datepicker({
        format: '@',
        //maxDate: moment(),
        dateLimit: { months: 3 },
        /*ranges: {
         'Сегодня': [moment()],
         'Вчера': [moment().subtract(1, 'days')],
         'Неделю назад': [moment().subtract(6, 'days')],
         'Месяц назад': [moment().subtract(29, 'days')],
         },*/
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-primary',
        cancelClass: 'btn-default',
        onSelect: function (dateStr, obj) {
            //end.subtract(1, 'days');
            var date = new Date(obj.selectedYear, obj.selectedMonth,obj.selectedDay);
            date.setHours(12);
            handler(date);
        }
    });
    return cont;
}
/**
 *
 * @return DOM элемент, который содержит кнопку с обработчиком handler и текстом text
 */
function MakeButton(param){
    param = $.extend({
        "node":"<span/>",
        "text":"button",
        handler: function(){return;}
    }, param);
    param.class += ' button';
    if (param.activeOn == param.activeAttr) {
        param.class = param.class + ' active';
    }
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
    //Сохраняем ссылку на окошко, которое контролирует пользователя
    me.dialog = parameters.dialog;
    //Создаем элемент отображения пользователя
    me.element = $('<tr/>',{
        'class':'userLine'
    });
    me.separator = $('<td/>',{
        'class':'tableSeparator',
        css:{display:'none'}
    });
    me.element.append(me.separator);
    /**
     * Функция получает и размещает на страничке текстовую информацию о пользователе
     */
    me.collectBaseInfo = function() {
        $.ajax({
            url: baseUrl + 'basicUserData',
            type:'post',
            data:{id:me.id},
            dataType:"json"
        }).done(function(data){
            //По непонятной причине возвращается массив, содержащий
            // нужный объект в качестве единственного элемента.
            var dataBig = data[0];
            dataBig.images = data.images;
            //Устанавливаем title для элемента, чтобы всегда иметь
            // возможность видеть имя юзера
            me.element.attr('title',dataBig.fio);
            InsertBasicData(me.separator, dataBig);
        });
    };
    me.lastStatElement = function(){
        return me.element.children().filter(':last');
    };
    /**
     * Создаем отображение для статистики одного пользователя
     * за определенный период. Нужна для удобства изменения.
     */
    me.makeStatBlock = function(el){
        if (!el.count) {return;}
        var common = el.count.common;
        var className = '';

        if (common > 0) {

        }
        var temp = $('<span/>');
        temp
            .append(renderDigit(50,common))
            .append(' -> ')
            .append(renderDigit(common,el.count.assigned))
            .append(' -> ')
            .append(renderDigit(common,el.count.verifyed))
        /*temp
            .append($('<span/>',{
                text:el.count.common,
                "class":renderClass(50,common)
            }))
            .append(' -> ')
            .append($('<span/>',{
                text: el.count.assigned,
                "class":renderClass(common,el.count.assigned)
            }))
            .append(' -> ')
            .append($('<span/>',{
                text:el.count.verifyed,
                "class":renderClass(common,el.count.verifyed)
            }));*/
        return temp;
    };
    /**
     * Обработчик выделения пользователя
     */
    me.toggleSelected = function(){
        if (me.selected === undefined) {
            me.selected = 0;
        }
        me.selected = !me.selected;
        console.log(me.selected);
        me.dialog.lastSelected = me;
        me.element.toggleClass("selected");
    };
    /**
     * Принимает в качестве аргументов UNIX метки времени, а также тип ячейки,
     * которая должна быть разбита.
     */
    me.preciseStat = function (from, to){
        me.awaiting = generateId();
        $.ajax({
            url: baseUrl + 'preciseStat',
            dataType:"json",
            //type:"POST",
            data:{
                from: from,
                to: to,
                oldCell:me.dialog.cell,
                id:me.id,
                dataId:me.awaiting
            }
        }).done(function(data){
            //alert(data.dataId);
            console.log(data);
            if (data.dataId == me.awaiting) {
                if (!me.dopData) {
                    //Создаем допстроку
                    me.dopData = $("<tr/>", {
                        "class":'dopData'
                    });

                    var tmp = $("<td/>",{
                        colspan: 1000
                    });
                    me.element.after(me.dopData);
                    me.dopData = me.dopData.append(tmp);
                    me.dopData = tmp;
                } else {
                    //Обнуляем содержиое допстроки.
                    me.dopData.html("");
                }
                _.each(data.response, function(el){
                    var temp = $("<div/>", {

                    });
                    temp.append($("<div/>").append(el.headerInfo));
                    temp.append(me.makeStatBlock(el));
                    me.dopData.append(temp);
                });
            } else {
                //alert("not needed data!");
            }
        });
    };
    /**
     * Функция обращается к серверу за статистикой по данному пользователю.
     * Note: тип ячейки сохранен в me.dialog.cell, то есть не передается
     * в функцию в явном виде, но от него зависит результат!
     * @param from - ячейка, с которой начать выдавать информацию
     * @param to - на которой закончить
     * @param appendAfter - DOM элемент, после которого вставить данные
     * По умолчанию принимается равным me.separator - невидимой разделяющей
     * ячейкой в строке соответсвующей пользователю.
     * Если в качестве appendAfter передано 'last', то присваиваем в конец.
     * Если 'first', то в начало
     */
    me.collectStatInfo = function(from,to, appendAfter){
        if (appendAfter === 'last'){
            appendAfter = me.lastStatElement();
        }
        if ((!appendAfter)||(appendAfter==='first')){
            appendAfter = me.separator;
        }
        //Сохраняем место, с которого начать присваивание.
        me.appendAfter = appendAfter;
        //Сохраняем идентификатор, по которому проверять пришедшие с сервера данные
        me.awaiting = generateId();
        var data = {
            id:me.id,
            //Тип ячейки получаем напрямую из окошка
            cellType:me.dialog.cell,
            fromOffset:from,
            toOffset:to,
            reper: me.dialog.reper,
            dataId: me.awaiting
        };
        $.ajax({
            url: baseUrl + 'userStatDumpJS',
            //type:"POST",
            data:data,
            dataType:"json"
        }).done(function(data){
            //Если что-то не так на серваке, он вернет {success:false}
            if (data.success === false) {
                alert('A mistake has occurred while requesting data from the server!');
                return;
            }
            //Случай если вдруг было нажато много разных кнопок быстро.
            // Принимаем только последнюю информацию.
            if (me.awaiting != data.dataId) {
                alert('Not the needed data.');
                return;
            }
            _.each(data.response, function(el){

                //Создаем новую ячейку
                var temp = $('<td/>', {
                    "class" : 'data'
                }).append(me.makeStatBlock(el));
                //вешаем обработчик расширенной статистики
                temp.click(function(e){
                    //alert('from ' + el.from + 'to '+el.to);
                    if ((!e.ctrlKey)&&(!e.metaKey)) { return; }
                    //Уточняем статистику.
                    if (me.dialog.cell != 1) {
                        me.preciseStat(el.from, el.to);
                    }
                });
                //Вставляем ячейку на страницу
                me.appendAfter.after(temp);
                //Сохраняем последний элемент цепочки
                me.appendAfter = temp;
            });
            console.log('User '+me.id + ':');
            console.log(data);
        });
    };
    /**
     * Функция удаляет всю статистику по пользователю
     */
    me.clearStat = function(){
        //Удаляем все элменты в строке после разделителя.
        me.separator.nextAll().remove();
        //Удаляем дополнительную статистику.
        if (me.dopData) {
            //dopData сейчас указывает на ячейку таблицы, а нужно удалить строку
            me.dopData.parent().remove();
            me.dopData = null;
        }
    };
    me.collectBaseInfo();
    //Вешаем обработчик на строку пользователя
    me.element.click(function(e){
        //Выбираем цепочку
        if (((e.shiftKey)&&(e.shiftKey))&&(me.dialog.lastSelected)) {
            me.dialog.selectUsersTo(me);
        } else if (e.altKey) {
            me.toggleSelected();
        } else {
            //key - внешняя переменная, содержащая код зажатой кнопки.
            // key - глобальна, объявляется в Classes.js
            if (key) {
                //Клавиша DEL
                if (key == 46) {
                    console.log(me);
                    me.destroy();
                }
            }

        }
    });
    me.destroy = function() {
        me.element.remove();
        if (me.dopData) {
            me.dopData.remove();
        }
        if (me.dialog) {
            var temp = me.dialog;
            me.dialog = null;
            temp.deleteUser({user:me});
        }
    };
    return me;
}
/**
 * Перед separator вставляет данные по ячекам. Нужна для удобства генерации заголовка
 * @param separator
 * @param data
 */
function InsertBasicData(separator, data){
    var firstCell = $('<td/>',{
        html:data.fio,
        "class":"fio tableCell"
    });
    var imagesCell = $("<td/>",{
        "class":"propertyIcon tableCell"
    });
    if (data.images) {
        _.each(data.images, function(img){
            if (!img) {
                return;
            }
            imagesCell.append($("<img/>",{
                src: img,
                alt: "Картинка свойства"
            }));
            //alert(img);
        })
    }
    separator.before(firstCell).before($('<td/>',{
        html:data.tel,
        "class":"tel tableCell"
    })).before($('<td/>',{
        html:data.email,
        "class":"email tableCell"
    })).before(imagesCell);
}
function renderClass (top, val) {
    if (top == 0) {
        return '';
    }
    if (val == 0) {
        return '';
    }
    var className = '';
    var otn = val / top;
    if (otn > 0.2) {
        className = 'little';
    }
    if (otn > 0.5) {
        className = 'half';
    }
    if (otn > 0.75 ) {
        className = 'more075';
    }
    if (!className) {
        className = 'poor';
    }
    return className;
}
function renderDigit(top,val){
    if (!val) {
        val = 0;
    }
    return $("<span/>",{
        text: val,
        "class":renderClass(top,val)
    });
}