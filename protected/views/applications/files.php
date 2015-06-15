<?php
/* @var $this ApplicationsController */
/* @var $model Applications */
$this->breadcrumbs=array(
	'My Applications'=>array('admin'),
	
	'Files',
);
?>
<link type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/css/flick/jquery-ui-1.8.24.custom.css" rel="stylesheet" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery-ui-1.8.24.custom.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/codemirror/lib/codemirror.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jscript/codemirror/lib/codemirror.css">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/codemirror/mode/javascript/javascript.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jscript/codemirror/theme/blackboard.css">
<script type="text/javascript" charset="utf-8">
var globalvar;
var myCodeMirror;
			$(document).ready(function() {
				$('.supertable').dataTable( {
					"bJQueryUI": true,
					//"sPaginationType": "full_numbers",
					"bLengthChange": false,
					"bFilter": false,
					"bInfo": false,
					"bAutoWidth": false } );

			} );



</script>

<h1>File browse application: <?=$app->name?></h1>



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
          

        </ul>
    </div>

</div>

<div id="msg_dialog"></div>
<div class="panel">

	
<br/>
<?if($platform["vmc_status"] == 0){
	echo "<div class=\"flash-notice\">Application not deployed or connection problem.</div>";
}?>
<?if(($platform["files"] == 0)&&($platform["vmc_status"] == 1)){
	echo "<div class=\"flash-notice\">There is no application's files.</div>";
}?>




<?php if(($platform["vmc_status"] == 1)&&($platform["files"] == 1)){?>
<table cellpadding="0" cellspacing="0" border="0" class="display supertable" >
	<thead>
		<tr>
			<th>Name</th>
			<th>Size</th>
		</tr>
	</thead>
	<?php
		if(($platform["path"] != 'app')||($platform["path"] != 'app/')){

			$arr_path = explode('/',$platform["path"]);
			$count_path = count($arr_path)-1;
			if(!$arr_path[$count_path]){ $count_path -=1;}

			$strpath = "";
			for($i=0;$i<$count_path;$i++){

				$strpath .= $arr_path[$i]."/";
				
			}
		?>
		<tr class="gradeX">
			<? echo "<td><a href='".Yii::app()->createUrl('applications/files')."?id=".$app->id."&path=".$strpath."'>..</a></td>"; ?>
			<td>-</td>
		</tr>


		<?
		}
	?>

	<?php foreach($platform["arrfiles"] as $file){ 
	  		$arr_subfile= explode(' ',$file);
	  		$count = substr_count($file,' ');
	  		?>
		<tr class="gradeX">
		<? if($arr_subfile[$count] == "-"){
			echo "<td><a href='".Yii::app()->createUrl('applications/files')."?id=".$app->id."&path=".$platform['path'].'/'.$arr_subfile[0]."'>".$arr_subfile[0]."</a></td>";
		}else{
			echo "<td><a href='".Yii::app()->createUrl('applications/viewfile')."?id=".$app->id."&path=".$platform['path'].'/'.$arr_subfile[0]."'>".$arr_subfile[0]."</a></td>";
		}
		  ?>
		  <td><?=$arr_subfile[$count]?></td>
		 
		</tr>
	<?}?>
	<tbody>
		
	</tbody>

</table>
<?}?>
<br>

</div>

