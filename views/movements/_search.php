<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\MovementsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="movements-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'materials_id') ?>

    <?= $form->field($model, 'direction') ?>

    <?= $form->field($model, 'qty') ?>

    <?= $form->field($model, 'from_to') ?>

    <?php echo $form->field($model, 'transaction_date') ?>

    <?php echo $form->field($model, 'stocks_id') ?>

    <?php echo $form->field($model, 'person_in_charge') ?>
    
    <?php echo $form->field($model, 'person_receiver') ?>

    <?php echo $form->field($model, 'docref') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
