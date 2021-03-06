/**
 * Created by user on 20.04.2016.
 */
//$(document).ready(function(){
//});

/*console.log(MedPredDrag({
    MDid:562
}));*/
/*obj1 = new UserDrag({
    nodeConfig:{
        'id':'drag1'
    },
    users:[3,4,5]
});
obj2 = new UserDrag({
    nodeConfig:{
        'id':'drag2'
    },
    users: [1,2,3]
});*/
/*obj2 = new Drop({
    target: $('#parentDrag'),
    nodeConfig:{
        'class':'ui-corner-all',
        id:'drop1'
    },
    show: true,
    css:{
        width:'150px',
        height:'150px',
        border:'1px solid black',
        background:'#123'
    }
});*/
new ActionAdd({
    DOMparams:{
        nodeConfig:{
            element:$("#addAction")
        },
        css:{
            background:"#aaa",
            width:"500px"
        }
    },
    dropConfig: {
        nodeConfig: {
            element:$("#addDrop")
        }
    },
    resultDrop:{
        dropNumber:"0"
    }
});
/*new ActionAdd({
    dropConfig: {
        nodeConfig: {
            'class': 'ui-corner-all',
            id: 'drop1'
        },
        show: true,
        css: {
            width: '150px',
            height: '150px',
            border: '1px solid black',
            background: '#123'
        }
    }
});*/
new ActionSubtract({
    DOMparams:{
        nodeConfig:{
            element:$("#subtrAction")
        },
        css:{
            background:"#aaa",
            width:"500px"
        }
    },
    menuend:{
        nodeConfig: {
            'class': 'ui-corner-all actionDrag',
            element:$("#menuend")
        },
        show: true
    },
    subtrahend:{
        nodeConfig: {
            'class': 'ui-corner-all actionDrag',
            element:$("#subtrahend")
        },
        show: true
    },
    resultDrop: {
        nodeConfig:{
            element:$("#subtrRez")
        }
    }
});
$('#hidden').sidr({
    name: 'sidr-left',
    source:'#menu',
    displace:true,
    renaming: true//,
    //onOpenEnd :
});
$.sidr('open', 'sidr-left');

(function(){
    var mds = $("#sidr-id-MDsDrag");
    mds.click(function(){
        $.ajax({
            url:baseUrl + 'allMDs',
            dataType:'json'
        }).done(function(data){
            new UserDrag({
                name:'Медпреды',
                users:data.users
            });
        });

    });
    var allUsers = $("#sidr-id-AllDrag");
    allUsers.click(function(){
        new AllUsersDrag();
    });
    var MedPredList = $('#sidr-id-mainDoc');
    $('.md').dblclick(function () {
        var data = $(this).attr('data-gen');
        new MedPredDrag({
            MDid: data
        });
        //eval('var o1 = new ' + className +'({data:data});');
    });
    $('#sidr-left ul li').click(function (event) {
        $(this).children('.hideable').toggle(500);
        //console.log(event.target);
        event.stopPropagation();
    });
    $('#menu').remove();
    MedPredList.select2({
        placeholder:'Выберите медпреда'
    });
    MedPredList.on('select2:selecting', function(event) {
        //получили значение тега option, который был выбран.
        var value = event.params.args.data.id;
        //console.log(event.val);
        event.preventDefault();
        MedPredList.select2('close');
        MedPredDrag({
            MDid:value
        })
    });
    var usersSelect = $("#sidr-id-users_select");
    usersSelect.select2({
        placeholder:'Выберите пользователя'
    });
    usersSelect.on('select2:selecting', function(event) {
        //получили значение тега option, который был выбран.
        var value = event.params.args.data.id;
        //console.log(event.val);
        event.preventDefault();
        usersSelect.select2('close');
        new OneUserDrag({
            userId:value
        });
    });
    var SpecList = $('#sidr-id-specialities');
    SpecList.select2({
        placeholder:'Выберите специализацию'
    });
    SpecList.on('select2:selecting', function(event) {
        //получили значение тега option, который был выбран.
        var value = event.params.args.data.id;
        //console.log(event.val);
        event.preventDefault();
        SpecList.select2('close');
        SpecialityDrag({
            SpecId:value
        })
    });
    var OptionsList = $('#sidr-id-options');
    /*OptionsList.select2({
        placeholder:'Выберите свойство'
    });*/
    OptionsList.on('select2:selecting', function(event) {
        //получили значение тега option, который был выбран.
        var value = event.params.args.data.id;
        //console.log(event.val);
        event.preventDefault();
        OptionsList.select2('close');
        OptionDrag({
            OptionId:value
        })
    });
})();
/**
 * В процессе создания новых драгов нужно не забывать вешать на них обработчики.
 * @param el - новый, только что созданный, драг
 * @constructor
 */
function DragAddListeners(el){
    el.children('.DragName').click(function(e){
        if (e.altKey) {
            Base.prototype.getObject(el.data('object')).rename();
        }
    });
}
$('.DragName').click(function(e){
    alert('click');
});
//var human = new User({id:5});
