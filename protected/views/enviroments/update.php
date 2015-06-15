<?php
/* @var $this EnviromentsController */
/* @var $model Enviroments */

$this->breadcrumbs=array(
	'Enviroments'=>array('admin'),
	'Update',
);

$this->menu=array(
	array('label'=>'List Enviroments', 'url'=>array('index')),
	array('label'=>'Create Enviroments', 'url'=>array('create')),
	array('label'=>'View Enviroments', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Enviroments', 'url'=>array('admin')),
);
?>

<h1>Update Enviroment: <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>