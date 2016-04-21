/**
 * Created by user on 20.04.2016.
 */
obj1 = new FastDrag({
    nodeConfig:{
        'id':'drag1'
    }
});
obj2 = new FastDrag({
    nodeConfig:{
        'id':'drag2'
    }
});
obj1.newProp = 'newProp';
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
obj2 = new ActionAdd({

    dropConfig: {
        target: $('#parentDrag'),
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