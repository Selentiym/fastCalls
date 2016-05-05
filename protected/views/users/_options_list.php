<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.04.2016
 * Time: 13:06
 */
?>
<form>
    <div class = "row">
        <label>Список параметров</label>
        <?php
        $data = UserOption::model() -> findAll();
        /**
         * @var array[] $htmlOptions_options - contains settings to be given to
         * CHtml::listOptions() method.
         */
        $htmlOptions_options = array();
        foreach($data as $option){
            /**
             * @var UserOption $option
             */
            $url = $option -> giveImageFolderAbsoluteUrl();
            $url .=  $option -> logo;
            //if the image is accessible, se the data-image attr to use it later in JS
            if (file_exists($url)) {
                $htmlOptions_options [$option -> id] = array('data-image' => $option -> giveImageFolderRelativeUrl() . $option -> logo, 'style' => 'color:blue');
            }
            $dataToSelect[$option -> id] = $option -> name;
        }
        /*$data = CHtml::listData($data, 'id', function($option){
            $img = $option -> logo ? CHtml::image(Yii::app() -> baseUrl . '/images/'. $option -> logo, '', array('style' => 'height:20px;')) : '';
            return $img . $option -> name;
        });*/
        echo CHtml::activeDropDownListChosen2(UserOption::model(),'id',$dataToSelect, array('name'=>'options', 'id' => 'options','style' => 'width:200px', 'options' => $htmlOptions_options),array(),'{
            templateResult: function(state){
                if (!state.element) {
                    return state.text;
                }
                //return state.text;
                return $("<img/>",{
                    src:$(state.element).attr("data-image"),
                    css: {
                        height:"20px",
                        margin:"2px",
                        "margin-right":"5px"
                    }
                }).after(state.text);
            }
        }');
        echo CHtml::button('Изменить', array('id' => 'changeOption'));
        echo CHtml::button('Создать', array('id' => 'createOption'));
        echo CHtml::button('Удалить', array('id' => 'deleteOption'));
        Yii::app()->clientScript->registerScript('clickScriptForOptions', "
			$('#changeOption').click(function(){
				location.replace('".$this -> createUrl('/OptionUpdate')."/'+$('#options').val());
			});
			$('#deleteOption').click(function(){
				location.replace('".$this -> createUrl('OptionDelete')."/'+$('#options').val());
			});
			$('#createOption').click(function(){
				location.replace('".$this -> createUrl('OptionCreate')."');
			}
		);
		");
        ?>
    </div>
</form>
