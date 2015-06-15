<?php
/* @var $this EnviromentsController */
/* @var $model Enviroments */
/* @var $form CActiveForm */
?>
<script>
var response = false;
$(document).ready(function(){
	$("#submit").click(function(){
		
		preloader_on();
		$.ajax({
			type: "POST",
            url: '<? echo Yii::app()->createUrl('enviroments/checkenv'); ?>',
            async: false,
            data:{endpoint:$("#endpoint").val(),user:$("#user").val(),pass:$("#pass").val()},
            success: function(data){
    			preloader_off();
    			if(data == 1){

    				response = true;
    			}else{
    				$("#errores").html('<div class="flash-error">Check enviroment data. Impossible to connect.</div>');
    			
    			}
	   	}});
	   	return response;
	});
	$("#endpoint").blur(function(){
		var miValor = $("#endpoint").val();
		miValor=miValor.replace('http://','');
		miValor=miValor.replace('https://','');
		$("#endpoint").val(miValor);
	});

});


</script>
<div id="errores">

</div>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'enviroments-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span>are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'endpoint'); ?>
		<?php echo $form->textField($model,'endpoint',array('size'=>60,'maxlength'=>512,'id'=>'endpoint')); ?>
		<?php echo $form->error($model,'endpoint'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ssl'); ?>
		<?php echo $form->checkBox($model,'ssl'); ?>
		<?php echo $form->error($model,'ssl'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user'); ?>
		<?php echo $form->textField($model,'user',array('size'=>60,'maxlength'=>256,'id'=>'user')); ?>
		<?php echo $form->error($model,'user'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pass'); ?>
		<?php echo $form->passwordField($model,'pass',array('size'=>60,'maxlength'=>256,'id'=>'pass')); ?>
		<?php echo $form->error($model,'pass'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('id'=>'submit')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->