<?php
/* @var $this EnviromentsController */
/* @var $model Enviroments */

$this->breadcrumbs=array(
	'Enviroments'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Enviroments', 'url'=>array('index')),
	array('label'=>'Manage Enviroments', 'url'=>array('admin')),
);
?>

<h1>Create Enviroment</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>