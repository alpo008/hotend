<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

$this->title = Yii::t('app', 'Order') . ' № ' .$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-view">

    <h1><?= $this->title . ' ' . Yii::t('app', 'dated') . ' ' . $model->order_date ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php /* Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) */?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'materials.ref',
            'materials.name',
            'qty',
            'order_date',
            [
                'attribute' => 'status',
                'value' => $lists['statuses'][$model->status],
            ],
            'person',
            'docref',
            'updated',
        ],
    ]) ?>

</div>
