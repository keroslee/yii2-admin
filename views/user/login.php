<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Login */

$this->title = Yii::t('rbac-admin', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-wrap">
	<div class="login-box">
		<div class="login-logo"></div>
		<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
		<div class="login-con">
			<div class="login-input login-name">
				<span></span>
				<div class="login-icon">
					<em class="login-bg"></em>
				</div>
				<?= $form->field($model, 'username')?>
			</div>
			<div class="login-input">
				<span></span>
				<div class="login-icon">
					<em class="login-bg login-bg-psd"></em>
				</div>
				<?= $form->field($model, 'password')->passwordInput()?>
			</div>
			<?php //= $form->field($model, 'rememberMe')->checkbox() ?>
			<?= Html::submitButton(Yii::t('rbac-admin', 'Login'), ['class' => 'button', 'name' => 'login-button'])?>
		<?php ActiveForm::end(); ?>
		</div>
	</div>

</div>

            
            