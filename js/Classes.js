parentDrag = $("#DragContainer");
parentAction = $("#ActionContainer");
baseUrl = '';
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
        //Создаем элемент, который будет отображать наш draggable
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
            me.element.position(position);
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
        console.log(pos);
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
            'class':'drag ui-corner-all ui-state-error'
        }
    };
    var param = $.extend(true, defaultParam, parameters);
    return Drag(param);
}
function UserDrag(parameters){
    var users = chArr(parameters.users);
    //alert(users);
    var name = parameters.name;
    if (!name) {
        name = 'Без названия';
    }
    var html = $('<h2/>',{
        text: name
    });
    html.after($('<div/>',{
        'class':'body'
    }).append($('<p/>',{
        text:users.toString()
    })));
    parameters.html = html;
    var me = FastDrag(parameters);
    me.element.addClass('userDrag');
    //Сохраняем юзеров, которых отображает данный блок.
    me.users = users;
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
            users:data
        });
    });
    param.users = users;
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
        if (me.resultDrop) {
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