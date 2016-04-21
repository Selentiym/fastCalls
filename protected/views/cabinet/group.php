<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.04.2016
 * Time: 18:20
 */
Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/jquery-ui.min.js', CClientScript::POS_END);
Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/underscore-min.js', CClientScript::POS_END);
Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/Classes.js', CClientScript::POS_END);
Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/scripts.js', CClientScript::POS_END);
Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/groupScenario.js', CClientScript::POS_END);
//Yii::app() -> clientScript -> registerCssFile(Yii::app() -> baseUrl . '/css/jquery-ui.css', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/jquery-ui.css');
?>
    <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css">-->
    <style type="text/css">
        .drag {font-size: x-large; border: thin solid black;
            width: 5em; text-align: center; padding:10px}
        #parentDrag {
            border:2px solid grey;
            border-radius:10px;
            width:500px;
            height:500px;
        }
    </style>
    <script type="text/javascript">
        $(function() {

            $('.drag').draggable({
                cancel:'.noDragDiv, .destroy',
                //helper:'clone',
                //opacity:'0.35',
                revertDuration:500,
                snap:true,
                snapMode: 'outer',
                snapTolerance:5,
                containment:"parent",
                stack:'.drag',
                distance:10,
                /*start:function(event, ui){
                 alert('start!');
                 }*/
            });

        });
        $(document).ready(function(){
            $('#destroy').click(function(){
                $('.drag').draggable('destroy');

            });
            $('#disable').click(function(){
                $('.drag').draggable('disable');
            });
            $('#enable').click(function(){
                $('.drag').draggable('enable');
            });
            obj3 = $('#hasObject').data('object');
        });
        /*function Obj(arg) {
         this . _arg = arg;
         }
         Obj.prototype.sayArg = function(){
         alert(this . _arg);
         }
         var obj1 = new Obj('arg1');
         var obj2 = new Obj('arg2');*/
        function ObjF(arg) {
            var _arg = arg;
            this . sayArg = function() {
                alert(_arg);
            }
        }
    </script>
<button id="kill">Kill!</button>
<button id="destroy">Destroy!</button>
<button id="disable">disable!</button>
<button id="enable">enable!</button>
<div id="parentDrag">
</div>
