<?php
class CacheCommand extends CConsoleCommand
{
    public function actionClear() {
        Yii::app()->cache->flush();
        echo 'The cache is cleared' . PHP_EOL;
    }
 
    public function actionCheck() {
        echo 'Testing of ' . get_class(Yii::app()->cache) . PHP_EOL;
        Yii::app()->cache->set('test', 'test value');
        if (Yii::app()->cache->get('test') == 'test value') {
            echo 'Storing is valid' . PHP_EOL;
        } else {
            echo 'Storing is failed' . PHP_EOL;
        }
        Yii::app()->cache->delete('test');
        if (empty(Yii::app()->cache->get('test'))) {
            echo 'Deleting is valid' . PHP_EOL;
        } else {
            echo 'Deleting is failed' . PHP_EOL;
        }
    }
}
?>