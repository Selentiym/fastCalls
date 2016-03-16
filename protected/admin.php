<?php $this -> renderPartial('//navBar', array('user' => $user)); ?>    
    

<div class="row">

    <div class="col-xs-4">
        <div class="panel panel-default">
            <div class="panel-heading">Пользователи</div>

            <div class="panel-body">
                <?php echo CHtml::link('Статистика', Yii::app() -> baseUrl.'/allstat',array('class'=>"list-group-item")); ?>
                <?php echo CHtml::link('Подробная информация о партнерах', Yii::app() -> baseUrl.'/userlist',array('class'=>"list-group-item")) ?>

    <span class="list-group-item">Статистика по партнерам за <Месяц></span>
				<?php echo CHtml::link('Входы в Партнерский кабинет', Yii::app() -> baseUrl.'/entries',array('class'=>"list-group-item")) ?>
            </div>
        </div>
    </div>

    <div class="col-xs-4">
        <div class="panel panel-default">
            <div class="panel-heading">Данные</div>

            <div class="panel-body">

                <?php echo CHtml::link('Общие данные', Yii::app() -> baseUrl . '/data',array('class'=>"list-group-item")); ?>
                <?php echo CHtml::link('Ошибки', Yii::app() -> baseUrl.'/errors',array('class'=>"list-group-item")) ?>
				<?php //echo CHtml::link('Общие данные', Yii::app() -> baseUrl() . '/data'); ?>
                


            </div>
        </div>
    </div>

</div>
</html>