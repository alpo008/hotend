<?php
/**
 * Created by PhpStorm.
 * User: alpo
 * Date: 09.02.17
 * Time: 17:04
 */

$this->title = 'H O T E N D';
use yii\bootstrap\ActiveForm;
use anmaslov\autocomplete\AutoComplete;
?>

<div class="fastsearch-form">

<?php
/*    echo
    AutoComplete::className(
        [
            //'attribute' => 'materials_id',
            'name' => 'Movements[materials_id]',
            'data' =>  $lists['materials'],
            'value' => '',
            'clientOptions' => [
                'minChars' => 2,
            ],
        ]);*/

    echo AutoComplete::widget([
        'name' => 'link',
        'id' => 'fastsearch',
        'data' => $lists['materials'],
        'clientOptions' => [
            'minChars' => 2,
        ],
    ])
    ?>


</div>
<div id = "startsearch"><span class="glyphicon glyphicon-search"></span></div>

<?php
foreach ($lists['recent'] as $recent): ?>
<div>
    <?php var_dump($recent); ?>
</div>
<?php endforeach; ?>

<script type="text/javascript">

        var fsInput = document.getElementById('fastsearch');
        var fsStart = document.getElementById('startsearch');
        fsStart.onclick =(function () {
            var materialId = parseInt(fsInput.value);
            if (!!materialId) {
                location.replace("/materials/view/" + materialId);
            }
        })


</script>
