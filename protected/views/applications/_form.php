<?php
/* @var $this ApplicationsController */
/* @var $model Applications */
/* @var $form CActiveForm */
?>

<script>
var selected_zip;
var filefile;
$(document).ready(function(){
	$('#cfappframework').ddslick({
    	imagePosition:"right",
    	height: "200px",
    	onSelected: function(data){
        	//alert(data.selectedData.value);
        	$("#cfappframework_id").val(data.selectedData.value);
        	$.post('<? echo Yii::app()->createUrl('cfappframeworks/AjaxLoadModel'); ?>',{id:data.selectedData.value},function(data){
        		
        		var obj = jQuery.parseJSON(data);
        		var ram_array=obj.ram.split(',');
        		var strout = '<select id="cfappframework_attr_sel">';
    			$.each(ram_array,function(indice){
    				if(ram_array[indice] >= 1000){
    					valor_a_mostrar = ram_array[indice]/1000+'GB';
    				}else{
    					valor_a_mostrar = ram_array[indice]+'MB';
    				}
          			strout += '<option value="'+ram_array[indice]+'">'+valor_a_mostrar+'</option>';
          			
    			});
    			strout +='</select>';
    			$('#cfappframework_attr_sel').ddslick('destroy');
    			$('#cfappframework_attr_div').html(strout);
    			$('#cfappframework_attr_sel').ddslick({ onSelected: function(data){ 
    					$("#cfappframework_attr").val(data.selectedData.value);

    			}});
        	});
    	}
    	//selectText: "Select your favorite social network"
	});

	$("#enviroment").ddslick({
		onSelected: function(data){
			$("#enviroment_id").val(data.selectedData.value);				
		}
	});

	$("#repository").ddslick({
		onSelected: function(data){
			$("#repository_id").val(data.selectedData.value);				
		}
	});

	$("#services").multiselect({
  		noneSelectedText: 'Servicios del PaaS'
  		//selectedList: 4
		});
		
   	$("#subbutton").click(function(){
   		if(selected_zip == 0){
   			$('#appfile_path').val($("#appurl").val()+'|'+$("#svnuser").val()+'|'+$("#svnpass").val());	
   		}
   		return true;
   	});

   	<?php if($model->appfile_type){ echo "fnradio('".$model->appfile_type."');";}?>

});


function fnradio(val){
	if(val == "ZIP"){
		selected_zip = 1;
		$("#zipdiv").show();
		$("#appurldiv").hide();
	}else{
		selected_zip = 0;
		$("#zipdiv").hide();
		$("#appurldiv").show();
	}
}

