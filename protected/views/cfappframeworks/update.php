<?php
/* @var $this CfappframeworksController */
/* @var $model Cfappframeworks */

$this->breadcrumbs=array(
	'Cfappframeworks'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Cfappframeworks', 'url'=>array('index')),
	array('label'=>'Create Cfappframeworks', 'url'=>array('create')),
	array('label'=>'View Cfappframeworks', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Cfappframeworks', 'url'=>array('admin')),
);
?>

<h1>Update Cfappframeworks <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>