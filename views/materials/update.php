<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Materials */

$this->title = Yii::t('app', 'Update Materials').': '. $model->ref;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Materials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="materials-update container">

    <h1><?= Html::encode($this->title) ?></h1>
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
            <?php echo $this->render('_form', compact("model", "lists"));?>
        </div>
    </div>
</div>
