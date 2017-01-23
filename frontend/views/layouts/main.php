<link rel="shortcut icon" href="../../web/favicon.ico" type="icon" />
<link rel="shortcut icon" href="../web/favicon.ico" type="icon" />

<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use kartik\nav\NavX;

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
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    $options = [
        'brandLabel' => 'Patient Tracking',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-right navbar-inverse navbar-fixed-top',
        ],
    ];
    if (Yii::$app->user->isGuest) {
        $items = [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
//            ['label' => 'Contact', 'url' => ['/site/contact']],
//            Yii::$app->user->isGuest ?
//                ['label' => 'Sign Up', 'url' => ['/site/signup']] :
//                false,
            ['label' => 'Sign up', 'url' => ['/site/signup']],
            ['label' => 'Login', 'url' => ['/site/login']]
        ];
    } else {
        $items = [
            [
                'label' => 'Account (' . Yii::$app->user->identity->username . ')',
                'items' => [
                    ['label' => 'Account',
                        'url' => ['site/account'],
                    ],
                    ['label' => 'Logout',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ],
                ],
            ],
        ];
    }

    NavBar::begin($options);
    echo NavX::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
        'activateParents' => true,
        'encodeLabels' => false
    ]);
    NavBar::end();

    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Yii::$app->name ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
