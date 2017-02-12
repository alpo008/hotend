<?php
/**
 * Created by PhpStorm.
 * User: alpo
 * Date: 09.02.17
 * Time: 17:04
 */

$this->title = 'H O T E N D';
use anmaslov\autocomplete\AutoComplete;
use yii\bootstrap\Html;

?>


<div class="search-container">
    <?php
    echo AutoComplete::widget([
        'name' => 'link',
        'id' => 'fastsearch',
        'data' => $lists['materials'],
        'clientOptions' => [
            'minChars' => 2,
        ],
    ])
    ?>



    <div id="startsearch" class="fastsearch-link"><span class="glyphicon glyphicon-arrow-right"></span></div>

    <div class="search-thumbs">
        <?php
        foreach ($lists['recent'] as $recent): ?>
            <div class="search-thumb">
                <div class="search-thumb-txt">
                    <?= Html::a($recent['name'], ['materials/view', 'id' => $recent['id']], ['class' => 'fastsearch-link']) ?>
                </div>
                <?php
                    $imgPath = Yii::$app->basePath . '/web/photos/';
                    if (is_file($imgPath . $recent['ref'] . '.jpg')) {
                        echo Html::img('@web/photos/' . $recent['ref'] . '.jpg', ['alt' => $recent['name'], 'title' => $recent['name']]);
                    } else {
                        echo Html::img('@web/photos/_no-image.jpg', ['alt' => $recent['name'], 'title' => $recent['name']]);
                    }
                ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script type="text/javascript">
    "use strict";
    var fsInput = document.getElementById("fastsearch");
    var fsStart = document.getElementById("startsearch");
    fsInput.classList.toggle('list-group-item-info');
    fsStart.onclick = (function () {
        var materialId = parseInt(fsInput.value);
        if (!!materialId) {
            location.replace("/materials/view/" + materialId);
        }else{
            fsInput.classList.toggle('list-group-item-warning');
        }
    });
</script>
