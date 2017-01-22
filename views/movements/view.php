<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Movements */

$this->title = $model->materials->ref . ' - ' .$model->transaction_date;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Movements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movements-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            //'materials_id',
            'materials.ref',
            'materials.name',
            [
                'attribute' => 'direction',
                'value' => $lists['directions'][$model->direction],
            ],
            'qty',
            'from_to',
            'transaction_date',
            'stocks_id',
            'person_in_charge',
            'person_receiver',
            'docref',
        ],
    ]) ?>

</div>
