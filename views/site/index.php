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
                <p class="alert-warning"><?= Yii::t('app', 'DB was rebuilt'); ?></p>
                <?php else:?>
                 <p class="alert-success"><?= Yii::t('app', 'DB integrity is normal'); ?></p>
                <?php endif; ?>
                
                <h2><?= Yii::t('app', 'Urgent orders')?></h2>
            </div>
        </div>
    </div>

    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'materials.ref',
            'materials.name',
            'qty',
            'materials.qty',
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
                    return  Html::a('<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>', ['/orders/update/', 'id' => $searchModel->id], ['class' => 'profile-link']);
                },
            ],
        ]
    ]); ?>
    <?php Pjax::end(); ?>
    <?php if (!!$lists['downloads']):?>
        <?php foreach ($lists['downloads'] as $k => $v): ?>
            <div class="download-link">
                <?php echo Html::a($k, ['/site/download', 'name' => $v], ['class' => 'download-link',]) . '<br>'; ?>
            </div>
        <?php endforeach ?>
    <?php endif;?>

</div>
