<?php
/* @var $this CfappframeworksController */
/* @var $model Cfappframeworks */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cfappframeworks-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cfcode'); ?>
		<?php echo $form->textField($model,'cfcode',array('size'=>16,'maxlength'=>16)); ?>
		<?php echo $form->error($model,'cfcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ram'); ?>
		<?php echo $form->textField($model,'ram',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'ram'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'imgsrc'); ?>
		<?php echo $form->textField($model,'imgsrc',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'imgsrc'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->