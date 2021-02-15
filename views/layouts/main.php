<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    $action_name = Yii::$app->controller->id;
    NavBar::begin([
        'brandLabel' => Yii::t('app', 'Home'),
        'brandUrl' => Yii::$app->homeUrl,
        'brandOptions' => ['class' => ($action_name === 'site') ? 'active' : ''],
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => Yii::t ('app', 'Materials'), 'url' => ['/materials'],
            'linkOptions' => ['class' => ($action_name === 'materials') ? 'active' : '']
            ],
            ['label' => Yii::t ('app','Movements'), 'url' => ['/movements'],
            'linkOptions' => ['class' => ($action_name === 'movements') ? 'active' : '']
            ],
            ['label' => Yii::t ('app','Stocks'), 'url' => ['/stocks'],
            'linkOptions' => ['class' => ($action_name === 'stocks') ? 'active' : '',]
            ],
            ['label' => Yii::t ('app','Orders'), 'url' => ['/orders'],
                'options' =>
                [
                    'class' => (empty(Yii::$app->user->identity['role']) || Yii::$app->user->identity['role'] == 'OPERATOR') ? 'hidden' : '',
                ],
            'linkOptions' => [
                'class' => ($action_name === 'orders') ? 'active' : '',
            ]
            ],
            Yii::$app->user->isGuest ? (
                ['label' => Yii::t ('app','Login'), 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->surname . ' ' . Yii::$app->user->identity->name .')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Ruscam-Ufa 2017</p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
