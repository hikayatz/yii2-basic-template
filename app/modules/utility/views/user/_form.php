<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\User;
?>

<div class="app-form">
   <?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal'
	],
	'fieldConfig' => [
      
		'template' => "<div class='col-md-2 text-right'>{label}</div>${PHP_EOL}<div class='col-md-7'>{input}{error}</div>${PHP_EOL}",
	],

]); ?>
   <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

   <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

   <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

   <?php if (!$model->isNewRecord) : ?>
   <strong> Biarkan kosong jika tidak ingin mengubah password</strong>
   <div class="ui divider"></div>
      <?= $form->field($model, 'new_password') ?>
      <?= $form->field($model, 'repeat_password') ?>
      <?= $form->field($model, 'old_password')->textInput(['readonly' => true]) ?>
   <?php else: ?>
      <?= $form->field($model, 'new_password') ?>

   <?php endif; ?>
   <?= $form->field($model, 'status')->dropDownList([User::STATUS_ACTIVE=> "Active", User::STATUS_INACTIVE=> "Non Active"], ['option' => 'value']); ?>

   <div class="form-group">
      <div class="col-offset-sm-2">
         <?= Html::a(('<i class="glyphicon glyphicon-remove"></i> Cancel'), \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
         <?= Html::submitButton('<i class="glyphicon glyphicon-ok"> </i>' . ' Save', ['class' => 'btn btn-primary']) ?>
      </div>
   </div>

   <?php ActiveForm::end(); ?>
</div>