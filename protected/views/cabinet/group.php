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
            width:1000px;
            height:1000px;
        }
        .actionDrag {
            display:inline-block;
            margin:10px;
        }
        #subtrAction {}
        #subtrAction div{
            display:inline-block;
            margin:10px;
            vertical-align:middle;
        }
        .drop {
            background:#123;
            width:200px;
            height:200px;
        }
    </style>
<div id="generators">
    <div class="gen md" data-gen="561" data-className="MedPredDrag">561</div>
    <div class="gen md" data-gen="562" data-className="MedPredDrag">562</div>
    <div class="gen md" data-gen="563" data-className="MedPredDrag">563</div>
</div>
<div id="parentDrag" style="position:relative">
    <div id="DragContainer" style="position:absolute;top:0;left:0;width:100%;height:100%;">

    </div>
    <div id="ActionContainer" style="position:absolute;top:0;left:0;z-index:-1">
        <div id="subtrAction">
            <div id="menuend" class="ui-corner-error drop">
            </div>
            <div style="font-size:50px">-</div>
            <div id="subtrahend" class="ui-corner-error drop">
            </div>
            <div style="font-size:50px">=</div>
            <div id="subtrRez" class="ui-corner-error drop">
            </div>
        </div>
    </div>
</div>
