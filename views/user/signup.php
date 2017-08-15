<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\components\Helper;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Signup */

$this->title = Yii::t('rbac-admin', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>
    <?= Html::errorSummary($model)?>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'user_name') ?>
                <?= $form->field($model, 'user_email') ?>
                <?= $form->field($model, 'user_tel') ?>
                <?= $form->field($model, 'user_passwd')->passwordInput() ?>
                <?= $form->field($model, 'shop_id')->dropDownList(Helper::shopMap()) ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('rbac-admin', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
