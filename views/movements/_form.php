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
    <?php
        $trans_date = (isset ($model->transaction_date)) ? $model->transaction_date : date ('Y-m-d');
        $is_operator = (Yii::$app->user->identity['role'] == 'OPERATOR');
        $l_data = (isset($l_data)) ? $l_data : false;
    ?>
    
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
                ],
            ])

    ?>

    <?php echo $form->field($model, 'direction')->dropDownList($lists['directions'],
        ['options' =>
            [
            '1' => ($is_operator || !!$l_data) ? ['disabled' => true, 'selected' => false] : ['disabled' => false],
            ]
        ]);
    ?>

    <?= $form->field($model, 'qty')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'from_to')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transaction_date' , ['options' => ['class' => 'hidden']])->textInput([
        'value' => $trans_date,
        ])
    ?>

    <?= $form->field($model, 'stocks_id')->dropDownList($lists['stocks'],
        [
            'disabled' => (!!$l_data || !$model->isNewRecord) ? true : false,
        ])
    ?>

    <?= $form->field($model, 'person_in_charge', ['options' => ['class' => 'hidden']])->textInput([
        'maxlength' => true,
        'value' => ($model->isNewRecord ) ?
            ((!!Yii::$app->user->identity) ? Yii::$app->user->identity->surname . ' ' . Yii::$app->user->identity->name : '') :
            $model->person_in_charge,
        ])
    ?>

    <?= $form->field($model, 'person_receiver')->textInput([
        'maxlength' => true,
        'value' => ($model->isNewRecord) ?
            ((!!Yii::$app->user->identity && $is_operator) ? Yii::$app->user->identity->surname . ' ' . Yii::$app->user->identity->name : '') :
            $model->person_in_charge,
        ])
    ?>

    <?= $form->field($model, 'docref')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save changes'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php if (!!$lists['locations']): ?>
    <div class="locations-list hidden">
        <?php foreach($lists['locations'] as $loc): ?>
            <div class="location">
                <?php foreach($loc as $k => $v): ?>
                    <div class = <?= $k ?>>
                        <?php if (!is_array($v)):?>
                            <?php echo $v;?>
                        <?php endif ?>
                    </div>
                <?php endforeach;?>
            </div>
        <?php endforeach;?>
    </div>
<?php endif;?>

<?php if (!!$lists['stocks']): ?>
    <div class="stocks-list hidden">
        <?php foreach($lists['stocks'] as $id => $stock): ?>
            <div id = <?= 'place_'.$id ?>>
                <?php if (!is_array($stock)):?>
                    <?php echo $stock;?>
                <?php endif ?>
            </div>
        <?php endforeach;?>
    </div>
<?php endif;?>


<div class="modal fade" id ="qty-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Остаток материала</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Данный материал отсутствует на складе!
                Форма будет закрыта автоматически.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
