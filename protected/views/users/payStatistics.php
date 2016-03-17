<?php
    $send = $_GET['send'] == 1;
    echo $model -> smsReportOnPeriod($_GET['from'], $_GET['to'], $send);
?>