<?php if ((($user -> conditions)&&($user -> conditions != 0))||($user -> conditions_add)) : ?>	
<div class="well">
<h3 style="margin-top: 0px">Условия работы</h3>
<?php if ($user -> conditions_add) : ?>
<?php echo $user -> conditions; ?> рублей за подтвержденную запись<br/>
Оплата докторам: <?php echo $user -> conditions_add; ?>руб
<?php else: ?>
<?php echo $user -> conditions; ?>руб за запись.
<?php endif; ?>
</div>
<?php endif; ?>