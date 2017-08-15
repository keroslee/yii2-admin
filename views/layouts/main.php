<?php

use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

list(,$url) = Yii::$app->assetManager->publish('@mdm/admin/assets');
$this->registerCssFile($url.'/main.css');
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
        <?php
        NavBar::begin([
            'brandLabel' => false,
            'options' => ['class' => 'navbar-inverse navbar-fixed-top'],
        ]);

        if (!empty($this->params['top-menu']) && isset($this->params['nav-items'])) {
            echo Nav::widget([
                'options' => ['class' => 'nav navbar-nav'],
                'items' => $this->params['nav-items'],
            ]);
        }
        
        //游客菜单不可见
        if (!Yii::$app->user->isGuest) {
        	echo Nav::widget([
        			'options' => ['class' => 'nav navbar-nav navbar-right'],
        			'items' => $this->context->module->navbar,
        	]);
        }

        NavBar::end();
        ?>

        <div class="container">
            <?= $content ?>
        </div>

        <footer class="footer">
		    <div class="container">
		        <p class="pull-left">&copy; My app <?= date('Y') ?></p>
		
		        <p class="pull-right"><?= Yii::powered() ?></p>
		    </div>
		</footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
