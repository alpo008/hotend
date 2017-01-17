<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Movements */

$this->title = Yii::t('app', 'Create Movements');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Movements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movements-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', compact ("model", "lists", "l_data")) ?>

</div>
