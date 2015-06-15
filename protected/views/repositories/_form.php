<?php
/* @var $this RepositoriesController */
/* @var $model Repositories */
/* @var $form CActiveForm */
?>
<script>
function changeType(){
	if($("#repotype").val() == "GIT"){
		$("#upload").show();
	}else{
		$("#upload").hide();
	}

}

$(document).ready(function(){
	changeType();
});

</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'repositories-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Campos con <span class="required">*</span> son requeridos.</p>
	<p ckass="note">NOTA: Si va a configurar un repositorio GIT y el mismo cuenta con una key ssh para autenticación, debe subir el archivo privado y no el público (.pub).</p>
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<label> Tipo de repositorio</label>
		<select id="repotype" name="Repositories[type]" onchange="changeType()">
			<option value="SVN" <? if(($model->type == "SVN")||(!$model->type)){ echo "selected";} ?>>SVN</option>
			<option value="GIT" <? if($model->type == "GIT"){ echo "selected";} ?>>GIT</option>
		</select>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'repo_url'); ?>
		<?php echo $form->textField($model,'repo_url',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'repo_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'repo_user'); ?>
		<?php echo $form->textField($model,'repo_user',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'repo_user'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'repo_passwd'); ?>
		<?php echo $form->textField($model,'repo_passwd',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'repo_passwd'); ?>
	</div>

	<div class="row" id="upload">
		<br>
		<input type="hidden" name="Repositories[key_file]" id="key_file" value="<?=$model->key_file?>" />
		<label>Seleccionar GIT Key SSH Privada (Ejemplo: "id_rsa")</label><br>
		<? if($model->key_file){
			echo "(".$model->key_file.")";
		}
		?>
		<? $this->widget('ext.EAjaxUpload.EAjaxUpload',
		array(
        'id'=>'uploadFile',
        'config'=>array(
               'action'=>Yii::app()->createUrl('repositories/upload'),
               'allowedExtensions'=>array(""),//array("jpg","jpeg","gif","exe","mov" and etc...
               'sizeLimit'=>100*1024,// maximum file size in bytes
               //'minSizeLimit'=>10*1024*1024,// minimum file size in bytes
               'onComplete'=>"js:function(id, fileName, responseJSON){ $('#key_file').val(responseJSON.filename); $('#subbutton').removeAttr('disabled');$('#subbutton').val('Guardar');}",
               //'messages'=>array(
               //                  'typeError'=>"{file} has invalid extension. Only {extensions} are allowed.",
               //                  'sizeError'=>"{file} is too large, maximum file size is {sizeLimit}.",
               //                  'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
               //                  'emptyError'=>"{file} is empty, please select files again without it.",
               //                  'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
               //                 ),
               //'showMessage'=>"js:function(message){ alert(message); }"
               'onSubmit'=>"js:function(id,filename){ $('#subbutton').attr('disabled', 'disabled'); $('#subbutton').val('Subiendo..');}",
              )
			)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar',array('id' => 'subbutton')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->