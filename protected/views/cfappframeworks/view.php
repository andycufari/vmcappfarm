<?php
/* @var $this CfappframeworksController */
/* @var $model Cfappframeworks */

$this->breadcrumbs=array(
	'Cfappframeworks'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Cfappframeworks', 'url'=>array('index')),
	array('label'=>'Create Cfappframeworks', 'url'=>array('create')),
	array('label'=>'Update Cfappframeworks', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Cfappframeworks', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cfappframeworks', 'url'=>array('admin')),
);
?>

<h1>View Cfappframeworks #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'cfcode',
		'ram',
		'imgsrc',
	),
)); ?>
