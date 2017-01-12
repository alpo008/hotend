<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use anmaslov\autocomplete\AutoComplete;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'materials_id')->textInput() ?>

    <?php
    if ($model->isNewRecord){
        echo $form->field($model, 'materials_id')->textInput()->widget(
        AutoComplete::className(),
        [
        'attribute' => 'materials_id',
        'name' => 'Orders[materials_id]',
        'data' =>  $lists['materials'],
        'value' => (isset ($lists['materials'][$model->materials_id])) ? $lists['materials'][$model->materials_id] : '',
        'clientOptions' => [
        'minChars' => 2,
        ]
        ]);
    }else{
        echo $form->field($model, 'materials_id')
        ->textInput(
            [
                'value' => (isset ($lists['materials'][$model->materials_id])) ? $lists['materials'][$model->materials_id] : '', 'disabled' => true
            ]);
    }
    ?>

    <?= $form->field($model, 'qty')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_date')->textInput(['value' => date ('Y-m-d')]) ?>

    <?= $form->field($model, 'status')->dropDownList($lists['statuses'],
        ['options' => [
            '0' => ['disabled' => true],
            '1' => ['disabled' => true],
            '5' => ['disabled' => true]
        ]]) ?>

    <?= $form->field($model, 'person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'docref')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
