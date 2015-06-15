<?php
/* @var $this ApplicationsController */
/* @var $model Applications */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'appcode'); ?>
		<?php echo $form->textField($model,'appcode',array('size'=>16,'maxlength'=>16)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updated_at'); ?>
		<?php echo $form->textField($model,'updated_at'); ?>
	</div>


	<div class="row">
		<?php echo $form->label($model,'oauth_return_url'); ?>
		<?php echo $form->textField($model,'oauth_return_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'initialize_app_url'); ?>
		<?php echo $form->textField($model,'initialize_app_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'adduser_url'); ?>
		<?php echo $form->textField($model,'adduser_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deluser_url'); ?>
		<?php echo $form->textField($model,'deluser_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'provisionokmail_id'); ?>
		<?php echo $form->textField($model,'provisionokmail_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'addusermail_id'); ?>
		<?php echo $form->textField($model,'addusermail_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'delusermail_id'); ?>
		<?php echo $form->textField($model,'delusermail_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'version'); ?>
		<?php echo $form->textField($model,'version',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cfappframework_id'); ?>
		<?php echo $form->textField($model,'cfappframework_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cfappframework_attr'); ?>
		<?php echo $form->textField($model,'cfappframework_attr',array('size'=>16,'maxlength'=>16)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'appfile_path'); ?>
		<?php echo $form->textField($model,'appfile_path',array('size'=>60,'maxlength'=>256)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'appfile_type'); ?>
		<?php echo $form->textField($model,'appfile_type',array('size'=>16,'maxlength'=>16)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->