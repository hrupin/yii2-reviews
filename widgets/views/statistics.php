<?php

/**
 * @var $this yii\web\View
 * @var $model array
 */

$diff = count($model, COUNT_RECURSIVE) - count($model);

if ($diff) {
    ?>
    <div class="table-responsive">
        <table class="table reviewsStatistics">
            <thead>
                <tr>
                    <th></th>
                    <?php
                    foreach ($model as $key => $value) {
                        echo '<th>' . $key . '</th>';
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($statistics as $key => $value) {
                        echo '<tr>';
                        echo '<th class="row">' . $value['name'] . '</th>';
                        foreach ($model as $k => $v) {
                            echo '<td>' . $v[$value['name']] . '</td>';
                        }
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
    <?php
} else {
    ?>
    <ul>
        <?php
            foreach ($model as $key => $value) {
                echo '<li>' . $key . ": " . $value . '</li>';
            }
        ?>
    </ul>
    <?php
}
