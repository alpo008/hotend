<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Stocks */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Stocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stocks-view">

    <h1><?= Yii::t('app', 'Stock place') . ' № ' .Html::encode($model->placename) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'placename',
            'description:ntext',
        ],
    ]) ?>

</div>


<?php

/** @var object $movements_data */

    if (!!count($model->materials)):?>

        <table class="table table-bordered">
            <caption><?php echo Yii::t('app', 'Stored materials')?></caption>
            <?php
                foreach ($model->materials as $material): ?>
                    <tr>
                        <td>
                            <a href="/materials/<?php echo $material->id ?>">
                                <?php echo $material->ref; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $material->name; ?>
                        </td>
                        <td>
                            <?php
                            $temp = array();
                            foreach ($material->locations as $v){
                                $temp[$v->stocks_id] = $v->qty;
                            }
                            echo $temp[$model->id] . ' ' . $material->unit;
                            ?>
                        </td>
                    </tr>
                    <?php
                endforeach;
            ?>
        </table>
    <?php
    else:
        echo Yii::t('app', 'No stock place data available');
    endif;
?>



