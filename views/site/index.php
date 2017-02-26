<?php

/* @var $this yii\web\View */

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'H O T E N D';

?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <?php
                if (!$message):?>
                <p class="alert-warning"><?= Yii::t('app', 'There is an urgent message'); ?></p>
                <?php else:?>
                 <p class="alert-success"><?= Yii::t('app', 'There are no urgent messages'); ?></p>
                <?php endif; ?>
                
                <h2><?= Yii::t('app', 'Urgent orders')?></h2>
            </div>
        </div>
    </div>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($searchModel)
        {
            if($searchModel->status == 1)  {
                return ['style' =>'background-color: #f2dede;'];
            }else{
                return NULL;
            }
        },
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'materials.ref',
                'value' => function ($searchModel){
                    if (isset ($searchModel->materials->ref)){
                        return Html::a($searchModel->materials->ref, ['/materials/update/', 'id' => $searchModel->materials_id], ['title' => Yii::t('app', 'Update Materials')]);
                    }else{
                        return NULL;
                    }
                },

                'format' => 'raw',
            ],

            'materials.name',

            [
                'attribute' => 'qty',
                'value' => function ($searchModel){
                    if (isset ($searchModel->materials->unit)){
                        return $searchModel->qty . ' ' . $searchModel->materials->unit;
                    }else{
                        return $searchModel->qty;
                    }
                },

                'format' => 'raw',
            ],

            [
                'attribute' => 'materials.qty',
                'value' => function ($searchModel){
                    if (isset ($searchModel->materials->unit)){
                        return $searchModel->materials->qty . ' ' . $searchModel->materials->unit;
                    }else{
                        return $searchModel->materials->qty;
                    }
                },

                'format' => 'raw',
            ],
            'order_date',
            [
                'attribute' => 'status',
                'value' => function ($searchModel) use ($lists){
                    if (isset ($searchModel->status)){
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
            'docref',
            [
                'label' => '',
                'format' => 'raw',
                'value' => function($searchModel){
                    return  Html::a('<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>', ['/orders/update/', 'id' => $searchModel->id], ['class' => 'profile-link', 'title' => Yii::t('app', 'Update Orders')]);
                },
            ],
        ]
    ]);
    ?>
    <?php Pjax::end(); ?>

    <?php if (!!$lists['downloads']):?>
        <div class="mess-board">
            <div class="download-links">
                <h4><?= Yii::t('app', 'Download') ?></h4>
                <?php foreach ($lists['downloads'] as $k => $v): ?>
                    <div class="download-link">
                        <?php echo Html::a($k, ['/site/download', 'name' => $v], ['class' => 'download-link',]) . '<br>'; ?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    <?php endif;?>

</div>
