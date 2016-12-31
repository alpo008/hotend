<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use anmaslov\autocomplete\AutoComplete;

/* @var $this yii\web\View */
/* @var $model app\models\Movements */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="movements-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-9 col-md9\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-3 col-md-3  text-left'],
        ],
    ]); ?>

    <?php /*echo $form->field($model, 'materials_id')->textInput()*/ ?>
    <?php $trans_date = (isset ($model->transaction_date)) ? $model->transaction_date : date ('Y-m-d');?>

    <?php echo
        $form->field($model, 'longname')->textInput()->widget(
            AutoComplete::className(),
            [
                'attribute' => 'materials_id',
                'name' => 'Movements[materials_id]',
                'data' =>  $lists['materials'],
                'value' => (isset ($lists['materials'][$model->materials_id])) ? $lists['materials'][$model->materials_id] : '',
                'clientOptions' => [
                    'minChars' => 2,
                ]
            ])

    ?>

    <?= $form->field($model, 'direction')->dropDownList($lists['directions']) ?>

    <?= $form->field($model, 'qty')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'from_to')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transaction_date')->textInput(['value' => $trans_date]) ?>

    <?= $form->field($model, 'stocks_id')->textInput() ?>

    <?= $form->field($model, 'person_in_charge')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'person_receiver')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'docref')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save changes'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
