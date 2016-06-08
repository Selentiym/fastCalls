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
    //@todo сделать окошко с обработкой результата действия (кнопка ОК/ОТМЕНА + поле отчета)
    Yii::app() -> getClientScript() -> registerScript('action_shortcut_open',"
        $('.action_shortcut .comment').click(function(e){
            e.preventDefault();
            var cont = $(this).parents('.action_shortcut');
            var fields = {
                arg: cont.attr('data-action')
            };
            window.open('".Yii::app() -> baseUrl."/site/MakeAction?' + $.param(fields),'','Toolbar=1,Location=0,Directories=0,Status=0,Menubar=0,Scrollbars=0,Resizable=0');
        });
    ",CClientScript::POS_READY);
?>