<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.06.2016
 * Time: 19:07
 */
/**
 * @type UserAction $model
 * @type CController $this
 */
?>
<div class="container-fluid">
    <div>
        <form method="post" action="addActionReport?arg=<?php echo $model -> id; ?>">
            <div class="row">
                <textarea name="report" placeholder="Отчет по действию"></textarea>
            </div>
            <div class="row">
                <input type="submit" name="done" value="ОК"/>
                <input type="button" value="Отмена" onClick="window.close()"/>
            </div>
            <div class="row">
                <?php $this -> renderPartial('//actions/picker', array('button' => false, 'name' => 'postpone'),false,true); ?>
                <input type="submit" name="postponeSubmit" value="Отложить"/>
            </div>
        </form>
    </div>
    <div>
        <?php $this -> renderPartial('//actions/show', array('model' => $model)); ?>
    </div>
</div>