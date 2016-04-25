/**
 * Created by user on 20.04.2016.
 */
console.log($('.md'));
$('.md').click(function(){
    var data = $(this).attr('data-gen');
    new MedPredDrag({
        MDid:data
    });
    //eval('var o1 = new ' + className +'({data:data});');
});
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
});