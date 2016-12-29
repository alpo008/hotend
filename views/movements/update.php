<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Movements */

$this->title = Yii::t('app', 'Update Movements');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Movements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="movements-update">

    <h1><?= Html::encode($this->title) . ' № ' . Html::encode($model->id) . " " . Yii::t('app', 'dated') . " " . $model->transaction_date ?></h1>

    <?= $this->render('_form', compact ("model", "lists")) ?>

</div>