</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'applications-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="note">Fields width <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>3, 'cols'=>135)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	<br>
	<div class="row">
		<table style="border-width:0px;">
		<tr>
			<td style="border-width:0px;">
				<?php echo $form->labelEx($model,'cfappframework_id'); ?>
				<select id="cfappframework">
				<?php foreach ($cfframeworks as $item) { ?>
					<option value="<?=$item["id"]?>" data-imagesrc="<?=$item['imgsrc']?>"  data-description="<?=$item['description']?>"><?=$item["name"]?></option>
				<?php } ?>
				</select>
				<input type="hidden" name="Applications[cfappframework_id]" id="cfappframework_id" value=""/>

				<?php echo $form->error($model,'cfappframework_id'); ?>
			</td >

			<td style="border-width:0px;">
				<?php echo $form->labelEx($model,'cfappframework_attr'); ?>
				<div id="cfappframework_attr_div">
				</div>
				<input type="hidden" name="Applications[cfappframework_attr]" id="cfappframework_attr" value=""/>
				<?php echo $form->error($model,'cfappframework_attr'); ?>
			</td>
		</tr>
		<tr>
			<td style="border-width:0px;"> 

				<?php echo $form->labelEx($model,'enviroment_id'); ?>
				<select id="enviroment">
				<?php foreach ($enviroments as $item_env) { 
						if($item_env["id"] == $model->enviroment_id){
							$selected = "selected";
						}else{
							$selected = "";
						}
					?>
					<option value="<?=$item_env["id"]?>"  data-description="<?=$item_env['description']?>" <?=$selected?>><?=$item_env["name"]?></option>
				<?php } ?>
				</select>
				<input type="hidden" name="Applications[enviroment_id]" id="enviroment_id" value=""/>
				<?php echo $form->error($model,'enviroment_id'); ?>

			</td>
			<td style="border-width:0px;">
				<label> Servicios </label>
				<select id="services" name="services[]" multiple="multiple">
				<?php foreach ($services as $item_s) { 
						if(in_array($item_s["id"], $appservices)){
							$select = "selected";
						}else{
							$select = "";
						}
					?>
					<option value="<?=$item_s["id"]?>"  <?=$select?>><?=$item_s['description']?></option>
				<?php } ?>
				</select>
			</td>		
		</tr>
		</table>
	</div>
	<br>
	<div class="row">
		
		<?php echo $form->labelEx($model,'initialize_app_url'); ?> (Example: /install.php )
		<?php echo $form->textField($model,'initialize_app_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'initialize_app_url'); ?>
		
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'version'); ?>
		<?php echo $form->textField($model,'version',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'version'); ?>
	
	</div>
	<br>
	<br>
	<div class="row">
		<label>Origen código de fuente</label>
		<table style="border-width:0px;" class="table_origen">
			<tr>
				<td style="border-width:0px;">
					Subir Zip &nbsp;<input type="radio" name="Applications[appfile_type]" value="ZIP" <?php if(($model->appfile_type == "ZIP")||(!$model->appfile_type)){ echo "checked";}?> onclick="fnradio(this.value)">
				</td>
				<td style="border-width:0px;">

					Subversion (SVN)&nbsp;<input type="radio" name="Applications[appfile_type]" value="SVN" <?php if($model->appfile_type == "SVN"){ echo "checked";}?> onclick="fnradio(this.value)">
				</td>
				<td style="border-width:0px;">
					Git&nbsp;<input type="radio" name="Applications[appfile_type]" value="GIT" <?php if($model->appfile_type == "GIT"){ echo "checked";}?> onclick="fnradio(this.value)">
				</td>
				<td style="border-width:0px;" with="100px">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
			</tr>
		</table>

	</div>
	<input type="hidden" id="appfile_path" value="<?=$model->appfile_path?>" name="Applications[appfile_path]" />
	<div class="row" id="zipdiv">
		<label>Select Zip file:</label>
		<? if($model->appfile_path){
			echo "(".$model->appfile_path.")";
		}
		?>
		<? $this->widget('ext.EAjaxUpload.EAjaxUpload',
		array(
        'id'=>'uploadFile',
        'config'=>array(
               'action'=>Yii::app()->createUrl('applications/upload'),
               'allowedExtensions'=>array("zip","txt"),//array("jpg","jpeg","gif","exe","mov" and etc...
               'sizeLimit'=>100*1024*1024,// maximum file size in bytes
               //'minSizeLimit'=>10*1024*1024,// minimum file size in bytes
               'onComplete'=>"js:function(id, fileName, responseJSON){ $('#appfile_path').val(responseJSON.filename); $('#subbutton').removeAttr('disabled');$('#subbutton').val('Save');}",
               //'messages'=>array(
               //                  'typeError'=>"{file} has invalid extension. Only {extensions} are allowed.",
               //                  'sizeError'=>"{file} is too large, maximum file size is {sizeLimit}.",
               //                  'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
               //                  'emptyError'=>"{file} is empty, please select files again without it.",
               //                  'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
               //                 ),
               //'showMessage'=>"js:function(message){ alert(message); }"
               'onSubmit'=>"js:function(id,filename){ $('#subbutton').attr('disabled', 'disabled'); $('#subbutton').val('Uploading zip..');}",
              )
			)); ?>

	</div>
	<div id="appurldiv" style="display:none;">
		<input type="hidden" name="Applications[repository_id]" id="repository_id" value="<?=$model->repository_id?>">
		<div class="row">
			<label>Repository:</label>
			<select  id="repository">
				<option value=""></option>
				<? foreach ($repositories as $repo){?>
					<option value="<?=$repo["id"]?>"  data-description="<?=$repo['type']?>" <? if($model->repository_id == $repo["id"]){ echo "selected"; }?> ><?=$repo["name"]?></option>
				<?}?>
			</select>
		</div>
		<br>
	</div>
	


	<!-- boton -->
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('id' => 'subbutton')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->