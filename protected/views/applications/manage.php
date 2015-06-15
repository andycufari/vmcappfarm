<?php
/* @var $this ApplicationsController */
/* @var $model Applications */
$this->breadcrumbs=array(
	'My Applications'=>array('admin'),
	'Manage',
);

$jscript_final= "";
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
				$('.supertable').dataTable( {
					"bJQueryUI": true,
					//"sPaginationType": "full_numbers",
					"bLengthChange": false,
					"bFilter": false,
					"bInfo": false,
					"bAutoWidth": false } );

			} );


function del_env(thevar){
	do_action('del_env','<?=$app->id?>',thevar);

}

function add_service(){
	do_action('add_service','<?=$app->id?>',$("#addservice").val());
}

</script>

<?
$img_src=  Yii::app()->request->baseUrl.'/images/icons/32/';
$img_src_16=  Yii::app()->request->baseUrl.'/images/icons/16/';
?>

<h1>Manage Application: <?=$app->name?></h1>

<p>
Application control panel.
<br/><br/><strong>Status:</stong>
<?php 
				
	if($app->provisioningstate != 0){
                 echo "<img src='".Yii::app()->request->baseUrl."/images/preloader.gif' with='30px' height='30px'/>";
            }else{

                switch ($app->status) {
                    case 0:
                        echo "<img src='".$img_src_16."accept_red.png' >Not deployed</p>";  
                        break;

                    case 1:
                        echo "<img src='".$img_src_16."arrow_right.png' >Running";
                        
                        break;
                    case -1:
                        echo "<img src='".$img_src_16."application_error.png' >Error";
                        break;
                    case 2:
                        echo "<img src='".$img_src_16."arrow_right.png' >Running";
                        break;
                    case 3:
                        echo "<img src=\"".$img_src_16."arrow_right_red.png\">Stopped";
                        break;
                    case 4:
                        echo "<img src='".$img_src_16."arrow_right.png' >Running";
                        
                        break;
                    case 9:
                    	echo "<img src=\"".$img_src_16."application_delete.png\">Deleted";
                        break;
                    default:
                        echo "<img src=\"".$img_src_16."application_delete.png\">Stopped";
                        break;
                }

            }

		?>


</p>


<div class="acciones_principales">


	<div class="optionButton">
    	<ul>
        	<li onclick="do_action('modify','<?=$app->id?>')">
        		
               <img src="<?=$img_src?>application_edit.png"></img>
               <span>Update</span>
            </li>
            <li onclick="window.open('http://<?=$platform["app_domain"]?>')">
               <img src="<?=$img_src?>link.png"></img>
               <span>Open</span>
            </li>
            <?if($platform["files"] != 0){?>
            <li onclick="document.location.href='<? echo Yii::app()->createUrl('applications/files'); ?>?id=<?=$app->id?>';" >
               <img src="<?=$img_src?>file_manager.png"></img>
               <span>Files</span>
            </li>
            <li onclick="document.location.href='<? echo Yii::app()->createUrl('applications/files'); ?>?id=<?=$app->id?>&path=logs';" >
               <img src="<?=$img_src?>application_osx_terminal.png"></img>
               <span>Logs</span>
            </li>
            <?}?>
             <li onclick="document.location.href='<? echo Yii::app()->createUrl('applications/admin');?>';">
               <img src="<?=$img_src?>page_refresh.png"></img>
               <span>Refresh</span>
            </li>
        </ul>
    </div>

</div>



<div id="msg_dialog"></div>
<div class="panel">
<br/>

<h1>VMC Information: <?=$platform["entorno"]?></h1>
<!--
array(11) { 
	["name"]=> string(19) "enncloud-dev-pdu907" 
	["staging"]=> array(2) { 
		["model"]=> string(3) "php" ["stack"]=> string(3) "php" } 
	["uris"]=> array(1) { [0]=> string(27) "enncloud-dev-pdu907.vcap.me" } 
	["instances"]=> int(2) 
	["runningInstances"]=> int(2) 
	["resources"]=> array(3) { 
	["memory"]=> int(128) 
	["disk"]=> int(2048) 
	["fds"]=> int(256) } 
	["state"]=> string(7) "STARTED" 
	["services"]=> array(1) { [0]=> string(25) "redis-enncloud-dev-pdu907" } ["version"]=> string(42) "2f8b7f181db4390be41c0c56d1092934428726a5-2" ["env"]=> array(6) { [0]=> string(48) "OAUTH_CLIENT_ID=4bc2ca8179ba040f2aa7843114d514cb" [1]=> string(92) "OAUTH_CLIENT_SECRET=2c7908483d7bb3e74d59e40709a64f4ecf5213994bc2ca8179ba040f2aa7843114d514cb" [2]=> string(22) "OAUTH_CLIENT_USE_SSL=1" [3]=> string(40) "OAUTH_CLIENT_URL_TOKEN=/oauth2/authorize" [4]=> string(40) "OAUTH_CLIENT_URL_AUTHORIZE=/oauth2/token" [5]=> string(38) "cfkey=c54c8610206e5417f106ed619fed7a39" } ["meta"]=> array(4) { ["debug"]=> NULL ["console"]=> bool(false) ["version"]=> int(5) ["created"]=> int(1349920170) } }

