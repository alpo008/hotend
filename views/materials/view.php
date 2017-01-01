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

<!-- Nav tabs -->

<div class="material-cart__nav-tabs">
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li role="presentation" class="active">
            <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
                <?php echo Yii::t('app', 'Stock History') ?>
            </a>
        </li>
        <li role="presentation">
            <a href="#location" aria-controls="location" role="tab" data-toggle="tab">
                <?php echo Yii::t('app', 'Stock Locations') ?>
            </a></li>
        <li role="presentation"><a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">История заявок</a>
        </li>

    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <div class="material-cart__movements-data text-center">
                <?php

                /** @var object $movements_data */

                if (count($movements_data) !== 0):
                    foreach ($movements_data as $movement):?>
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                <a href="/movements/<?php echo $movement->id ?>">
                                    <?php echo $movement->transaction_date; ?>
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                <?php echo $lists['directions'][$movement->direction]; ?>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                <?php echo $movement->from_to; ?>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                <?php echo $movement->qty . ' ' . $model->unit; ?>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                <?php echo $movement->person_in_charge; ?>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                <?php echo $movement->docref; ?>
                            </div>
                        </div>
                        <?php
                    endforeach;
                else:
                    echo Yii::t('app', 'No movements data available');
                endif;
                ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="location">
            <div class="material-cart__stocks-data text-center">
            <?php
                if (!!count($model->stocks)):
                    foreach ($model->stocks as $stock): ?>
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                <a href="/stocks/<?php echo $stock->id ?>">
                                    <?php echo $stock->placename?>
                                </a>
                            </div>

                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                <?php echo  $model->getQuantities()[$stock->id] . ' ' . $model->unit; ?>
                            </div>
                        </div>
                    <?php
                    endforeach;
                else:
                    echo Yii::t('app', 'No stock data available');
                endif;
            ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="orders">История заявок</div>
    </div>
</div>

