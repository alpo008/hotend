<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MaterialsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Materials');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="materials-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Materials'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'ref',
            'name',
            'qty',
            'minqty',
            'unit',
            'type',
            'gruppa',
            [
                'label' => '',
                'format' => 'raw',
                'value' => function($model){
                    if ($model->minqty == 0 && $model->qty == 0){
                        return Html::img('@web/icons/off_led.jpg', ['alt' => 'OK', 'style' => 'width: 16px;']);
                    }elseif ($model->qty > $model->minqty){
                        return Html::img('@web/icons/green_led.jpg', ['alt' => 'OK', 'style' => 'width: 16px;']);
                    }elseif($model->qty == $model->minqty){
                        return Html::img('@web/icons/blinking_green_led.gif', ['alt' => 'MIN', 'style' => 'width: 18px;']);
                    }else{
                        return Html::img('@web/icons/blinking_red_led.gif', ['alt' => 'MIN', 'style' => 'width: 20px;']);
                    }

                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
