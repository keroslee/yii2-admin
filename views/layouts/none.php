<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$this->registerCssFile('@web/css/public.css');
$this->registerCssFile('@web/css/login.css');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
    	<meta http-equiv="X-UA-Compatible" content="IE=7,IE=8,IE=9,IE=10"/>
  		<meta name="renderer" content="webkit|ie-comp|ie-stand"/>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
		<?= $content ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
