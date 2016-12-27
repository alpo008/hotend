<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Materials */

$this->title = $model->ref;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Materials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="materials-view">

    <h1><?= Html::encode($model->ref) ?></h1>
    <h2><?= Html::encode($model->name) ?></h2>

    <div class="row material-cart">
        <div class="col-lg-3 material-cart__image">
            <?php
                $imgfile = '@web/photos/' . $model->ref . '.jpg';
                $imgfile = (file_exists($_SERVER['DOCUMENT_ROOT'] . '/photos/' . $model->ref . '.jpg')) ? $imgfile : '@web/photos/_no-image.jpg';


                echo Html::img($imgfile, ['alt' => $model->name,
                    'title' => $model->name,
                    'width' => '200'
                ]);
            ?>
        </div>


        <div class="col-lg-9 material-cart__attributes">
            <?php echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    //'ref',
                    //'name',
                    'type',
                    'gruppa',
                    [
                        'attribute' => 'stock_rest',
                        'value' => $model->qty . ' ' . $model->unit,
                    ],
                    [
                        'attribute' => 'stock_min',
                        'value' => $model->minqty . ' ' . $model->unit,
                    ],
                    //'qty' => function ($model){return($model->qty . ' ' . $model->unit);},
                    //'minqty',
                    //'unit',
                ],
            ])
            ?>
        </div>
    </div>
</div>
    
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

<div class="material-cart__movements-data">
    <?php

    /** @var object $movements_data */

    foreach ($movements_data as $movement):?>
        <div class="row">
            <div class="col-lg-2">
                <?php echo $movement->transaction_date;?>
            </div>
            <div class="col-lg-2">
                <?php echo $movement->direction;?>
            </div>
            <div class="col-lg-2">
                <?php echo $movement->from_to;?>
            </div>
            <div class="col-lg-2">
                <?php echo $movement->qty . ' ' . $model->unit;?>
            </div>
            <div class="col-lg-2">
                <?php echo $movement->person_in_charge;?>
            </div>
            <div class="col-lg-2">
                <?php echo $movement->docref;?>
            </div>
       </div>
    <?php endforeach;?>
</div>

