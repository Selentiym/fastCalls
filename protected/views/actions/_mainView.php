<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.06.2016
 * Time: 12:19
 */
define(LENGTH, 70);
/**
 * @type UserAction $model
 */
?>
<span class="username"><?php echo $model -> user -> showOneself(); ?></span>
<span><?php echo date(CDateTime::longFormat, strtotime($model -> time)); ?></span>
<span class="comment"><a href="#" title="<?php echo $model -> comment; ?>"><?php echo substr($model -> comment,0, LENGTH).((strlen($model -> comment) > LENGTH) ? '...' : ''); ?></a></span>
