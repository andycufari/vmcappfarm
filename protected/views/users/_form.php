<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user'); ?>
		<?php echo $form->textField($model,'user',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'user'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'activated'); ?>
		<?php echo $form->textField($model,'activated'); ?>
		<?php echo $form->error($model,'activated'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'admin_level'); ?>
		<?php echo $form->textField($model,'admin_level'); ?>
		<?php echo $form->error($model,'admin_level'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_applications'); ?>
		(0 equivale al valor por defecto -> <?=$max_apps?>)
		<?php echo $form->textField($model,'max_applications'); ?>
		<?php echo $form->error($model,'max_applications'); ?>
	</div>

	<div class="row">

		<?php echo $form->labelEx($model,'max_applications_running'); ?>
		(0 equivale al valor por defecto -> <?=$max_apps_r?>)
		<?php echo $form->textField($model,'max_applications_running'); ?>
		<?php echo $form->error($model,'max_applications_running'); ?>
	</div>




	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->