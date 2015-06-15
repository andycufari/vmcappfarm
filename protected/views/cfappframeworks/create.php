<?php
/* @var $this CfappframeworksController */
/* @var $model Cfappframeworks */

$this->breadcrumbs=array(
	'Cfappframeworks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Cfappframeworks', 'url'=>array('index')),
	array('label'=>'Manage Cfappframeworks', 'url'=>array('admin')),
);
?>

<h1>Create Cfappframeworks</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>