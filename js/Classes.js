var parentDrag = $("#DragContainer");
var parentAction = $("#ActionContainer");
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
    if (!node) {
        node = '<div/>';
    }
    //Если айдишник элемента не задан внешне, то назначаем его по умолчанию.
    if (!nodeConfig.id) {
        nodeConfig.id = 'el' + me.id;
    }
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
    me.element.offset(position);
    //Сохраняем на DOM-елементе ссылку на объект.
    me.element.data('object',me.id);
    //alert(me.id);
    /**
     * Добавляем элемент на страницу, если нужно.
     */
    if (show) {
        if (!target) {
            target = $('body');
        }
        target.append(me.element);
    }

    /**
     * Задаем функцию уничтожения
     */
    me.destroy = function(){
        ind = _.indexOf(Base.prototype.getObject(me.id));
        if (!ind) { return false; };
        Base.prototype.objects.splice(ind, 1);
        //delete me.objects[me.getObject(me.id)];
        me.element.remove();
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
    me.element.addClass('prefix_drag');
    me.element.draggable(chObj(config));
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

    me.guests = [];
    /**
     * Задаем обработчик попадания элемента в droppable
     */
    me.element.on("drop", function( event, ui ) {
        var el = Base.prototype.getObject(ui.draggable.data('object'));
        console.log(me + ': ' + el);
        //Сохраняем элемент, который был вброшен, в массив вброшенных.
        me.guests.push(el);
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
    var me =  Drag(param);
    return me;
}
function UserDrag(parameters){
    var users = chArr(parameters.users);
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
function ActionDrop(parameters){
    var me =  Drop(parameters);
    me.action = parameters.action;
    //При попадании нового Drag'а в ActionDrop пытаемся совершить действие.
    me.element.on("drop",function(){
        me.action.go();
    });
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
    me.go = function(){
        return me.validate();
    };
    me.validate = function (){
        return false;
    };
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
            var users = [];
            var text = "";
            var i = 0;
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
            var rez = UserDrag({
                //html: 'sum: ' + text
                users:users,
                name:'sum'
            });
            return true;
        }
        return false;
    };
    return me;
}