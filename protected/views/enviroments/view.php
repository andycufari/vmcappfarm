<?php
/* @var $this EnviromentsController */
/* @var $model Enviroments */

$this->breadcrumbs=array(
	'Enviroments'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Enviroments', 'url'=>array('index')),
	array('label'=>'Create Enviroments', 'url'=>array('create')),
	array('label'=>'Update Enviroments', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Enviroments', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Enviroments', 'url'=>array('admin')),
);
?>

<h1>View Enviroments #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'endpoint',
		'user',
		'pass',
		'name',
		'description',
		'created_at',
		'user_id',
	),
)); ?>