-->

<?if($platform["vmc_status"] == 0){
	echo "<div class=\"flash-notice\">The application is not deployed.</div>";
}?>
<?if($platform["vmc_status"] == -1){
	echo "<div class=\"flash-notice\"><p>Connection lost! Wait 10 sec..<br></p></div>";
	$jscript_final .= "setInterval(\"document.location.href=''\",10000);";
}?>
<?if(($platform["files"] == 0)&&($platform["vmc_status"] == 1)&&($app->provisioningstate == 0)){
	echo "<div class=\"flash-notice\">There is not application's files. If you just execute an action, please refresh. <a href=\"\">>Refrescar</a></div>";
}?>
<?php if($platform["vmc_status"] == 1){?>
<table cellpadding="0" cellspacing="0" border="0" class="display supertable" >
	<thead>
		<tr>
			<th>Application</th>
			<th>Url</th>
			<th>Model</th>
			<th>Status</th>
			<th>Instances</th>
			<th>Ram</th>
			<th>Disk</th>
			
		</tr>
	</thead>
		<tr class="gradeX">
		  <td><?=$cfinfo["name"]?></td>
		  <td><a href="<?=$cfinfo["uris"][0]?>" target="_blank"><?=$cfinfo["uris"][0]?></a></td>
		  <td><?=$cfinfo["staging"]["model"]?></td>
		  <td><?=$cfinfo["state"]?></td>
		  <td><?=$cfinfo["runningInstances"]?></td>
		  <td><?=$cfinfo["resources"]["memory"]?>MB</td>
		  <td><?=$cfinfo["resources"]["disk"]?>MB</td>
		</tr>

	<tbody>
		
	</tbody>

</table>
<?}?>
<br>
<br>
<h1>Acciones a realizar</h1>
	<?php
		$appcheck = "";
		if($app->provisioningstate != 0){
                echo "The platform is executing an action, please wait... <img src='".Yii::app()->request->baseUrl."/images/preloader.gif' with='30px' height='30px'/>";
                $appcheck = $app->id;
        }else{
        	?>
        		<div class="optionButton">
		    	<ul>
		    		
		        	<li onclick="do_action('deploy','<?=$app->id?>')">
		               <img src="<?=$img_src?>application_lightning.png" title="Deploy application"></img>
		               <span>Deploy</span>
		            </li>
		        
			        <?if($app->status == 3){?>
			         	<li onclick="do_action('start','<?=$app->id?>')">
		               		<img src="<?=$img_src?>control_play.png" title="Start application"></img>
		               		<span>Start</span>
		            	</li>
		            <?}?>
		            <?if(($app->status == 1)||($app->status == 2)||($app->status == 4)){?>
		            	<li onclick="do_action('stop','<?=$app->id?>')">
		               		<img src="<?=$img_src?>control_stop.png" title="Stop application"></img>
		               		<span>Stop</span>
		            	</li>
		            	<li onclick="do_action('scale','<?=$app->id?>')">
		               		<img src="<?=$img_src?>application_get.png" title="Scale application"></img>
		               		<span>Scale</span>
		            	</li>
		            	<li onclick="do_action('descale','<?=$app->id?>')">
		               		<img src="<?=$img_src?>application_put.png" title="Unscale application"></img>
		               		<span>Unscale</span>
		            	</li>
		            <?}?>
		            <? if($app->provisioning_log != ""){?>
						<li onclick="do_action('log','<?=$app->id?>')">
		               		<img src="<?=$img_src?>application_xp_terminal.png" title="Platform log"></img>
		               		<span>Platform Log</span>
		            	</li>
		            <?}?>
		            <?if(($app->status != 0)&&($app->status != 9)){?>
						<li onclick="do_action('update','<?=$app->id?>')">
		               		<img src="<?=$img_src?>arrow_refresh.png" title="Update application"></img>
		               		<span>Update</span>
		            	</li>
		     
		            	<li onclick="do_action('delete','<?=$app->id?>')">
		               		<img src="<?=$img_src?>delete.png" title="Delete application"></img>
		               		<span>Delete</span>
		            	</li>

		            <?}?>
		        </ul>
		    	</div>
			<?
	
			echo "<br>";
			echo "<div class=\"optionButton\">";
			
			if(!$app->initialize_app_url){
				echo "<div class=\"flash-notice\">This application has not install script.</div>";
			}else{
				echo "<a href=\"#\" onclick=\"do_action('install','".$app->id."')\" ><img src=\"".$img_src_16."arrow_right.png\">Execute Install:".$app->initialize_app_url."</a><br>";
				if($app->install_log){
					echo "<a href=\"#\" onclick=\"do_action('install_log','".$app->id."')\" ><img src=\"".$img_src_16."arrow_right.png\">Watch install log</a><br>";	
				}
				
			}
			
			echo "</div>";

		}
	?>

