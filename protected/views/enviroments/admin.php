<link type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/css/flick/jquery-ui-1.8.24.custom.css" rel="stylesheet" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery-ui-1.8.24.custom.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jscript/jquery/js/jquery.dataTables.js"></script>

<?php
/* @var $this EnviromentsController */
/* @var $model Enviroments */
$this->breadcrumbs=array(
	'Entornos',
);
$img_src_32=  Yii::app()->request->baseUrl.'/images/icons/32/';
$img_src_16=  Yii::app()->request->baseUrl.'/images/icons/16/';


?>

<script>
$(document).ready(function() {
				$('#table').dataTable( {
					"bJQueryUI": true,
					//"sPaginationType": "full_numbers",
					"bLengthChange": false,
					"bFilter": true,
					"bInfo": false,
					"bAutoWidth": false } 
				);
	});

function fnDeleteEnv(id){
	jConfirm('¿Está seguro que desea eliminar el entorno?', 'Confirmation Dialog', function(r) {
    	if(r){
    		document.location.href='<? echo Yii::app()->createUrl("enviroments/delete")?>'+'/id/'+id;
    	}
	});
	
}

</script>

<h1>Entornos</h1>

<p>
Aquí configure los accesos de entornos a la plataforma Enncloud
</p>

<div class="acciones_principales">

<?
$img_src=  Yii::app()->request->baseUrl.'/images/icons/32/';

?>
	<div class="optionButton">
    	<ul>
        	<li onclick="document.location.href='<? echo Yii::app()->createUrl('enviroments/create');?>';">
        		
               <img src="<?=$img_src?>application_add.png"></img>
               <span>Crear</span>
            </li>
            
        </ul>
    </div>

</div>
<br>
<br>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="table">
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Descripción</th>
			<th>Endpoint</th>
			<th>Usuario</th>
			<th>Contraseña</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
	<?
	
	foreach($model as $item){
	
	?>
		<tr class="gradeX">
			<td><a href='#' onclick="document.location.href='<? echo Yii::app()->createUrl('enviroments/update',array('id'=> $item["id"]));?>';"><?=$item["name"]?></a></td>
			<td><?=$item["description"]?></td>
			<td><?=$item["endpoint"]?></td>
			<td><?=$item["user"]?></td>
			<td><?=$item["pass"]?></td>
			<td>
				<a href="<? echo Yii::app()->createUrl('enviroments/update',array('id'=> $item["id"]));?>"><img title="Modificar" src="<?=$img_src_16?>edit.png"></a>
				<a href="#" onclick="fnDeleteEnv('<?=$item["id"]?>')"><img title="Eliminar" src="<?=$img_src_16?>delete.png"></a>

			</td>
		</tr>
	<? } ?>
	</tbody>

</table>
