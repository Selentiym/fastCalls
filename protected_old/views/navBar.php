<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <!--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="true">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>-->
            <a class="navbar-brand" href="<?php echo Yii::app() -> baseUrl.'/cabinet'; ?>">
                <img src="<?php echo Yii::app() -> baseUrl; ?>/images/logo.png" width="40" style="margin-top: -10px">
            </a>

        </div>

        <div class="navbar-collapse collapse in" id="bs-example-navbar-collapse-1" aria-expanded="true">
            <p class="navbar-text navbar-btn">
                <strong>
                    Партнерская программа записи на МРТ и КТ
                </strong>
            </p>
				<?php $adding = (Yii::app() -> user -> getId() == $user -> id) ? '' : '/'.$user -> username ; ?>
                <a href="<?php echo Yii::app() -> baseUrl; ?>/cabinet<?php echo $adding; ?>" class="btn btn-default navbar-btn <?php echo (isset($button) ? '' : 'active'); ?>">
                    <span class="glyphicon glyphicon-home"></span> Главная
                </a>
                <a href="<?php echo Yii::app() -> baseUrl . '/finance/'.$user -> id; ?>" class="btn btn-default navbar-btn <?php echo ($button == 'pay' ? 'active' : ''); ?>">
                    <span class="glyphicon glyphicon-rub"></span> Оплата
                </a>
                <a href="<?php echo Yii::app() -> baseUrl . $user -> giveUserNameForPage() . '/info'; ?>" class="btn btn-default navbar-btn <?php echo ($button == 'info' ? 'active' : ''); ?>">
                    <span class="glyphicon glyphicon-info-sign"></span> Информация
                </a>
				
				<a href="<?php echo Yii::app() -> baseUrl . $user -> giveUserNameForPage() . '/reviews'; ?>" class="btn btn-default navbar-btn <?php echo ($button == 'rev' ? 'active' : ''); ?>">
                    <span class="glyphicon glyphicon-comment"></span> Отзывы
                </a>
				
				<?php //if ($user -> id_type == UserType::model() -> getNumber('doctor')) : ?>
				<?php if ($user -> allowPatients) : ?>
				<a href="<?php echo Yii::app() -> baseUrl . $user -> giveUserNameForPage() . '/patients'; ?>" class="btn btn-default navbar-btn <?php echo ($button == 'patients' ? 'active' : ''); ?>">
                    <span class="glyphicon glyphicon-comment"></span> Пациенты
                </a>
				<?php endif; ?>
				<?php if (($user -> hasDirections())&&($user -> id_type == UserType::model() -> getNumber('doctor'))) : ?>
				<a href="<?php echo Yii::app() -> baseUrl . '/print/'.$user->username;?>" class="btn btn-default navbar-btn">
                    <span class="glyphicon glyphicon-print"></span> Печать направлений
                </a>
				<?php endif; ?>
                <ul class="nav navbar-nav navbar-right">

                    <li>
						<?php if ($user -> id != Yii::app() -> user -> getId()) : ?>
								<b class="navbar-text">
								Вы вошли как
								</b>
								<b class="navbar-text" style="color: green">
									<?php echo CHtml::link(User::model() -> findByPk(Yii::app() -> user -> getId()) -> fio,Yii::app() -> baseUrl . '/cabinet'); ?>
								</b>
								<b class="navbar-text">
								за пользователя: 
								</b>
						<?php endif; ?>
                        <b class="navbar-text" style="color: green">
                            
                                <?php $logged_in = User::model() -> findByPk(Yii::app() -> user -> getId()); ?><?php echo $user -> fio; ?>
                            
                        </b>
                    </li>
                    <li>
                        <a href="<?php echo Yii::app() -> baseUrl; ?>/logout">
                            <span class="fa fa-power-off"></span> Выйти
                        </a>
                    </li>
                </ul>

            

        </div>

    </div>
</nav>