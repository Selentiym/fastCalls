<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.06.2016
 * Time: 11:45
 */
/**
 * @type iUserAction[] $actions - массив действий, которые нужно отобразить.
 */
?>
<div class="action_shortcuts">
    <?php
        foreach($actions as $act){
            echo "<div class='action_shortcut ".get_class($act)."' data-action='".$act -> id."'>";
            foreach($act -> giveViews() as $view){
                $this -> renderPartial($view, array('model' => $act));
            }
            echo "</div>";
        }
    ?>
</div>
<?php
    Yii::app() -> getClientScript() -> registerScript('action_shortcut_open',"
        $('.action_shortcut .comment').click(function(e){
            e.preventDefault();
            var cont = $(this).parents('.action_shortcut');

        });
    ",CClientScript::POS_READY);
?>