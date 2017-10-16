<?php

/**
 * @var $this yii\web\View
 * @var $model array
 */

$this->registerJs('
$("#viewCountBlock").click(function(){
    if ($(".reviewsCountAnswer").css("display") == "block"){
       $(".reviewsCountAnswer").slideUp(500);
   }
   else{
      $(".reviewsCountAnswer").slideDown(500);
   }
});
');

?>
<a href="javascript:void(0);" id="viewCountBlock">статистика по ответам</a>
    <div class="table-responsive reviewsCountAnswer" style="display: none">
        <table class="table">
            <thead>
            <tr>
                <th>Страницы отзывов</th>
                <th>Вопросы</th>
                <th>Варианты</th>
                <th>Кол-во ответов</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($data as $k => $i) {
                        echo '<tr class="success">';
                        if(isset($label[$k])){
                            echo '<td colspan="4">'.$label[$k].'</td>';
                        }
                        else{
                            echo '<td colspan="4">'.$k.'</td>';
                        }
                        echo '</tr>';
                        foreach ($i as $kk => $ii) {
                            echo '<tr class="active">';
                            echo '<td>&nbsp;</td>';
                            echo '<td>'.$ii['label'].'</td>';
                            echo '<td>&nbsp;</td>';
                            echo '<td>&nbsp;</td>';
                            echo '</tr>';
                            foreach ($ii['data'] as $kkk => $iii) {
                                if($ii['count'][$kkk] > 0){
                                    echo '<tr>';
                                    echo '<td>&nbsp;</td>';
                                    echo '<td>&nbsp;</td>';
                                    echo '<td>'.$iii.'</td>';
                                    echo '<td>'.$ii['count'][$kkk].'</td>';
                                    echo '</tr>';
                                }
                            }
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>