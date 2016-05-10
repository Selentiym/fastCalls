<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.04.2016
 * Time: 18:20
 */
Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/jquery-ui.min.js', CClientScript::POS_END);
Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/underscore-min.js', CClientScript::POS_END);
Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/jquery.sidr.min.js', CClientScript::POS_BEGIN);
Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/Classes.js', CClientScript::POS_END);
Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/scripts.js', CClientScript::POS_END);
Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/groupScenario.js', CClientScript::POS_END);
//Yii::app() -> clientScript -> registerCssFile(Yii::app() -> baseUrl . '/css/jquery-ui.css', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/jquery-ui.css');
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/jquery.sidr.light.min.css');
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/group.css');
?>
    <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css">-->
<!--<div id="sidr">
    <ul>
        <li><div class="gen md" data-gen="561" data-className="MedPredDrag">561</div></li>
        <li><div class="gen md" data-gen="562" data-className="MedPredDrag">562</div></li>
        <li><div class="gen md" data-gen="563" data-className="MedPredDrag">563</div></li>
    </ul>
</div>-->
<input type="hidden" id="hidden">
<div id="DialogContainer" style="position:absolute;top:0;left:0;width:100%;height:100%;">

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
<div id="menu" style="display:none">
    <ul style="padding-left: 10px">
        <li>
            MD's
            <?php
            $criteria = new CDbCriteria();
            $criteria -> compare('id_type', UserType::model() -> getNumber('mainDoc'));
            $MDs = User::model() -> findAll($criteria);
            echo "<select id='mainDoc'>";
            echo "<option></option>";
            foreach($MDs as $md){
                echo "<option value='{$md -> id}'>{$md -> fio}</option>";
            }
            echo "</select>";
            //CHtml::activeDropDownListChosen2(User::model(),'id',CHtml::giveAttributeArray($MDs,'fio'),array(),array(),'{}');
            //CHtml::activeDropDownListChosen2(UserMentor::model(), 'id',CHtml::listData(UserMentor::model() -> findAll(),'id','name'), array('class' => 'select2 createUser','name' => 'User[id_mentor]'), array(), '{}');
            ?>
        </li>
        <li>
            Options
            <?php
                //Потом навесим на этот селект select2() плагин. Важно: айдишник сменится, так как селект будет загружен в боковую панель sidr.
                UserOption::prettySelect(false, 'options', 'sidr-id-options','Выберите свойство');
                /*echo "<select id='options'>";
                echo "<option></option>";
                foreach(UserOption::model() -> findAll() as $opt) {
                    echo "<option value='{$opt -> id}'>{$opt -> name}</option>";
                }
                echo "</select>";*/
            ?>
        </li>
    </ul>
</div>