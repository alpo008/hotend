<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MovementsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Movements');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movements-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Movements'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'transaction_date',
            //'materials_id',
            //'materials.ref',
            'materials.name',
            'qty',
            'stocks.placename',

            [

                'attribute' => 'direction',
                'value' => function ($searchModel) use ($lists){
                    if (isset ($searchModel->direction)){
                    return $lists['directions'][$searchModel->direction];
                    }else{
                        return NULL;
                    }
                },

                'format' => 'raw',
                /**
                 * Отображение фильтра.
                 * Вместо поля для ввода - выпадающий список с заданными значениями directions
                 */
                'filter' => $lists['directions'],
            ],

            'from_to',
            // 'stocks_id',
            // 'person_in_charge',
            // 'person_receiver',
            // 'docref',

            ['class' => 'app\models\custom\CustomActionColumn',
                'buttons' => ['delete' => function(){return false;}, 'update' => function(){return false;}],
                'filter' => '<span class="glyphicon glyphicon-filter"></span>'
            ],


        ],
    ]); ?>
<?php Pjax::end(); ?></div>
