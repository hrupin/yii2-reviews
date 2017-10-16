<?php

/**
 * @var $this yii\web\View
 * @var $model array
 */

?>

<div class="table-responsive">
    <table class="table reviewsStatistics">
        <thead>
            <tr>
                <th><?= Yii::t('reviews', 'Criterion'); ?></th>
                <th><?= Yii::t('reviews', '% of answers "Yes"'); ?></th>
                <th><?= Yii::t('reviews', 'The number of responses'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($criterion as $key => $value){
                echo '<tr>';
                echo '<th class="row">'. $value['label'] .'</th>';
                echo '<td>'.round($value['statistic']).'</td>';
                echo '<td>'.$count.'</td>';
                echo '<tr>';
            }
            ?>
        </tbody>
    </table>
</div>
