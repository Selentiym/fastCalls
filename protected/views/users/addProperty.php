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
        <?php
        $options = UserOption::model() -> findAll();
        $data = $_POST['data'];
        if (!is_array($data)) {
            $data = array();
            $data['dragName'] = '';
        }
        if ($data['dragName']) {
            $newOpt = new UserOption();
            $newOpt -> id = $data['dragName'];
            $newOpt -> name = $data['dragName'];
            array_unshift($options, $newOpt);
        } else {
            unset($data['dragName']);
        }
        UserOption::model() -> prettySelect($options,null,false,false,true,$data['dragName']);
        ?>
        <div><input type="submit" value="Присвоить"/></div>
    </div>
</form>