<br>
<br>
<?php if($platform["vmc_status"] == 1){ ?>
<h1>VMC Services</h1>
<!-- ["services"]=> array(1) { 
	[0]=> string(25) "redis-enncloud-dev-pdu907" } 
	["version"]=> string(42) "2f8b7f181db4390be41c0c56d1092934428726a5-2" 
	["env"]=> array(6) { [0]=> string(48) "OAUTH_CLIENT_ID=4bc2ca8179ba040f2aa7843114d514cb" [1]=> string(92) "OAUTH_CLIENT_SECRET=2c7908483d7bb3e74d59e40709a64f4ecf5213994bc2ca8179ba040f2aa7843114d514cb" [2]=> string(22) "OAUTH_CLIENT_USE_SSL=1" [3]=> string(40) "OAUTH_CLIENT_URL_TOKEN=/oauth2/authorize" [4]=> string(40) "OAUTH_CLIENT_URL_AUTHORIZE=/oauth2/token" [5]=> string(38) "cfkey=c54c8610206e5417f106ed619fed7a39" } ["meta"]=> array(4) { ["debug"]=> NULL ["console"]=> bool(false) ["version"]=> int(5) ["created"]=> int(1349920170) } }-->
<div class="form">
	<div class="row">
		 <?php echo "<img src=\"".$img_src_16."arrow_right.png\">"; ?> Add service to this app,  
		 <select id="addservice" class="select" style="width:150px">
		 	<?php 
		 		foreach($platform["services"] as $cfservice){
		 			echo "<option value='".$cfservice["id"]."'>".$cfservice["name"]."</option>";
		 		}
		 	?>
		 </select>

		 <input type="button" onclick="add_service();" value="Ok"> 
	</div>
</div>
<br>
<table cellpadding="0" cellspacing="0" border="0" class="display supertable">
	<thead>
		<tr>
			<th>Name</th>
			<th>Action</td> 
		</tr>
	</thead>
	<tbody>
		<?php 
		if(isset($cfinfo["services"])){
			foreach ($cfinfo["services"] as $serv){
				echo "<tr class=\"gradeX\">";
				echo "<td>".$serv."</td>";
				echo "<td><a href=\"#\" onclick=\"do_action('delete_service','".$app->id."','".$serv."');\" title=\"Delete service.\"><img src=\"".$img_src_16."delete.png\"></a></td>";
				echo "</tr>";
			}	
		}
		
		?>
	</tbody>

</table>
			
<br>
<br>
<br>
<h1>Enviroment vars</h1>
<div class="form">
	<div class="row">
		 <?php echo "<img src=\"".$img_src_16."arrow_right.png\">"; ?> Add enviroment var to this app, Key: <input type="text" id="env_key"> Value: <input type="text" id="env_value"> <input type="button" onclick="add_env()" value="Ok"> 
	</div>
</div>
<br>

<!-- ["services"]=> array(1) { 
	[0]=> string(25) "redis-enncloud-dev-pdu907" } 
	["version"]=> string(42) "2f8b7f181db4390be41c0c56d1092934428726a5-2" 
	["env"]=> array(6) { [0]=> string(48) "OAUTH_CLIENT_ID=4bc2ca8179ba040f2aa7843114d514cb" [1]=> string(92) "OAUTH_CLIENT_SECRET=2c7908483d7bb3e74d59e40709a64f4ecf5213994bc2ca8179ba040f2aa7843114d514cb" [2]=> string(22) "OAUTH_CLIENT_USE_SSL=1" [3]=> string(40) "OAUTH_CLIENT_URL_TOKEN=/oauth2/authorize" [4]=> string(40) "OAUTH_CLIENT_URL_AUTHORIZE=/oauth2/token" [5]=> string(38) "cfkey=c54c8610206e5417f106ed619fed7a39" } ["meta"]=> array(4) { ["debug"]=> NULL ["console"]=> bool(false) ["version"]=> int(5) ["created"]=> int(1349920170) } }-->
<table cellpadding="0" cellspacing="0" border="0" class="display supertable">
	<thead>
		<tr>
			<th>Key</th>
			<th>Value</td>
			<th>Action</td> 
			
		</tr>
	</thead>
	<tbody>

		<?php 
		foreach ($cfinfo["env"] as $var){
			if($class = "gradeX"){ $class = "gradeA";}else{ $class= "gradeX";}
			$var_aux = split("=",$var);
			echo "<tr class=\"$class\">";
			echo "<td>".$var_aux[0]."</td>";
			echo "<td>".$var_aux[1]."</td>";
			echo "<td><a href=\"#\" onclick=\"del_env('".$var."');\" title=\"Delete Enviroment var.\"><img src=\"".$img_src_16."delete.png\"></a></td>";
			echo "</tr>";
		}
		?>
	</tbody>

</table>
<?php } ?>
</div>
<?php if($appcheck){?>
<script>
setInterval("checkStatus('<?=$appcheck?>')",5000);
</script>
<?}?>
<?if($jscript_final){?>
<script>
	<?=$jscript_final?>
</script>

<?}?>