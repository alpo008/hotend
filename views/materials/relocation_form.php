<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RelocationForm */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('app', 'Relocation').': '. $model->material->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Materials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->material->name, 'url' => ['view', 'id' => $model->material->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Relocation');

?>

<div class="relocation-form">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php $form = ActiveForm::begin([
        //'enableAjaxValidation' => true,
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-9 col-md9\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-3 col-md-3  text-left'],
        ],
    ]);
    ?>

    <?= $form->field($model, 'stocks_id_old')->dropDownList($model->oldLocationsList, [
            'value' => $model->stocks_id_old,
            'readonly' => !!$model->stocks_id_old
        ]
    ) ?>

    <?= $form->field($model, 'qty')->textInput([
            'type' => 'number', 'min' => 1, 'max' => $model->material->qty
    ]) ?>

    <?= $form->field($model, 'stocks_id_new')->dropDownList($model->newLocationsList) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Relocate'), [
                'class' => 'btn btn-primary',
                'style' => 'float:right;'
        ]) ?>
    </div>

    <?php ActiveForm::end();?>

</div>
