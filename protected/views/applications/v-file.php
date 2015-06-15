<?php
/* @var $this ApplicationsController */
/* @var $model Applications */
$array_aux = explode("/",$platform["path"]);
$num = count($array_aux);
$filename = $array_aux[$num-1];
$volver_path = "";
for($i=0;$i<$num-1;$i++){
	if($volver_path == ""){
		$volver_path.= $array_aux[$i];
	}else{
		$volver_path.="/".$array_aux[$i];
	}
}

$this->breadcrumbs=array(
	'My Applications'=>array('admin'),
	'Files'=>array('files'),
);
?>
<link type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/css/flick/jquery-ui-1.8.24.custom.css" rel="stylesheet" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery-ui-1.8.24.custom.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/editarea/edit_area_full.js"></script>

<script type="text/javascript" charset="utf-8">
<?php

//saco la extension del archivo para poner la sytax
if($platform["files"]){
	$file_ext = substr($platform["path"], strrpos($platform["path"], '.') + 1);

	switch ($file_ext) {
			case 'php':
				$ext = 'php';
				break;
			case 'css':
				$ext = 'css';
				break;
			case 'rb':
				$ext = 'ruby';
				break;
			case 'sql':
				$ext = 'sql';
				break;
			case 'htm':
				$ext = 'html';
				break;
			case 'html':
				$ext = 'html';
				break;
			case 'java':
				$ext = 'java';
				break;
			case 'js':
				$ext = 'js';
				break;
			case 'py':
				$ext = 'pyton';
				break;
			case 'pl':
				$ext = 'perl';
				break;

			default:
				$ext = 'basic';
				break;
		}	
}else{
	$ext = "basic";
}



?>
var globalvar;
var myCodeMirror;
			$(document).ready(function() {
				editAreaLoader.init({
					id: "codetext"	// id of the textarea to transform		
					,start_highlight: <? if($ext != "basic"){ echo "true";}else{ echo "false"; }?>	// if start with highlight
					,allow_resize: "both"
					,allow_toggle: true
					,word_wrap: true
					,syntax: "<?=$ext?>"	
				});
	    		
			});



</script>

<h1>Explorando archivos de aplicación: <?=$app->name?></h1>

<div class="acciones_principales">

<?
$img_src=  Yii::app()->request->baseUrl.'/images/icons/32/';
$img_src_16=  Yii::app()->request->baseUrl.'/images/icons/16/';
?>
	<div class="optionButton">
    	<ul>
        	<li onclick="document.location.href='<? echo Yii::app()->createUrl('applications/manage'); ?>?id=<?=$app->id?>';" >
               <img src="<?=$img_src?>cog.png"></img>
               <span>Manage</span>
            </li>
            <li onclick="document.location.href='<? echo Yii::app()->createUrl('applications/files'); ?>?id=<?=$app->id?>&path=<?=$volver_path?>';" >
               <img src="<?=$img_src?>file_manager.png"></img>
               <span>Back</span>
            </li>
            <li onclick="document.location.href='';">
               <img src="<?=$img_src?>page_refresh.png"></img>
               <span>Refresh</span>
            </li>

        </ul>
    </div>

</div>
<br>
<h1>>> Archivo: <?=$platform["path"]?></h1>
<div id="msg_dialog"></div>
<div class="panel">

	
<?if($platform["vmc_status"] == 0){
	echo "<div class=\"flash-notice\">Error, application not deployed or connection problem. Please try again later.</div>";
}?>
<?if(($platform["files"] == 0)&&($platform["vmc_status"] == 1)){
	echo "<div class=\"flash-notice\">File not found.</div>";
}?>




<?php if(($platform["vmc_status"] == 1)&&($platform["files"] == 1)){?>
	<div id="code">
		<textarea id="codetext" style="width:100%;height:640px"><?=$platform["strfile"]?></textarea>

	</div>
<?}?>
<br>

</div>

