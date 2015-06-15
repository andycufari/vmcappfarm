<?php
/* @var $this ApplicationsController */
/* @var $model Applications */

$this->breadcrumbs=array(
	//'Applications'=>array('index'),
	'My Applications',
);

$this->menu=array(
	array('label'=>'List Applications', 'url'=>array('index')),
	array('label'=>'Create Applications', 'url'=>array('create')),
);

?>
<link type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/css/flick/jquery-ui-1.8.24.custom.css" rel="stylesheet" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery-ui-1.8.24.custom.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/codemirror/lib/codemirror.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jscript/codemirror/lib/codemirror.css">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/codemirror/mode/javascript/javascript.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jscript/codemirror/theme/blackboard.css">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/applications.js"></script>

<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#table').dataTable( {
					"bJQueryUI": true,
					//"sPaginationType": "full_numbers",
					"bLengthChange": false,
					"bFilter": true,
					"bInfo": false,
					"bAutoWidth": false } 
				);
			 
/*
				$(function() {

				  var timer = setInterval( RefreshPage, 10000);
				  function RefreshPage() {
				    if (refresh_on){
				    	document.location.href='';
				    }

				    
				  }

				});
*/
			});

</script>

<h1>My Applications</h1>

<p>
This is the list of all your applications deployed or not into the cloud. You can execute rapid actions from here.
</p>
<div class="acciones_principales">

<?
$img_src=  Yii::app()->request->baseUrl.'/images/icons/32/';

?>
	<div class="optionButton">
    	<ul>
        	<li onclick="document.location.href='<? echo Yii::app()->createUrl('applications/create');?>';">
        		
               <img src="<?=$img_src?>application_add.png"></img>
               <span>Crear</span>
            </li>
            <li onclick="document.location.href='<? echo Yii::app()->createUrl('applications/admin');?>';">
               <img src="<?=$img_src?>page_refresh.png"></img>
               <span>Actualizar</span>
            </li>
        </ul>
    </div>

</div>

<div id="msg_dialog"></div>
<div class="panel">
<br/>

<div id="msg_dialog"></div>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="table">
	<thead>
		<tr>
			<th>Name</th>
			<th>App ID</th>
			
			<th>Enviroment</th>
			<th>Framework</th>
			<th>Status</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	<?
	$appcheck = "";
	foreach($apps as $item){

	?>
		<tr class="gradeX">
			<td><a href='#' onclick="do_action('manage','<?=$item["id"]?>');" ><?=$item["name"]?></a></td>
			<td><?=$item["appcode"]?></td>
			
			<td><?=$item["enviroment_name"]?></td>
			<td><?=$item["framework"]?></td>
			<td>
				<?php 
				$img_src=  Yii::app()->request->baseUrl.'/images/icons/16/';
					if($item["provisioningstate"] != 0){
                         echo "<img src='".Yii::app()->request->baseUrl."/images/preloader.gif' with='30px' height='30px'/>";
                         $appcheck.=','.$item["id"];
                    }else{

                        switch ($item["status"]) {
                            case 0:
                                echo "<img src='".$img_src."accept_red.png' >Not deployed</p>";  
                                break;

                            case 1:
                                echo "<img src='".$img_src."arrow_right.png' >Running";
                                
                                break;
                            case -1:
                                echo "<img src='".$img_src."application_error.png' >Error";
                                break;
                            case 2:
                                echo "<img src='".$img_src."arrow_right.png' >Running";
                                break;
                            case 3:
                                echo "<img src=\"".$img_src."arrow_right_red.png\">Stopped";
                                break;
                            case 4:
                                echo "<img src='".$img_src."arrow_right.png' >Running";
                                
                                break;
                            case 9:
                            	echo "<img src=\"".$img_src."application_delete.png\">Deleted";
                                break;
                            default:
                                echo "<img src=\"".$img_src."application_delete.png\">Stopped";
                                break;
                        }

                    }

				?>

			</td>
			
			<td>
					<?php
						echo "<a href=\"#\" onclick=\"do_action('manage','".$item["id"]."')\" title=\"Manage Application.\"><img src=\"".$img_src."cog.png\"></a>";
						echo "&nbsp;";
						echo "<a href=\"#\" onclick=\"do_action('modify','".$item["id"]."')\" title=\"Update Application.\"><img src=\"".$img_src."application_edit.png\"></a>";
						
						if((($item["status"] == 0)||($item["status"] == -1)||($item["status"] == 9))&&($item["provisioningstate"] == 0)){
							echo "&nbsp;";
							echo "<a href=\"#\" onclick=\"do_action('deploy','".$item["id"]."')\" title=\"Deploy Application.\"><img src=\"".$img_src."application_lightning.png\"></a>";
							
						}
						if(($item["status"] != 0)&&($item["status"] != 9)){
							echo "&nbsp;";
							echo "<a href=\"#\" onclick=\"do_action('update','".$item["id"]."')\" title=\"Update Application (Deploy).\"><img src=\"".$img_src."arrow_refresh.png\"></a>";
							echo "&nbsp;";
							$url_log = Yii::app()->createUrl('applications/files');
							echo "<a href=\"#\" onclick=\"document.location.href='".$url_log."/id/".$item["id"]."&path=logs';\" title=\"See Application's log.\"><img src=\"".$img_src."application_osx_terminal.png\"></a>";
							echo "&nbsp;";
							echo "<a href=\"#\" onclick=\"do_action('delete','".$item["id"]."')\" title=\"Delete Application.\"><img src=\"".$img_src."delete.png\"></a>";
							echo "&nbsp;";
						    echo "<a href=\"#\" onclick=\"window.open('http://".str_replace('api', $item["appcode"], $item["endpoint"])."');\" title=\"http://".str_replace('api', $item["appcode"], $item["endpoint"])."\"><img src=\"".$img_src."link.png\"></a>";
							
						}
						if($item["status"] == 3){
							echo "&nbsp;";
							echo "<a href=\"#\" onclick=\"do_action('start','".$item["id"]."')\" title=\"Start Application.\"><img src=\"".$img_src."control_play.png\"></a>";
							//echo "<option value='start'>Arrancar</option>";
						}
						if($item["status"] == 1){
							echo "&nbsp;";
							echo "<a href=\"#\" onclick=\"do_action('stop','".$item["id"]."')\" title=\"Stop Application.\"><img src=\"".$img_src."control_stop.png\"></a>";
							//echo "<option value='stop'>Detener</option>";
							

						}
						if(Yii::app()->user->isAdmin()){
							echo "&nbsp;";
							echo "<a href=\"#\" onclick=\"do_action('reset','".$item["id"]."')\" title=\"Reset.\"><img src=\"".$img_src."delete.png\"></a>";	
						}
						echo "&nbsp;";
						echo "<a href=\"#\" onclick=\"do_action('log','".$item["id"]."')\" title=\"See system's log.\"><img src=\"".$img_src."application_view_xp.png\"></a>";
						
					?>
		

			</td>
		</tr>
	<? } ?>
	</tbody>

</table>
			
</div>
<?php if($appcheck){?>
<script>
setInterval("checkStatus('<?=$appcheck?>')",5000);
</script>
<?}?>