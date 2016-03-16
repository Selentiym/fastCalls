<?php $this -> renderPartial('//navBar', array('user' => $user)); ?>    
<?php CustomFlash::ShowFlashes(); ?>

<div class="row">

    <div class="col-xs-4">
        <div class="panel panel-default">
            <div class="panel-heading">Пользователи</div>

            <div class="panel-body">
                <?php echo CHtml::link('Статистика', Yii::app() -> baseUrl.'/allstat',array('class'=>"list-group-item")); ?>
                <?php echo CHtml::link('Подробная информация о партнерах', Yii::app() -> baseUrl.'/userlist',array('class'=>"list-group-item")) ?>
                <?php echo CHtml::link('Действия', Yii::app() -> baseUrl.'/activeuserlist',array('class'=>"list-group-item")) ?>
                <?php echo CHtml::link('Добавить главного врача', Yii::app() -> baseUrl.'/create/maindoc',array('class'=>"list-group-item")) ?>
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
                <?php echo CHtml::link('Ошибки -> Телфин', Yii::app() -> baseUrl.'/errors/telfin',array('class'=>"list-group-item")) ?>
				<?php echo CHtml::link('Список звонков', Yii::app() -> baseUrl.'/allCalls',array('class'=>"list-group-item")); ?>
				<?php echo CHtml::link('Список смс', Yii::app() -> baseUrl.'/smsList',array('class'=>"list-group-item")); ?>
                <?php echo CHtml::link('Настройки', Yii::app() -> baseUrl.'/settings',array('class'=>"list-group-item")) ?>
				<?php //echo CHtml::link('Общие данные', Yii::app() -> baseUrl() . '/data'); ?>
                


            </div>
        </div>
    </div>
    <div class="col-xs-4">
        <div class="panel panel-default">
            <div class="panel-heading">Статистика по месяцам</div>

            <div class="panel-body">
				<?php
					$date = getdate();
					
					$start = mktime(0,0,0,$date['mon'],1,$date['year']);
					$data = new Data();
					$stop = $data -> dateAdd('m',1,$start) - 1;
					for ($i = 0; $i < 3; $i++) {
						$curdate = getdate($start);
						echo CHtml::link($curdate['month'],Yii::app() -> baseUrl.'/paystat/'.$start.'/'.$stop, array('class'=>"list-group-item"));
						$stop = $start - 1;
						$start = $data -> dateAdd('m', -1, $start);
					}
				?>
                


            </div>
        </div>
    </div>
</div>
</html>