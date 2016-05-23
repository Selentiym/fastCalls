<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.05.2016
 * Time: 19:16
 */
?>
<form method="post" action="<?php echo Yii::app() -> baseUrl; ?>/userAddProperty" style="padding-left:20px">
    <div class="row">
        <?php
        User::model() -> showUserList($_POST['userGroup']);
        ?>
    </div>
    <div class="row">
        <?php UserOption::model() -> prettySelect(false,null,false,false,true); ?>
        <div><input type="submit" value="Присвоить"/></div>
    </div>
</form>
