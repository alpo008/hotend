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
<div class="row">

    <div class="container">
        <div class = "stock-cart__materials-data table-bordered text-center">
            <?php

            /** @var object $movements_data */

            if (!!count($model->materials)):
                foreach ($model->materials as $material): ?>
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                            <a href="/materials/<?php echo $material->id ?>">
                                <?php echo $material->ref; ?>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                            <?php echo $material->name; ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                            <?php
                            $temp = array_column($material->locations, 'qty', 'materials_id');
                            echo $temp[$material->id] . ' ' . $material->unit;
                            ?>
                        </div>
                    </div>
                    <?php
                endforeach;
            else:
                echo Yii::t('app', 'No stock place data available');
            endif;
            ?>
        </div>
    </div>
</div>
