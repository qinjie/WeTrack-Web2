<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
<link rel="shortcut icon" href="../../web/favicon.ico" type="icon" />
<link rel="shortcut icon" href="../web/favicon.ico" type="icon" />

<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

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
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems = [
            [
                'label' => 'Residents',

                'items' => [
                    ['label' => 'All Residents',
                        'url' => ['/resident/index'],
                    ],
                    ['label' => 'Missing Residents',
                        'url' => ['/resident/show-missing'],
                    ],
                    ['label' => 'Resident Relative',
                        'url' => ['/user-resident/index'],
                    ],

                ],

            ],
            ['label' => 'Beacons', 'url' => ['/beacon/index']],
            ['label' => 'Location History', 'url' => ['/location-history/index']],
            ['label' => 'Users',
                'url' => ['/user/index'],
            ],
            ['label' => 'Locators', 'url' => ['/locator/index']],
        ];
        $menuItems[] = [
            'label' => Yii::$app->user->identity->username ,
            'items' => [
                ['label' => 'Account',
                    'url' => ['site/account'],
                ],
                ['label' => 'Logout',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
            ],
        ];

    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
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
        <p class="pull-left">&copy; <?= Yii::$app->name ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
