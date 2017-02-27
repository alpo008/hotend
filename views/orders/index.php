<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Orders'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($searchModel)
        {
            if($searchModel->status > 4)  {
                return ['style' =>'background-color: #def2de;'];
            }else{
                return NULL;
            }
        },
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'materials_id',
            'materials.ref',
            'materials.name',
            'qty',
            'order_date',
            //'status',
            [
                'attribute' => 'status',
                'value' => function ($searchModel) use ($lists){
                    if (isset ($searchModel->status) && ($searchModel->status >4)) {
                        return $lists['statuses'][$searchModel->status] . '<br />' . $searchModel->updated;
                    }elseif(isset ($searchModel->status)){
                            return $lists['statuses'][$searchModel->status];
                    }else{
                        return NULL;
                    }
                },

                'format' => 'raw',
                /**
                 * Отображение фильтра.
                 * Вместо поля для ввода - выпадающий список с заданными значениями directions
                 */
                'filter' => $lists['statuses'],
            ],
            // 'person',
            'docref',

            ['class' => 'app\models\custom\CustomActionColumn',
                'buttons' => ['delete' => function(){return false;}],
                'filter' => '<a href="/orders"><span class="glyphicon glyphicon-refresh" title="Сбросить фильтр"></span></a>'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
