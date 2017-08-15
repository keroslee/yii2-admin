<?php
/* @var $this \yii\web\View */
/* @var $content string */

$controller = $this->context;
//游客菜单不可见
if (Yii::$app->user->isGuest) {
	$menus=[];
	$this->params['nav-items'] = $menus;
	$this->params['top-menu'] = false;
}else{
	$menus = $controller->module->menus;
	$route = $controller->route;
	foreach ($menus as $i => $menu) {
		$menus[$i]['active'] = strpos($route, trim($menu['url'][0], '/')) === 0;
	}
	$this->params['nav-items'] = $menus;
	$this->params['top-menu'] = true;
}
?>
<?php $this->beginContent($controller->module->mainLayout) ?>
<div class="row">
    <div class="col-sm-12">
        <?= $content ?>
    </div>
</div>
<?php $this->endContent(); ?>
